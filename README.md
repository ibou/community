# Community

## Guide d'installation :sheep:

### Récupérer le dépôt Git
```bash
$ git clone git@github.com:ibou/community.git

```

### Se déplacer dans le projet
```bash
$ cd community
```

### Démarrer les containers 
```bash
$ ./start.sh
```

### Stopper les containers 
```bash
$ ./stop.sh
```

### Ajouter le host :
```bash
sudo nano /etc/hosts
```

```
$ composer install
$ npm install
$ yarn encore dev ou ./node_modules/.bin/encore dev

```

ajouter la ligne
>127.0.0.1    sf-commulity-localhost
 
>Aller à [http://sf-commulity-localhost](http://sf-commulity-localhost)

### Création de la base de données community 

Créer une nouvelle base de données "community" dans http://localhost:8000


```
$ bin/run-short-command.sh [commande_name] 

```

### Pour les test unitaire
Créer une nouvelle base de données "test_community" dans http://localhost:8000

importer la base de test à partir du fichier sql : data/sql/test_community.sql

```
$ bin/run-short-command.sh dotests 

```


### URLs projet :
- Maildev : http://0.0.0.0:1080
- PhpMyAdmin : http://0.0.0.0:8000
- Projet : http://sf-commulity-localhost

