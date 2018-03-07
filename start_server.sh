#!/bin/sh

docker rm -f ms-role-api-mongo
docker rm -f ms-role-api-app
docker rm -f ms-role-api-swagger
docker-compose rm

docker-compose build mongo
docker-compose up -d mongo

docker-compose build swagger
docker-compose up -d swagger

docker-compose build app
docker-compose up -d app