
A Docker configuration is available to execute havefnubb on your computer to develop and test it.

You have to install Docker on your computer.

With Docker
===================

A docker configuration is provided to launch the application into a container.

build
-----
Before launching containers, you have to run these commands:

```
./run-docker build
```


launch
-------

To launch containers, just run `./run-docker`. Then you can launch some other
commands.

The first time you run the containers, you have to initialize databases and
application configuration by executing these commands:

```
./app-ctl reset
```

If you made changes into Havefnubb like database changes, you can rerun this command.

You can execute some commands into containers, by using this script:

```
./app-ctl <command>
```

Available commands:

* `reset`: to reinitialize the application (It reinstall the configuration files,
  remove temp files, create tables in databases, and it launches the jelix installer...) 
* `composer-update` and `composer-install`: to install update PHP packages 
* `clean-temp`: to delete temp files 
* `install`: to launch the Havefnubb installer, if you changed the version of a module,
   or after you reset all things by hand.

browsing the application
------------------------

You can view the application at `http://localhost:8998` in your browser. 
Or, if you set `127.0.0.1 havefnubb.hfn` into your `/etc/hosts`, you can
view at `http://havefnubb.hfn:8998`.

You can change the port by setting the environment variable `APP_WEB_PORT`
before launching `run-docker`.

```
export APP_WEB_PORT=12345
./run-docker
```

Using a specific php version
-----------------------------

By default, PHP 8.1 is installed. If you want to use another PHP version,
set the environment variable `PHP_VERSION`, and rebuild the containers:

```
export PHP_VERSION=8.2

./run-docker stop # if containers are running
./run-docker build
./run-docker
```
