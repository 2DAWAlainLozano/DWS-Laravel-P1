FROM python:3.10-slim

WORKDIR /app

# Install system dependencies for OpenCV and DeepFace
RUN apt-get update && apt-get install -y \
    libgl1 \
    libglib2.0-0 \
    libsm6 \
    libxext6 \
    libxrender-dev \
    wget \
    && rm -rf /var/lib/apt/lists/*

COPY requirements.txt .

RUN pip install --no-cache-dir -r requirements.txt

COPY . .

# Pre-download the Facenet model weights so they're ready at runtime
RUN mkdir -p /root/.deepface/weights/ && \
    wget -q https://github.com/serengil/deepface_models/releases/download/v1.0/facenet_weights.h5 \
    -O /root/.deepface/weights/facenet_weights.h5

CMD ["uvicorn", "main:app", "--host", "0.0.0.0", "--port", "5000"]
