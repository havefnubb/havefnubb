<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_request
* @author      Laurent Jouanneau
* @contributor Loic Mathaud
* @contributor Thibault Piront (nuKs)
* @contributor Christophe Thiriot
* @copyright   2005-2012 Laurent Jouanneau, 2006-2007 Loic Mathaud
* @copyright   2007 Thibault Piront
* @copyright   2008 Christophe Thiriot
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jCmdLineRequest extends jRequest{
	public $type='cmdline';
	public $defaultResponseType='cmdline';
	public $authorizedResponseClass='jResponseCmdline';
	protected $onlyDefaultAction=false;
	function __construct($onlyDefaultAction=false){
		$this->onlyDefaultAction=$onlyDefaultAction;
	}
	protected function _initUrlData(){
		$this->urlScriptPath='/';
		$this->urlScriptName=$this->urlScript=$_SERVER['SCRIPT_NAME'];
		$this->urlPathInfo='';
	}
	protected function _initParams(){
		$argv=$_SERVER['argv'];
		$scriptName=array_shift($argv);
		$mod=jApp::config()->startModule;
		$act=jApp::config()->startAction;
		if($this->onlyDefaultAction){
			if($_SERVER['argc'] > 1&&$argv[0]=='help'){
				$argv[0]=$mod.'~'.$act;
				$mod='jelix';
				$act='help:index';
			}
		}
		else{
			if($_SERVER['argc']!=1){
				$argsel=array_shift($argv);
				if($argsel=='help'){
					$mod='jelix';
					$act='help:index';
				}else if(($pos=strpos($argsel,'~'))!==false){
					$mod=substr($argsel,0,$pos);
					$act=substr($argsel,$pos+1);
				}else{
					$act=$argsel;
				}
			}
		}
		$this->params=$argv;
		$this->params['module']=$mod;
		$this->params['action']=$act;
	}
	function getIP(){
		return '127.0.0.1';
	}
	public function isAllowedResponse($response){
		return($response instanceof $this->authorizedResponseClass);
	}
}
