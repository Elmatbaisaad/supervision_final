FROM python:3.10

WORKDIR /app

COPY requirements.txt /app

RUN pip install -r requirements.txt --no-cache-dir
RUN apt-get update && apt-get install -y iputils-ping

COPY . /app


