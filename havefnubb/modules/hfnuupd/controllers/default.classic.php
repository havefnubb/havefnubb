<?php
/**
* @package   havefnubb
* @subpackage hfnuupd
* @author    FoxMaSk
* @copyright 2010 FoxMaSk
* @link      http://www.havenfubb.org
* @license   http://www.gnu.org/licenses/lgpl.html  GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * This controller manages the upgrade from 1.3.6 to 1.4.0
 */
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    function index() {
        $rep = $this->getResponse('html');
        $tpl = new jTpl();

        $installerIni = parse_ini_file(JELIX_APP_CONFIG_PATH.'installer.ini.php');
        if ( $GLOBALS['gJConfig']->havefnubb['version'] == '1.3.6' and preg_match('/1\.4(.*)/',$installerIni['havefnubb.version'],$match))
            $msg = '';
        else {
            $msg = jLocale::get('upgrade.no.upgrading.required');
        }
        $tpl->assign('msg',$msg);
        $rep->body->assign('MAIN',$tpl->fetch('upgrade'));
        return $rep;
    }

    /**
    * run the upgrading itself
    */
    function run_1_4_0() {
        global $gJConfig;
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        //check if the upgrade is needed
        $installerIni = parse_ini_file(JELIX_APP_CONFIG_PATH.'installer.ini.php');
        //yes it is !
        if ( $GLOBALS['gJConfig']->havefnubb['version'] == '1.3.6' and preg_match('/1\.4(.*)/',$installerIni['havefnubb.version'],$match))  {
            // get the profile
            $dbProfile = jIniFile::read(JELIX_APP_CONFIG_PATH . $gJConfig->dbProfils);
            // get the config of the default profile
            $tools = jDb::getTools($dbProfile['default']);
            //SQL Script to load
            $file = dirname(__FILE__).'/../install/sql/update_to_1.4.0.'.$dbProfile[$dbProfile['default']]['driver'].'.sql';
            //open a connection
            $conn = jDb::getConnection($gJConfig->dbProfils);
            //running the script
            $conn->beginTransaction();
            try{
                $tools->execSQLScript($file);
                $conn->commit();
            }
            catch(Exception $e){
                $conn->rollback();
                throw $e;
            }
            //load default config file for modifying + change the version of HaveFnubb
            $ini = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
            $ini->setValue('version',$installerIni['havefnubb.version'],'havefnubb');
            $ini->save();
            //clear the cache
            jFile::removeDir(JELIX_APP_TEMP_PATH, false);
            //message of the finished upgrade
            $msg = jLocale::get('upgrade.upgrading.done');
        }
        //no need
        else {
            $msg = jLocale::get('upgrade.no.upgrading.required');
        }
        $tpl->assign('msg',$msg);
        $rep->body->assign('MAIN',$tpl->fetch('upgrade'));
        return $rep;
    }
}
