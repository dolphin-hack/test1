FROM ubuntu:22.04

RUN apt update -y && apt upgrade -y
RUN apt install -y nano python3 python3-pip
RUN apt install -y file

RUN mkdir /data
ADD ./src /data
WORKDIR /data

RUN pip3 install -r requirements.txt

CMD ["python3", "/data/app.py"]