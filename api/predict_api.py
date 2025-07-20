from fastapi import FastAPI, HTTPException
import pandas as pd
import numpy as np
from tensorflow.keras.models import load_model
import joblib
import uvicorn

app = FastAPI()

# Load model and preprocessing objects
model = load_model('api/prioritation-model.h5')
le_status = joblib.load('api/le_status.pkl')
le_priority = joblib.load('api/le_priority.pkl')
scaler = joblib.load('api/scaler.pkl')

def parse_due_date(date_str):
    if not isinstance(date_str, str) or date_str == '-1' or not date_str.strip():
        return pd.NaT
    try:
        # Try dataset format (DD/MM/YYYY HH:MM)
        return pd.to_datetime(date_str, format='%d/%m/%Y %H:%M', errors='coerce')
    except (ValueError, TypeError):
        # Fallback to Laravel format (YYYY-MM-DD HH:MM:SS)
        return pd.to_datetime(date_str, format='%Y-%m-%d %H:%M:%S', errors='coerce')

@app.post("/predict")
async def predict(data: dict):
    try:
        tasks = data.get('tasks', [])
        if not tasks:
            raise HTTPException(status_code=400, detail="No tasks provided")

        # Convert input to DataFrame
        df = pd.DataFrame(tasks)
        required_columns = ['title', 'due_date', 'estimated_time', 'parent_id', 'status']
        if not all(col in df.columns for col in required_columns):
            raise HTTPException(status_code=400, detail=f"Missing required columns. Expected: {required_columns}")

        # Preprocess data
        df_processed = df[['due_date', 'estimated_time', 'parent_id', 'status']].copy()
        
        # Title-case and encode status
        df_processed['status'] = df_processed['status'].astype(str).str.title()
        if not all(df_processed['status'].isin(le_status.classes_)):
            invalid_status = df_processed[~df_processed['status'].isin(le_status.classes_)]['status'].unique()
            raise HTTPException(status_code=400, detail=f"Invalid status values: {invalid_status}. Expected: {le_status.classes_}")
        df_processed['status'] = le_status.transform(df_processed['status'])

        # Parse due_date
        df_processed['due_date'] = df_processed['due_date'].apply(parse_due_date)
        default_date = pd.to_datetime('2025-07-16 09:15:00')
        df_processed['due_date'] = df_processed['due_date'].fillna(default_date)
        df_processed['due_date'] = df_processed['due_date'].map(lambda x: x.timestamp() / (24 * 3600))

        # Handle parent_id
        df_processed['parent_id'] = df_processed['parent_id'].fillna(-1).astype(float)

        # Scale numerical features
        df_processed[['due_date', 'estimated_time', 'parent_id']] = scaler.transform(
            df_processed[['due_date', 'estimated_time', 'parent_id']]
        )

        # Predict
        pred_probs = model.predict(df_processed)
        pred = np.argmax(pred_probs, axis=1)
        pred_labels = le_priority.inverse_transform(pred)

        # Assign order based on priority
        priority_order = {'High': 1, 'Medium': 2, 'Low': 3}
        orders = [priority_order[priority] for priority in pred_labels]

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