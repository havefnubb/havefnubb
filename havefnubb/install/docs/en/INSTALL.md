Installing Havefnubb

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
    temp/
    
All others dir are use for web content such as "images", "themes" etc..
 
 
### Configuration of the web environment
 
- If you can specify the document root, upload the content of the 
  archive and point the root of your application to havefnubb/www/.
  More infos on http://jelix.org/articles/en/manual-1.1/application-configuration

- If you can't specify the document root :
  have a look at the chapter "If you can't change the document root" on 
  http://jelix.org/articles/en/manual-1.1/application-configuration

- rename the file havefnubb/var/config/defaultconfig.ini.php.dist in havefnubb/var/config/defaultconfig.ini.php
- rename the file havefnubb/var/config/profiles.ini.php.dist in havefnubb/var/config/profiles.ini.php

### Rights Accesss 

Don't forget to change/check your rights access on all your folder to 755, and your files in 644,
except for the 4 files located in var/config which have to be in 664 : defaultconfig.ini.php, profiles.ini.php, flood.coord.ini.php, activeusers.coord.ini.php

do the following to change them :

    find . -type d -exec chmod 755 {} \;
    find . -type f -exec chmod 644 {} \;    
    chmod 664 var/config/defaultconfig.ini.php
    chmod 664 var/config/profiles.ini.php
    chmod 664 var/config/havefnubb/flood.coord.ini.php
    chmod 664 var/config/havefnubb/activeusers.coord.ini.php
    chmod 770 cache/images

additionnaly, you could have to change the rights on the temp directory,
effectively it have to be writable to generate the cache

### URLs settings with HaveFnuBB : 

the default behavior of havefnubb is to use the URL engine significant with the 
param multiview to on et enableParser to on.

if you wish to use the URL engine basic_significant, then :

- with multiview set to on the URLs will take the form http://localhost/forums.php/ but this one http://localhost/forums.php will lead to a 404 eror page
- with multiview set to off the URLs of the form http://localhost/forums.php/ or http://localhost/forums.php will work without any 404 error

### Running the installation :

2 choices :


* access to http://mydomain/install.php
* or from the command line, into the folder of havefnubb, enter 
  the command :

    php lib/jelix-script/jelix.php --havefnubb installapp


### Running the update

* access to http://mydomain/update.php
* the command line :

    php lib/jelix-script/jelix.php --havefnubb installapp

yes it's the same for updating or installing ;) the core does everything alone ;)
