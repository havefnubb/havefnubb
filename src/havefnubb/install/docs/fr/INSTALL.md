### Prérequis recommandés

- Option Apache RewriteEngine doit être activé
- PHP 5.6 minimum

### Introduction

Pour mettre à jour ; parcourez les fichiers UPDATE-TO-x
Pour Installer ; lisez les étapes ci-dessous

Quand l'archive est décompressée vous obtenez les répertoires suivants :

```
    havefnubb-X.Y/          répertoire contenant la partie web et configuration 
        havefnubb/
            install/           scripts for installation
            var/config/         
            modules-hook/      les modules de Hook utilisés par HaveFnuBB  
            plugins/           les differents plugins composants HaveFnuBB  
            lib/               librairies Jelix
            temp/              répertoire où Jelix stockes des fichiers de cache
        cache/                 cache des données applicatives
```   

Les autres répertoires dans havefnubb-X.Y/ fournissent du contenu web tels "hfnu","themes" etc..


### Configuration de l'environnement web :

- Faites pointer le "document root" du serveur web sur le repertoire racine de Havefnubb
- renommer le fichier havefnubb/var/config/localconfig.ini.php.dist en havefnubb/var/config/localconfig.ini.php
- renommer le fichier havefnubb/var/config/profiles.ini.php.dist en havefnubb/var/config/profiles.ini.php

- plus d'infos ici : https://docs.jelix.org/fr/manuel/installation/deploiement

### Droits d'accès

N'oubliez pas de passer vos dossiers en 755 et vos fichiers en 644,
à l'exception des 4 fichiers de configurations situés dans var/config avec le mode suivant 664 : localconfig.ini.php, profiles.ini.php.

Executer les commandes suivantes pour appliquer les bons droits  :

```
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;    
chmod 664 var/config/localconfig.ini.php
chmod 664 var/config/profiles.ini.php
chmod 770 cache/images
```

Vous pouvez rencontrer des problemes de droits sur le repertoire temp
effectivement il faut autoriser l'écriture à l'interieure de temp/havefnubb afin 
qu'il y génère son cache

### Exécuter l'installation :

2 choix possibles :

* accéder à l'url http://mondomaine/install.php
* la ligne de commandes :  
depuis le dossier havefnubb, taper la commande :

```
php havefnubb/install/installer.php
```

si vous avez suivi les étapes préalables aucune erreur ne se produira.

### Exécution la mise à jour 

* accéder à l'url http://mondomaine/update.php
* la ligne de commandes : depuis le dossier havefnubb, taper la commande

```
php havefnubb/install/installer.php
```
