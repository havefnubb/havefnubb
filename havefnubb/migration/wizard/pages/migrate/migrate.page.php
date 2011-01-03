<?php
/**
 * @package     InstallWizard
 * @subpackage  pages
 * @author      Olivier Demah
 * @copyright   2010 Olivier Demah
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that handles the pages for Installation wizard
 */
class migrateWizPage extends installWizardPage {

    /**
     * action to display the page
     * @param jTpl $tpl the template container
     */
    function show ($tpl) {
        return true;
    }

    /**
     * action to process the page after the submit
     */
    function process() {
        /**
         * this part handle all the config files
         */
        $errors = array();
        // upgrade all config files
        $this->_updateConfig();
        // upgade the database
        $this->_updateDatabase();

        return 0;
    }


    protected function loadconf() {
        $ini = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
        $config = array(
            'theme'=>$ini->getValue('theme'),
            'title'=>$ini->getValue('title','havefnubb'),
            'description'=>$ini->getValue('description','havefnubb'),
            'errors'=>array()
        );
        return $config;
    }

    private function _updateConfig() {
        /**
         *
         * CONFIG FILE : dbprofils.ini.php
         *
         */
        $dBini = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'dbprofils.ini.php');
        $dBini->setValue('jacl2_profile','havefnubb');
        $dBini->save();
        /**
         *
         * CONFIG FILE : defaultconfig.ini.php
         *
         */
        $iniDef = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
        // let's migrate config, section by section
        //main section
        //drop deprecated parms
        $iniDef->removeValue('checkTrustedModules');
        $iniDef->removeValue('trustedModules');
        $iniDef->removeValue('hfnuinstalled','coordplugins');
        //[basic_significant_urlengine_entrypoints]
        $iniDef->setValue('forums','on','basic_significant_urlengine_entrypoints');
        $iniDef->setValue('install','on','basic_significant_urlengine_entrypoints');
        //[simple_urlengine_entrypoints]
        $iniDef->setValue('forums','@classic','simple_urlengine_entrypoints');
        //[datepickers]
        $iniDef->setValue('chocolatebrown','jelix/js/jforms/datepickers/chocolatebrown/init.js','datepickers');
        $iniDef->setValue('dust','jelix/js/jforms/datepickers/dust/init.js','datepickers');
        $iniDef->setValue('emplode','jelix/js/jforms/datepickers/emplode/init.js','datepickers');
        $iniDef->setValue('emplode','jelix/js/jforms/datepickers/default/init.js','datepickers');
        //[wikieditors]
        $iniDef->setValue('default.engine.name','wr3','wikieditors');
        $iniDef->setValue('default.wiki.rules','wr3_to_xhtml','wikieditors');
        $iniDef->setValue('default.engine.file','jelix/markitup/jquery.markitup.js','wikieditors');
        $iniDef->setValue('default.image.path','jelix/markitup/sets/wr3/images/','wikieditors');
        $iniDef->setValue('default.skin','jelix/markitup/skins/simple/style.css','wikieditors');
        $iniDef->save();
        /**
         *
         * CONFIG FILE : havefnubb/floodcoord.ini.php
         *
         */
        //floodcoord.ini.php of the forums entrypoint
        $floodCoordIni = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnubb/floodcoord.ini.php');
        //drop deprecated parms
        $floodCoordIni->removeValue('elapsed_time_between_two_post_by_same_ip');
        $floodCoordIni->removeValue('elapsed_time_after_posting_before_editing');
        //add new parms
        $floodCoordIni->setValue('only_same_ip',0);
        $floodCoordIni->setValue('time_interval',30);
        $floodCoordIni->setValue('elapsed_time_between_two_post',0);
        $floodCoordIni->save();
        /**
         *
         * CONFIG FILE : havefnubb/config.ini.php
         *
         */
        $iniConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnubb/config.ini.php');
        //[coordplugins]
        //drop deprecated parms
        $iniConfig->removeValue('hfnuinstalled','coordplugins');
        $iniConfig->removeValue('timeout','coordplugins');
        //add new parms
        $iniConfig->setValue('activeusers','coordplugins');
        //[urlengine]
        //remove this unuseful section
        $iniConfig->removeValue('engine','urlengine');
        $iniConfig->removeValue('enableParser','urlengine');
        $iniConfig->removeValue('multiview','urlengine');
        $iniConfig->removeValue('defaultEntrypoint','urlengine');
        $iniConfig->removeValue('entrypointExtension','urlengine');
        $iniConfig->removeValue('notfoundAct','urlengine');
        $iniConfig->save();
        /**
         *
         * CONFIG FILE : havefnubb/jack2.coord.ini.php
         *
         */
        $jacl2Config = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnubb/jacl2.coord.ini.php');
        $jacl2Config->setValue('on_error_action','havefnubb~hfnuerror:badright');
        $jacl2Config->save();
        /**
         *
         * CONFIG FILE : hfnuadmin/config.ini.php
         *
         */
        $adminConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'hfnuadmin/config.ini.php');
        $adminConfig->removeValue('checkTrustedModules');
        $adminConfig->removeValue('trustedModules');
        //[urlengine]
        //remove this unuseful section
        $adminConfig->removeValue('engine','urlengine');
        $adminConfig->setValue('pluginconf','havefnubb/activeusers.coord.ini.php','activeusers_admin');
        $adminConfig->save();
        /**
         *
         * CONFIG FILE : hfnuadmin/config.ini.php
         *
         */
        $adminAuthConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'hfnuadmin/auth.coord.ini.php');
        $adminAuthConfig->setValue('timeout',0);
        $adminAuthConfig->save();
        //drop 2 deprecated files
        @unlink(JELIX_APP_CONFIG_PATH.'havefnubb/timeout.ini.php');
        @unlink(JELIX_APP_CONFIG_PATH.'havefnubb/hfnuinstalled.ini.php');
        //this file has been integrated inside the defaultconfig.ini.php
        //in the section [wikieditor]
        //so drop it
        @unlink(JELIX_APP_CONFIG_PATH.'wikitoolbar.ini.php');

    }
    private function _updateDatabase() {
        $db = jDb::getConnection();
        $db->execSQLScript('sql/update_to_1.4.0');
    }
}
