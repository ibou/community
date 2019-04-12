#!/bin/bash

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



if [ $# -eq 0 ];
then
listCommandes
exit 0
elif [ $1 = "-h" ]
then
listCommandes
exit 0
else
echo "Vous avez passé $# paramètres"
echo $1
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

listCommandes)
    $param;;
reindex)
￼	    $param;;
*)
echo "Désolé, cette commande n'existe pas, tapez en arguments 'listCommandes' pour voir les différentes options " ;;
esac
done