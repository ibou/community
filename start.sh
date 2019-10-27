#!/bin/bash

# echo Stopping serveurs...
sudo service apache2 stop && sudo service mysql stop

echo Démarrage des containers...
docker-compose down
docker-compose up --build 

echo Containers démarrés
