#!/bin/bash

echo Démarrage des containers...

docker-compose down
docker-compose up --build 

echo Containers démarrés
