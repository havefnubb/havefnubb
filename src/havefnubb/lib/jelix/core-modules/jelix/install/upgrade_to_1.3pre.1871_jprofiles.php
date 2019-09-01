<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix-modules
 * @subpackage jelix-module
* @author     Laurent Jouanneau
* @copyright  2011 Laurent Jouanneau
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jelixModuleUpgrader_jprofiles extends jInstallerModule{
	function install(){
		$profilesfile=jApp::configPath('profiles.ini.php');
		if(!file_exists($profilesfile)){
			file_put_contents($profilesfile,";<?php die(''); ?>
;for security reasons, don't remove or modify the first line");
		}
		$profiles=new jIniFileModifier($profilesfile);
		$profilesdistfile=jApp::configPath('profiles.ini.php.dist');
		$distcreated=false;
		if(!file_exists($profilesdistfile)){
			file_put_contents($profilesdistfile,";<?php die(''); ?>
;for security reasons, don't remove or modify the first line");
			$distcreated=true;
		}
		$profilesdist=new jIniFileModifier($profilesdistfile);
		$dbprofile=$this->config->getValue('dbProfils');
		if(!$dbprofile)
			$dbprofile='dbprofils.ini.php';
		$dbprofilefile=jApp::configPath($dbprofile);
		if(file_exists($dbprofilefile)){
			$dbProfiles=new jIniFileModifier($dbprofilefile);
			$profiles->import($dbProfiles,'jdb',':');
			unlink($dbprofilefile);
		}
		$dbprofilefile=jApp::configPath('dbprofils.ini.php.dist');
		if(file_exists($dbprofilefile)){
			$dbProfiles=new jIniFileModifier($dbprofilefile);
			$profilesdist->import($dbProfiles,'jdb',':');
			unlink($dbprofilefile);
		}
		$kvprofilefile=jApp::configPath('kvprofiles.ini.php');
		if(file_exists($kvprofilefile)){
			$dbProfiles=new jIniFileModifier($kvprofilefile);
			$profiles->import($dbProfiles,'jkvdb',':');
			unlink($kvprofilefile);
		}
		$kvprofilefile=jApp::configPath('kvprofiles.ini.php.dist');
		if(file_exists($kvprofilefile)){
			$dbProfiles=new jIniFileModifier($kvprofilefile);
			$profilesdist->import($dbProfiles,'jkvdb',':');
			unlink($kvprofilefile);
		}
		$soapprofilefile=jApp::configPath('soapprofiles.ini.php');
		if(file_exists($soapprofilefile)){
			$soapProfiles=new jIniFileModifier($soapprofilefile);
			$profiles->import($soapProfiles,'jsoapclient',':');
			unlink($soapprofilefile);
		}
		$soapprofilefile=jApp::configPath('soapprofiles.ini.php.dist');
		if(file_exists($soapprofilefile)){
			$soapProfiles=new jIniFileModifier($soapprofilefile);
			$profilesdist->import($soapProfiles,'jsoapclient',':');
			unlink($soapprofilefile);
		}
		$cacheprofilefile=jApp::configPath('cache.ini.php');
		if(file_exists($cacheprofilefile)){
			$cacheProfiles=new jIniFileModifier($cacheprofilefile);
			$profiles->import($cacheProfiles,'jcache',':');
			unlink($cacheprofilefile);
		}
		$cacheprofilefile=jApp::configPath('cache.ini.php.dist');
		if(file_exists($cacheprofilefile)){
			$cacheProfiles=new jIniFileModifier($cacheprofilefile);
			$profilesdist->import($cacheProfiles,'jcache',':');
			unlink($cacheprofilefile);
		}
		$profiles->save();
		if($profilesdist->isModified()){
			$profilesdist->save();
		}
		else if($distcreated){
			unlink($profilesdistfile);
		}
		$this->config->getMaster()->removeValue('dbProfils');
		$this->config->getOverrider()->removeValue('dbProfils');
		$this->config->getMaster()->removeValue('cacheProfiles');
		$this->config->getOverrider()->removeValue('cacheProfiles');
	}
}
