<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor
* @copyright   2006-2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
include JELIX_LIB_UTILS_PATH.'jZipCreator.class.php';
class jResponseZip extends jResponse{
	protected $_type = 'zip';
	public $content = null;
	public $zipFilename='';
	function __construct(){
		$this->content = new jZipCreator();
		parent::__construct();
	}
	public function output(){
		$zipContent = $this->content->getContent();
		if($this->hasErrors()){
			return false;
		}
		$this->_httpHeaders['Content-Type']='application/zip';
		$this->_httpHeaders['Content-Disposition']='attachment; filename="'.$this->zipFilename.'"';
		$this->addHttpHeader('Content-Description','File Transfert',false);
		$this->addHttpHeader('Content-Transfer-Encoding','binary',false);
		$this->addHttpHeader('Pragma','no-cache',false);
		$this->addHttpHeader('Cache-Control','no-store, no-cache, must-revalidate, post-check=0, pre-check=0',false);
		$this->addHttpHeader('Expires','0',false);
		$this->_httpHeaders['Content-length']=strlen($zipContent);
		$this->sendHttpHeaders();
		echo $zipContent;
		flush();
		return true;
	}
	public function outputErrors(){
		global $gJConfig;
		header("HTTP/1.0 500 Internal Server Error");
		header('Content-Type: text/plain;charset='.$gJConfig->charset);
		if($this->hasErrors()){
			foreach( $GLOBALS['gJCoord']->errorMessages  as $e){
			   echo '['.$e[0].' '.$e[1].'] '.$e[2]." \t".$e[3]." \t".$e[4]."\n";
			}
		}else{
			echo "[unknown error]\n";
		}
	}
}