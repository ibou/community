#!/bin/bash

if [ $# -eq 0 ];
then
echo "Vous avez passé $# paramètres : tapez en arguments '--help' ou 'listCommandes' pour voir les différentes options"
else
echo "Vous avez passé $# paramètres"
fi
# Affichage du nombre de paramètres

# Liste des paramètres (un seul argument)


 dotests(){
     echo "########### Testing ... ###########"
    docker exec sf4_php_apache ./vendor/bin/simple-phpunit
 }
  updateschema(){
      echo "###########Update schema ... ###########"
      docker exec sf4_php_apache bin/console doctrine:schema:update -f
 }

  loaddata(){
      echo "########### loaddata data ... ###########"
      docker exec sf4_php_apache bin/console doctrine:fixtures:load
      reindex
 }

listCommandes(){
         echo " "
         echo " "
         echo "############  Lancer les tests unitaires avec : dotests :  ########"
         echo "############  Mise à jour du schema de bdd : updateschema"
         echo "############  Charger les données bdd avec : loaddata ########"
         echo "############  Reindex avec  : reindex ########"
         echo " "
         echo " "
}


  reindex(){
       echo "########### Reindexing data###########"
      docker exec sf4_php_apache bin/console elastic:reindex
 }

for param in "$@"
do
case $param
in
dotests)
    $param;;

loaddata)
    $param;;

updateschema)
    $param;;

reindex)
    $param;;

listCommandes)
    $param;;

*)
echo "Désolé, cette commande n'existe pas, tapez en arguments '--help' ou 'listCommandes' pour voir les différentes options " ;;
esac
done