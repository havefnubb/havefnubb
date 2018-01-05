
This directory contains all things needed to run Havefnubb and execute
tests in a virtual machine with Vagrant.


First installation
==================

- First install [Virtual box](https://www.virtualbox.org/) and [Vagrant](http://www.vagrantup.com/downloads.html)
- open a terminal, go into the dev/ directory 
- then fo into one of its sub-directory, depending on which PHP version you want 
  to launch tests : php7.0, php7.1, php5.6 or php5.3.

```
cd dev/php5.3/
```
 
- Then you can launch the vagrant virtual machine

```
vagrant up
```

It can take time the first time. It depends of your internet connection.

When the "Done" message appears, and if there are no errors, Havefnubb is
ready. Go on http://10.12.1.5/ to see the app.

To shutdown the virtual machine, type

```
vagrant halt
```

You can also add in your hosts file a declaration of the havefnubb.local domain

```
10.12.1.5  havefnubb.local
```

And then use http://havefnubb.local/ instead of http://10.12.1.5/

To reinstall havefnubb
======================

During development, it may appears that the app is completely broken. You can 
reinstall it without recreating the whole vm.

Follow these instructions:

```
cd dev/php5.3/
# connection into the vm
vagrant ssh
# in the vm, go into the right directory and lanch the script which reset all things
cd /vagrantscripts/
./reset_app.sh
```

Full Reinstall
--------------

You should destroy the vm. Example:

```
cd dev/php5.3
vagrant destroy
```

Then you can follow instruction to install havefnubb. See above.


