Installing Havefnubb

### Prerequisites :

- Apache RewriteEngine has to be set On
- PHP 5.6 minimum

### Introduction :

When the archive in uncompressed you have the following directories :

```
    havefnubb-X.Y/          web content + configuration
        havefnubb/
            install/           scripts for installation
            var/config/         
            modules-hook/      hook modules of HaveFnuBB 
            plugins/           plugins of HaveFnuBB
            lib/               Jelix librairies 
            temp/              directory where Jelix stores some cache
        cache/                 cache of application data
```   

 
Others directories into havefnubb-X.Y/ are used for web content such as "hfnu", "themes" etc..
 
 
### Configuration of the web environment
 
- Specify the document root of the web server to the root directory of Havefnubb
- rename the file havefnubb/var/config/localconfig.ini.php.dist to havefnubb/var/config/localconfig.ini.php
- rename the file havefnubb/var/config/profiles.ini.php.dist in havefnubb/var/config/profiles.ini.php
- More infos on https://docs.jelix.org/en/manual/installation/deployment


### Rights Accesss 

Don't forget to change/check your rights access on all your folder to 755, and your files in 644,
except for the 4 files located in var/config which have to be in 664 : localconfig.ini.php, profiles.ini.php, flood.coord.ini.php, activeusers.coord.ini.php

do the following to change them :

```
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;    
chmod 664 var/config/localconfig.ini.php
chmod 664 var/config/profiles.ini.php
chmod 664 var/config/havefnubb/flood.coord.ini.php
chmod 664 var/config/havefnubb/activeusers.coord.ini.php
chmod 770 cache/images
```

additionnaly, you could have to change the rights on the temp directory,
effectively it have to be writable to generate the cache

### Running the installation :

2 choices :


* access to http://mydomain/install.php
* or from the command line, into the folder of havefnubb, enter 
  the command :

```
php havefnubb/install/installer.php
```

### Running the update

* access to http://mydomain/update.php
* the command line :

```
php havefnubb/install/installer.php
```

