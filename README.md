cd C:/laragon/www/smart-task-management-app
uvicorn api.predict_api:app --reload
uvicorn api.predict_api:app --host 0.0.0.0 --port 8000
conda activate tf_env
jupyter notebook
