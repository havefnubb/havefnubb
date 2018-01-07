### Prérequis recommandés

- Option Apache RewriteEngine doit être activé
- PHP 5.2.x minimum
- L'option PHP MagicQuotes doit être désactivé

### Introduction

Pour mettre à jour ; parcourez les fichiers UPDATE-TO-x
Pour Installer ; lisez les étapes ci-dessous

Quand l'archive est décompressée vous obtenez les répertoires suivants :

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


### Configuration de l'environnement web :

- Si vous pouvez spécifier le document root, transférer le contenu de l'archive et faites pointer le répertoire havefnubb/www sur la racine de votre site.
  plus d'infos ici : http://jelix.org/articles/fr/manuel-1.1/configurer-application

- Si vous ne pouvez pas spécifier le document root :
  rendez vous sur la page http://jelix.org/articles/fr/manuel-1.1/configurer-application 
  sur la paragraphe "Si vous ne pouvez pas spécifier le document root", qui vous indiquera la marche à suivre pour que l'application puisse fonctionner.

- renommer le fichier havefnubb/var/config/localconfig.ini.php.dist en havefnubb/var/config/localconfig.ini.php
- renommer le fichier havefnubb/var/config/profiles.ini.php.dist en havefnubb/var/config/profiles.ini.php

### Droits d'accès

N'oubliez pas de passer vos dossiers en 755 et vos fichiers en 644,
à l'exception des 4 fichiers de configurations situés dans var/config avec le mode suivant 664 : localconfig.ini.php, profiles.ini.php, flood.coord.ini.php, activeusers.coord.ini.php.

executer les commandes suivantes pour appliquer les bons droits  :

    find . -type d -exec chmod 755 {} \;
    find . -type f -exec chmod 644 {} \;
    chmod 664 var/config/localconfig.ini.php
    chmod 664 var/config/profiles.ini.php
    chmod 664 var/config/havefnubb/flood.coord.ini.php
    chmod 664 var/config/havefnubb/activeusers.coord.ini.php
    chmod 770 cache/images

Vous pouvez rencontrer des problemes de droits sur le repertoire temp
effectivement il faut autoriser l'écriture à l'interieure de temp/havefnubb afin 
qu'il y génère son cache

### configuration des URLs par défaut avec HaveFnuBB : 

Le comportement par défaut de havefnubb est d'utiliser le moteur d'url significant avec le multiview à on et enableParser à on.

Si vous souhaitez utiliser le moteur d'URL basic_significant alors :
avec multiview à on les URLs seront de la forme http://localhost/forums.php/ mais http://localhost/forums.php aboutira à une erreur 404
avec multiview à off les URLs de la forme http://localhost/forums.php/ ou http://localhost/forums.php fonctionneront sans erreur 404.


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
