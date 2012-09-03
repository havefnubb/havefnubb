<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   core
* @author       Christophe Thiriot
* @contributor  Laurent Jouanneau
* @copyright    2008 Christophe Thiriot, 2011-2012 Laurent Jouanneau
* @link         http://www.jelix.org
* @licence      GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jCmdlineCoordinator extends jCoordinator{
	function __construct($configFile,$enableErrorHandler=true){
		if(!jServer::isCLI()){
			throw new Exception("Error: you're not allowed to execute this script outside a command line shell.");
		}
		jApp::setEnv('cli');
		parent::__construct($configFile,$enableErrorHandler);
	}
	public function process($request){
		$this->allErrorMessages=jBasicErrorHandler::$initErrorMessages;
		parent::process($request);
		exit($this->response->getExitCode());
	}
	public $allErrorMessages=array();
	public function handleError($type,$code,$message,$file,$line,$trace){
		$errorLog=new jLogErrorMessage($type,$code,$message,$file,$line,$trace);
		$errorLog->setFormat(jApp::config()->error_handling['messageLogFormat']);
		jLog::log($errorLog,$type);
		$this->allErrorMessages[]=$errorLog;
		if($type!='error')
			return;
		$this->errorMessage=$errorLog;
		while(ob_get_level()&&@ob_end_clean());
		if($this->response){
			$resp=$this->response;
		}
		else{
			require_once(JELIX_LIB_CORE_PATH.'response/jResponseCmdline.class.php');
			$resp=$this->response=new jResponseCmdline();
		}
		$resp->outputErrors();
		jSession::end();
		exit(1);
	}
}
