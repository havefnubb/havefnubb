<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

/**
* page for Installation wizard
*
* @package     InstallWizard
* @subpackage  pages
* @author      Laurent Jouanneau
* @copyright   2010 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/
$lib_jelix=dirname(__FILE__).'/../../../jelix/';
include $lib_jelix.'/installer/jIInstallReporter.iface.php';
include $lib_jelix.'/installer/jInstallerMessageProvider.class.php';
include $lib_jelix.'/installer/jInstallChecker.class.php';
class checkjelixWizPage extends installWizardPage  implements jIInstallReporter{
	protected $tpl;
	protected $messages;
	function show($tpl){
		$this->tpl=$tpl;
		$check=new jInstallCheck($this);
		$check->run();
		return($check->nbError==0);
	}
	function start(){}
	function message($message,$type=''){
		$this->messages[]=array($type,$message);
	}
	function end($results){
		$this->tpl->assign('messages',$this->messages);
		$this->tpl->assign('nbError',$results['error']);
		$this->tpl->assign('nbWarning',$results['warning']);
		$this->tpl->assign('nbNotice',$results['notice']);
	}
}
