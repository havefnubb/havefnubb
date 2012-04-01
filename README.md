# What is HaveFnuBB ?

HaveFnuBB is an OpenSource bulletin board software (under GPL 2.0 license) with the goals of being Fast, Light and Fun !

Its strength resides in the modules that you can add to extend its functionality.

The Theme system of HaveFnuBB that is based on the famous grid system 960gs should appeal to designers

# Technicals details 

HaveFnuBB is based on [Jelix PHP5 Framework](http://www.jelix.org) and can use MySQL, SQLIte, PostgreSQL.

# Installation

## French

### Prérequis recommandés : 

*  Option Apache RewriteEngine doit être activé
*  PHP 5.2.x minimum
*  L'option PHP MagicQuotes doit être désactivé

### Introduction :

Quand l'archive est décompressée dans votre "document_root" apache, vous obtenez les répertoires suivants :

    havefnubb/          répertoire contenant la partie web et configuration 
    -> responses
    -> var              (la config)
    -> modules/           les differents modules composants HaveFnuBB
    -> admin-modules/     les differents modules d'administration des composants HaveFnuBB
    -> modules-hook/      les modules de Hook utilisés par HaveFnuBB  
    -> plugins/           les differents plugins composants HaveFnuBB  
    lib/               librairies Jelix
    temp

 tous les autres répertoires fournissent du contenu web tels "images","themes" etc..


###  Configuration de l'environnement web :
- Si vous pouvez spécifier le document root, transférer le contenu de l'archive et faites pointer le répertoire havefnubb/www sur la racine de votre site.

plus d'infos ici : http://jelix.org/articles/fr/manuel-1.1/configurer-application

- Si vous ne pouvez pas spécifier le document root :
rendez vous sur la page http://jelix.org/articles/fr/manuel-1.1/configurer-application 
sur la paragraphe "Si vous ne pouvez pas spécifier le document root", qui vous indiquera la marche à suivre pour que l'application puisse fonctionner.

*  renommer le fichier havefnubb/var/config/defaultconfig.ini.php.dist en havefnubb/var/config/defaultconfig.ini.php
*  renommer le fichier havefnubb/var/config/dbprofils.ini.php.dist en havefnubb/var/config/dbprofils.ini.php

### Droits d'accès : 
n'oubliez pas de passer vos dossiers en 755 et vos fichiers en 644,
à l'exception des 4 fichiers de configurations situés dans var/config avec le mode suivant 664 : defaultconfig.ini.php, dbprofils.ini.php, flood.coord.ini.php, activeusers.coord.ini.php.

exécuter les commandes suivantes pour appliquer les bons droits  :

    find . -type d -exec chmod 755 {} \;
    find . -type f -exec chmod 644 {} \;
    chmod 664 var/config/defaultconfig.ini.php
    chmod 664 var/config/dbprofils.ini.php
    chmod 664 var/config/havefnubb/flood.coord.ini.php
    chmod 664 var/config/havefnubb/activeusers.coord.ini.php
    chmod 770 cache/images

Vous pouvez rencontrer des problèmes de droits sur le répertoire temp. Effectivement il faut autoriser l'écriture à l'intérieure de temp/havefnubb afin qu'il y génère son cache


### Exécuter l'installation :

2 choix possibles :

* accéder à l'url http://mondomaine/install.php
* la ligne de commandes :  
depuis le dossier havefnubb, taper la commande :

    php lib/jelix-script/jelix.php --havefnubb installapp

si vous avez suivi les étapes préalables aucune erreur ne se produira.

### Exécution la mise à jour 

* accéder à l'url http://mondomaine/update.php
* la ligne de commandes :  
depuis le dossier havefnubb, taper la commande php lib/jelix-script/jelix.php --havefnubb installapp
si vous avez suivi les étapes préalables aucune erreur ne se produira. Oui oui c'est bien la même commande 
que pour l'installation, simple non ? ;)

## English
see doc in havefnubb/install/docs/en/

### Prerequisites :

- Apache RewriteEngine has to be set On
- PHP 5.2.x minimum
- PHP MagicQuotes has to be set Off 

### Introduction :

When the archive in uncompressed you have the following directories :

    havefnubb/          web content + configuration
     -> responses
     -> var              (config)
     -> modules/           modules of HaveFnuBB
     -> admin-modules/      admin module of HaveFnuBB
     -> modules-hook/      hook modules of HaveFnuBB 
     -> plugins/           plugins of HaveFnuBB
    lib/               Jelix librairies 
    temp
    
all others dir are use for web content such as "images","themes" etc..
 
 
### Configuration of the web environment : 
- If you can specify the document root, upload the content of the archive and point the root of your application to havefnubb/www
more infos on http://jelix.org/articles/en/manual-1.1/application-configuration

- If you cant specify the document root :
have a look at the chapter "If you can't change the document root" on http://jelix.org/articles/en/manual-1.1/application-configuration

*  rename the file havefnubb/var/config/defaultconfig.ini.php.dist in havefnubb/var/config/defaultconfig.ini.php
*  rename the file havefnubb/var/config/dbprofils.ini.php.dist in havefnubb/var/config/dbprofils.ini.php

### Rights Accesss :
Don't forget to change/check your rights access on all your folder to 755, and your files in 644,
except for the 4 files located in var/config which have to be in 664 : defaultconfig.ini.php, dbprofils.ini.php, flood.coord.ini.php, activeusers.coord.ini.php

do the following to change them :

    find . -type d -exec chmod 755 {} \;
    find . -type f -exec chmod 644 {} \;
    chmod 664 var/config/defaultconfig.ini.php
    chmod 664 var/config/dbprofils.ini.php
    chmod 664 var/config/havefnubb/flood.coord.ini.php
    chmod 664 var/config/havefnubb/activeusers.coord.ini.php
    chmod 770 cache/images

additionnaly, you could have to change the rights on the temp directory,
effectively it have to be writable to generate the cache

### Running the installation :

2 choices :


* access to http://mydomain/install.php
* the command line :
from the folder of havefnubb, enter the command :

    php lib/jelix-script/jelix.php --havefnubb installapp


### Running the update

* access to http://mydomain/update.php
* the command line :

    php lib/jelix-script/jelix.php --havefnubb installapp

yes it's the same for updating or installing ;) the core does everything alone ;)
