from fastapi import FastAPI, HTTPException
import pandas as pd
import numpy as np
from datetime import datetime
from tensorflow.keras.models import load_model
import joblib
import uvicorn

app = FastAPI()

# Load model and preprocessing objects
try:
    model = load_model('task_priority_model.h5')
    ohe_status = joblib.load('status_ohe.pkl')
    le_priority = joblib.load('label_encoder.pkl')
    scaler = joblib.load('scaler.pkl')
    tfidf = joblib.load('tfidf_vectorizer.pkl')
except Exception as e:
    raise Exception(f"Failed to load model or preprocessors: {str(e)}")

# Hardcode urgency_keywords and priority_weights from training
urgency_keywords = {
    'urgent': 2, 'immediate': 2, 'tomorrow': 1.5, 'overdue': 2, 'deadline': 1.5,
    'exceeded': 1.5, 'fix': 1, 'bug': 1, 'security': 2, 'critical': 2,
    'high': 1.5, 'priority': 1.5, 'emergency': 2, 'review': 1,
    'audit': 1.5, 'compliance': 1.5, 'recovery': 2, 'test case': 1.5,
    'data recovery': 2
}

priority_weights = {
    'Security': 2, 'Development': 1.5, 'Testing': 1.5, 'Planning': 1.2,
    'Analytics': 1, 'Content': 0.8, 'Training': 0.8, 'Review': 1,
    'Meeting': 0.7, 'Documentation': 0.7, 'Design': 1,
    'Maintenance': 1.8, 'Release': 1.5, 'Research': 0.8, 'Presentation': 0.7
}

# Define current date and days cap from training
current_date = datetime(2025, 8, 19)
days_cap = 180

def clean_status(s):
    if pd.isna(s) or not str(s).strip():
        return 'pending'
    s = str(s).strip().replace('.', '').replace(' ', '').lower()
    if 'pending' in s:
        return 'pending'
    elif 'inprogress' in s or 'in_progress' in s:
        return 'in_progress'
    elif 'completed' in s:
        return 'completed'
    else:
        return 'pending'

def parse_days_to_due(date_str):
    if not isinstance(date_str, str) or date_str == '-1' or not date_str.strip():
        return days_cap
    try:
        # Try Laravel format (YYYY-MM-DD HH:MM:SS)
        due = pd.to_datetime(date_str, format='%Y-%m-%d %H:%M:%S', errors='coerce')
        if pd.isna(due):
            # Fallback to dataset format (DD/MM/YYYY HH:MM)
            due = pd.to_datetime(date_str, format='%d/%m/%Y %H:%M', errors='coerce')
        if pd.isna(due):
            return days_cap
        days = (due - current_date).total_seconds() / (3600 * 24)
        return min(days, days_cap)
    except (ValueError, TypeError):
        return days_cap

def get_urgency_score(title):
    if pd.isna(title) or not str(title).strip():
        return 0
    title_lower = str(title).lower()
    score = sum(weight for phrase, weight in urgency_keywords.items() if phrase in title_lower)
    return score

def get_task_type_weight(task_type):
    if pd.isna(task_type) or not str(task_type).strip():
        return 1
    return priority_weights.get(str(task_type).title(), 1)

@app.post("/predict")
async def predict(data: dict):
    try:
        tasks = data.get('tasks', [])
        if not tasks:
            raise HTTPException(status_code=400, detail="No tasks provided")

        # Convert input to DataFrame
        df = pd.DataFrame(tasks)
        required_columns = ['title', 'due_date', 'estimated_time', 'parent_id', 'status', 'task_type']
        if not all(col in df.columns for col in required_columns):
            raise HTTPException(status_code=400, detail=f"Missing required columns. Expected: {required_columns}")

        # Preprocess data
        # Compute urgency_score
        df['urgency_score'] = df['title'].apply(get_urgency_score)

        # Compute task_type_weight
        df['task_type_weight'] = df['task_type'].apply(get_task_type_weight)

        # Compute days_to_due
        df['days_to_due'] = df['due_date'].apply(parse_days_to_due)

        # Handle estimated_time (use training mean for NaN)
        train_estimated_time_mean = scaler.mean_[0]  # First feature: estimated_time
        df['estimated_time'] = df['estimated_time'].fillna(train_estimated_time_mean).astype(float)

        # Compute has_parent
        df['has_parent'] = df['parent_id'].apply(lambda x: 0 if pd.isna(x) or str(x).strip() == '' or str(x) == '-1' else 1)

        # Clean and one-hot encode status
        df['status'] = df['status'].apply(clean_status)
        if not all(df['status'].isin(ohe_status.categories_[0])):
            invalid_status = df[~df['status'].isin(ohe_status.categories_[0])]['status'].unique()
            raise HTTPException(status_code=400, detail=f"Invalid status values: {invalid_status}. Expected: {ohe_status.categories_[0]}")
        status_onehot = ohe_status.transform(df[['status']])

        # TF-IDF for title
        title_tfidf = tfidf.transform(df['title'].fillna('')).toarray()

        # Scale numerical features
        num_df = df[['estimated_time', 'days_to_due', 'urgency_score', 'task_type_weight']]
        if list(num_df.columns) != scaler.feature_names_in_.tolist():
            raise HTTPException(status_code=422, detail=f"Feature names mismatch. Expected: {scaler.feature_names_in_}, Got: {num_df.columns.tolist()}")
        num_scaled = scaler.transform(num_df)

        # Prepare input for model
        X = np.hstack((title_tfidf, status_onehot, num_scaled, df['has_parent'].values.reshape(-1, 1)))

        # Predict
        pred_probs = model.predict(X, verbose=0)
        pred = np.argmax(pred_probs, axis=1)
        pred_labels = le_priority.inverse_transform(pred)

        # Assign order based on priority
        priority_order = {'High': 1, 'Medium': 2, 'Low': 3}
        orders = [priority_order.get(priority, 3) for priority in pred_labels]

        # Prepare response
        response = {
            'tasks': [
                {
                    'title': task['title'],
                    'priority': pred_labels[i],
                    'order': orders[i]
                } for i, task in enumerate(tasks)
            ]
        }
        return response
    except Exception as e:
        raise HTTPException(status_code=422, detail=f"Processing error: {str(e)}")

if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=8000)