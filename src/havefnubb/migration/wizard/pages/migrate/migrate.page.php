<?php
/**
 * @package     InstallWizard
 * @subpackage  pages
 * @author      Olivier Demah
 * @copyright   2010 Olivier Demah
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that handles the pages for Installation wizard
 */
class migrateModuleInfo {
    public $path;
    public $name;
    public $access;
}
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
        // upgrade all config files
        copy(__DIR__.'/../../../plugins/coord/activeusers/activeusers.coord.ini.php.dist',
                        jApp::configPath().'activeusers.coord.ini.php');
        $this->_updateConfig();
        // migate existing app to jelix 1.2
        $this->_migrateApp();
        // upgade the database
        $this->_updateDatabase();
        return 0;
    }
    protected function loadconf() {
        $ini = new jIniFileModifier(jApp::configPath().'localconfig.ini.php');
        $config = array(
            'theme'=>$ini->getValue('theme'),
            'title'=>$ini->getValue('title','havefnubb'),
            'description'=>$ini->getValue('description','havefnubb'),
            'errors'=>array()
        );
        return $config;
    }
    /**
     * private function to handle the configuration migration
     */
    private function _updateConfig() {
        /**
         *
         * DataBase CONFIG FILE : profiles.ini.php
         *
         */
        $dBini = new jIniFileModifier(jApp::configPath().'profiles.ini.php');
        $dBini->setValue('jacl2_profile','havefnubb');
        $dBini->save();
        /**
         *
         * Main CONFIG FILE : defaultconfig.ini.php
         *
         */
        $iniDef = new jIniFileModifier(jApp::configPath().'localconfig.ini.php');

        $this->defaultModulesPath = $iniDef->getValue('modulesPath');
        //need to add app:admin-modules to the modulesPath
        if (strpos($this->defaultModulesPath,'app:admin-modules/') === false) {
            $this->defaultModulesPath .= ',app:admin-modules/';
        }
        // need to add jelix-admin-modules to user jauthdb_admin
        if (strpos($this->defaultModulesPath,'lib:jelix-admin-modules/') === false) {
            $this->defaultModulesPath = 'lib:jelix-admin-modules/,'. $this->defaultModulesPath;
        }
        // let's migrate config, section by section
        $this->defaultCheckTrustedModules = $iniDef->getValue('checkTrustedModules');
        if ($this->defaultCheckTrustedModules === null)
            $this->defaultCheckTrustedModules = false;

        $this->defaultTrustedModules = $iniDef->getValue('trustedModules');
        if ($this->defaultTrustedModules === null)
            $this->defaultTrustedModules = '';

        $allModulePath = $this->getModulesPath($this->defaultModulesPath, ($this->defaultCheckTrustedModules?1:2));

        if ($this->defaultCheckTrustedModules) {
            $list = preg_split('/ *, */', $this->defaultTrustedModules);
            foreach ($list as $module) {
                if (isset($allModulePath[$module]))
                    $allModulePath[$module]->access = 2;
            }
        }

        $this->defaultUnusedModules = $iniDef->getValue('unusedModules');
        if ($this->defaultUnusedModules) {
            $list = preg_split('/ *, */', $this->defaultUnusedModules);
            foreach ($list as $module) {
                if (isset($allModulePath[$module]))
                    $allModulePath[$module]->access = 0;
            }
        }

        foreach ($allModulePath as $name=>$module) {
            $iniDef->setValue($name.'.access', $module->access, 'modules');
        }

        $iniDef->removeValue('checkTrustedModules');
        $iniDef->removeValue('trustedModules');
        $iniDef->removeValue('unusedModules');
        $iniDef->removeValue('hiddenModules');

        //modulesPath
        $iniDef->setValue('modulesPath',$this->defaultModulesPath);
        //section [coordplugins]
        $iniDef->removeValue('hfnuinstalled','coordplugins');
        //[basic_significant_urlengine_entrypoints]
        $iniDef->setValue('forums','on','basic_significant_urlengine_entrypoints');
        $iniDef->setValue('install','on','basic_significant_urlengine_entrypoints');
        //section [simple_urlengine_entrypoints]
        $iniDef->setValue('forums','@classic','simple_urlengine_entrypoints');
        //section [datepickers]
        $iniDef->setValue('default','jelix/js/jforms/datepickers/default/init.js','datepickers');
        //section [wikieditors]
        $iniDef->setValue('default.engine.name','wr3','wikieditors');
        $iniDef->setValue('default.wiki.rules','wr3_to_xhtml','wikieditors');
        $iniDef->setValue('default.engine.file','jelix/markitup/jquery.markitup.js','wikieditors');
        $iniDef->setValue('default.image.path','jelix/markitup/sets/wr3/images/','wikieditors');
        $iniDef->setValue('default.skin','jelix/markitup/skins/simple/style.css','wikieditors');
        //section [havefnubb]
        $iniDef->setValue('version','1.4.0','havefnubb');
        $iniDef->save();
        /**
         *
         * Flood CONFIG FILE : havefnubb/flood.coord.ini.php
         *
         */
        //floodcoord.ini.php of the forums entrypoint
        $floodCoordIni = new jIniFileModifier(jApp::configPath().'havefnubb/flood.coord.ini.php');
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
         * ACL CONFIG FILE : havefnubb/jacl2.coord.ini.php
         *
         */
        $jacl2Config = new jIniFileModifier(jApp::configPath().'havefnubb/jacl2.coord.ini.php');
        $jacl2Config->setValue('on_error_action','havefnubb~hfnuerror:badright');
        $jacl2Config->save();
        /**
         *
         * AUTH CONFIG FILE : hfnuadmin/auth.coord.ini.php
         *
         */
        $adminAuthConfig = new jIniFileModifier(jApp::configPath().'hfnuadmin/auth.coord.ini.php');
        $adminAuthConfig->setValue('timeout',0);
        $adminAuthConfig->setValue('dao','jcommunity~user','Db');
        $adminAuthConfig->save();
        /**
         *
         * CONFIG FILE of each entry point : havefnubb/config.ini.php  +
         *                                   hfnuadmin/config.ini.php
         *
         */
        $entryPointConfigFiles = array(jApp::configPath().'havefnubb/config.ini.php',
                                      jApp::configPath().'hfnuadmin/config.ini.php');
        $help = "<p>In each config files of your entry points, fill this parameters:<br/>".
               "<ul><li> checkTrustedModules=on</li>".
               "<li>trustedModules: list of modules accessible from the web</li>".
               "<li> unusedModules: those you don't use at all</li>".
               "<li>For other modules you use but which should not be accessible from the web, nothing to do.</li></ul>";

        $otherModulePath=array();

        foreach ($entryPointConfigFiles as $currentIni) {
            $iniConfig = new jIniFileModifier($currentIni);
            //common tasks

            $modulesPath = $iniConfig->getValue('modulesPath');
            if (!$modulesPath) {
                $modulesPath = $this->defaultModulesPath;
            }
            //need to add app:admin-modules to the modulesPath of the admin entrypoint
            if (strpos($modulesPath,'app:admin-modules/') === false)
                $modulesPath .= ',app:admin-modules/';
            if (strpos($modulesPath,'lib:jelix-admin-modules/') === false)
                $modulesPath = 'lib:jelix-admin-modules/,'. $modulesPath;

            $checkTrustedModules = $iniConfig->getValue('checkTrustedModules');
            if ($checkTrustedModules === null)
                $checkTrustedModules = $this->defaultCheckTrustedModules;

            if (!$checkTrustedModules) {
                throw new Exception("checkTrustedModules should be set to 'on' in config files.\n$help");
                return "checkTrustedModules should be set to 'on' in config files.\n$help";
            }

            $trustedModules = $iniConfig->getValue('trustedModules');
            if (!$trustedModules)
                $trustedModules = $this->defaultTrustedModules;

            if ($trustedModules == '') {
                throw new Exception("trustedModules should be filled in config files.\n$help");
                return "trustedModules should be filled in config files.\n$help";
            }
            // add the new admin module to the $trustedModules
            if ($currentIni == jApp::configPath().'hfnuadmin/config.ini.php')  {
                $trustedModules .= 'activeusers_admin, hfnuadmin, jelixcache, modulesinfo, servinfo';
            }

            $unusedModules = $iniConfig->getValue('unusedModules');
            if (!$unusedModules)
                $unusedModules = $this->defaultUnusedModules;

            $epModulePath = $this->getModulesPath($modulesPath, 1);

            $list = preg_split('/ *, */', $trustedModules);
            foreach ($list as $module) {
                if (isset($epModulePath[$module]))
                    $epModulePath[$module]->access = 2;
            }

            if ($unusedModules) {
                $list = preg_split('/ *, */', $unusedModules);
                foreach ($list as $module) {
                    if (isset($epModulePath[$module]))
                        $epModulePath[$module]->access = 0;
                }
            }

            foreach ($epModulePath as $name=>$module) {
                if (!isset($allModulePath[$name]) || $allModulePath[$name]->access != $module->access) {
                    $iniConfig->setValue($name.'.access', $module->access, 'modules');
                }
                if (!isset($allModulePath[$name]) && !isset($otherModulePath[$name]))
                    $otherModulePath[$name] = $module;
            }

            $iniConfig->removeValue('checkTrustedModules');
            $iniConfig->removeValue('trustedModules');
            $iniConfig->removeValue('unusedModules');
            $iniConfig->removeValue('hiddenModules');
            // end common tasks
            //specific tasks
            if ($currentIni == jApp::configPath().'havefnubb/config.ini.php') {
                //[coordplugins]
                //drop deprecated parms
                $iniConfig->removeValue('hfnuinstalled','coordplugins');
                $iniConfig->removeValue('timeout','coordplugins');
                //add new parms
                $iniConfig->setValue('activeusers','activeusers.coord.ini.php','coordplugins');
                //[urlengine]
                //remove this unuseful section
                $iniConfig->removeValue('engine','urlengine');
                $iniConfig->removeValue('enableParser','urlengine');
                $iniConfig->removeValue('multiview','urlengine');
                $iniConfig->removeValue('defaultEntrypoint','urlengine');
                $iniConfig->removeValue('entrypointExtension','urlengine');
                $iniConfig->removeValue('notfoundAct','urlengine');
            } elseif ($currentIni == jApp::configPath().'hfnuadmin/config.ini.php') {
                $iniConfig->removeValue('engine','urlengine');
                //add a new section [activeusers_admin]
                $iniConfig->setValue('pluginconf','activeusers.coord.ini.php','activeusers_admin');
            }
            $iniConfig->save();
        }
        /**
         *
         * DROP DEPRECATED FILE
         *
         */
        @unlink(jApp::configPath().'havefnubb/timeout.coord.ini.php');
        @unlink(jApp::configPath().'havefnubb/hfnuinstalled.coord.ini.php');
        //this file has been integrated inside the defaultconfig.ini.php
        //in the section [wikieditor]
        //so drop it
        @unlink(jApp::configPath().'wikitoolbar.ini.php');

    }
    private function _migrateApp(){
        $errors = array();
        if (!file_exists(jApp::appPath().'install/installer.php')) {
            $this->createDir(jApp::appPath().'install/');
            $this->createFile(jApp::appPath().'install/installer.php','installer/installer.php.tpl',array());
        }
        return $errors;
    }
    /**
     * retrieve all modules specifications
     */
    private function getModulesPath($modulesPath, $defaultAccess) {

        $list = preg_split('/ *, */', $modulesPath);
        array_unshift($list, JELIX_LIB_PATH.'core-modules/');
        $modulesPathList = array();

        foreach ($list as $k=>$path) {
            if (trim($path) == '') continue;
            $p = str_replace(array('lib:','app:'), array(LIB_PATH, jApp::appPath()), $path);
            if (!file_exists($p)) {
                throw new Exception('The path, '.$path.' given in the jelix config, doesn\'t exists !',E_USER_ERROR);
            }

            if (substr($p,-1) !='/')
                $p.='/';

            $this->moduleRepositories[$p] = array();

            if ($handle = opendir($p)) {
                while (false !== ($f = readdir($handle))) {
                    if ($f[0] != '.' && is_dir($p.$f)) {
                        $m = new migrateModuleInfo();
                        $m->path = $p.$f.'/';
                        $m->name = $f;
                        $m->access = ($f == 'jelix'?2:$defaultAccess);
                        $m->repository = $p;
                        $modulesPathList[$f] = $m;
                        $this->moduleRepositories[$p][$f] = $m;
                    }
                }
                closedir($handle);
            }
        }
        return $modulesPathList;
    }
    /**
     * private function to handle the database migration
     */
    private function _updateDatabase() {
        //1) if the file installer.ini.php does not exist we cant install jelix nor havefnubb;
        // then the application has not been installed with jelix 1.2
        // so we copy the installer.ini.php build for the application havefnubb
        //2) if the file exists, that means jelix 1.2 is "installed"
        // so no need to try to install jelix
        if (!file_exists(jApp::configPath().'installer.ini.php')) {
            copy(__DIR__.'/../../../install/installer.ini.php',jApp::configPath().'installer.ini.php');
        }
        
        jApp::loadConfig('havefnubb/config.ini.php');
        //get the profiles file
        $dbProfile = jIniFile::read(jApp::configPath('profiles.ini.php'));
        //get the default profile
        $tools = jDb::getConnection($dbProfile['default'])->tools();
        // migrate from 1.3.6 to 1.4.0
        $tools->execSQLScript(__DIR__.'/../../../sql/update_to_1.4.0.mysql.sql');
    }
}
