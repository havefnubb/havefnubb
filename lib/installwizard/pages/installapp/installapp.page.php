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
require(JELIX_LIB_PATH.'installer/jIInstallReporter.iface.php');
class installappWizPage extends installWizardPage{
	function show($tpl){
		if(isset($this->config['level'])){
			$level=$this->config['level'];
			if(!in_array($level,array('error','notice','warning')))
				$level='warning';
		}
		else
			$level='warning';
		$reporter=new wizInstallReporter($level,$this);
		$installer=new jInstaller($reporter);
		$ok=$installer->installApplication();
		$tpl->assign('messages',$reporter->messages);
		$tpl->assign('installok',$ok);
		return $ok;
	}
	function process(){
		return 0;
	}
}
class wizInstallReporter implements jIInstallReporter{
	protected $level;
	public $messages=array();
	function __construct($level='notice',$page){
		$this->level=$level;
		$this->page=$page;
	}
	function start(){
		if($this->level=='notice')
			$this->messages[]=array('notice',$this->page->getLocale('install.start'));
	}
	function message($message,$type=''){
		if(($type=='error'&&$this->level!='')
			||($type=='warning'&&$this->level!='notice'&&$this->level!='')
			||(($type=='notice'||$type=='')&&$this->level=='notice'))
		$this->messages[]=array($type,$message);
	}
	function end($results){
		$this->messages[]=array('',$this->page->getLocale('install.end'));
	}
}
