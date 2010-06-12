<?xml version="1.0" encoding="UTF-8"?>
<project xmlns="http://jelix.org/ns/project/1.0">
    <info id="%%default_id%%" name="%%appname%%" createdate="%%createdate%%">
        <version stability="pre-alpha" date="">0.1pre</version>
        <label lang="%%default_locale%%">%%appname%%</label>
        <description lang="%%default_locale%%"></description>
        <license URL="%%default_license_url%%">%%default_license%%</license>
        <copyright>%%default_copyright%%</copyright>
        <creator name="%%default_creator_name%%" email="%%default_creator_email%%" active="true" />
        <!--<contributor name="hisname" email="hisemail@yoursite.undefined" active="true" since="" role=""/>-->
        <homepageURL>%%default_website%%</homepageURL>
        <!--<updateURL>%%default_website%%/update.xml</updateURL>-->
    </info>
    <dependencies>
        <jelix minversion="%%jelix_version%%" maxversion="%%jelix_version_next%%" />
    </dependencies>
    <directories>
        <config>%%rp_conf%%</config>
        <log>%%rp_log%%</log>
        <var>%%rp_var%%</var>
        <www>%%rp_www%%</www>
        <temp>%%rp_temp%%</temp>
    </directories>
    <entrypoints>
        <!-- file: the path to the entry point relative to the base path
            config: the path to the config file used by the entry point, relative
                    to var/config/
            type: type of the entry point : classic, cmdline, xmlrpc....-->
        <entry file="index.php" config="index/config.ini.php" />
    </entrypoints>
</project>
