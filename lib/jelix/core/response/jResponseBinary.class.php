<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Laurent Jouanneau
* @contributor Nicolas Lassalle <nicolas@beroot.org> (ticket #188), Julien Issler
* @copyright   2005-2010 Laurent Jouanneau
* @copyright   2007 Nicolas Lassalle
* @copyright   2009 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
final class jResponseBinary  extends jResponse{
	protected $_type='binary';
	public $fileName='';
	public $outputFileName='';
	public $content=null;
	public $doDownload=true;
	public $mimeType='application/octet-stream';
	public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		if($this->doDownload){
			$this->mimeType='application/forcedownload';
			if(!strlen($this->outputFileName)){
				$f=explode('/',str_replace('\\','/',$this->fileName));
				$this->outputFileName=$f[count($f)-1];
			}
		}
		$this->addHttpHeader("Content-Type",$this->mimeType,$this->doDownload);
		if($this->doDownload)
			$this->_downloadHeader();
		if($this->content===null){
			if(is_readable($this->fileName)&&is_file($this->fileName)){
				$this->_httpHeaders['Content-Length']=filesize($this->fileName);
				$this->sendHttpHeaders();
				session_write_close();
				readfile($this->fileName);
				flush();
			}
			else{
				throw new jException('jelix~errors.repbin.unknown.file',$this->fileName);
			}
		}else{
			$this->_httpHeaders['Content-Length']=strlen($this->content);
			$this->sendHttpHeaders();
			session_write_close();
			echo $this->content;
			flush();
		}
		return true;
	}
	protected function _downloadHeader(){
		$this->addHttpHeader('Content-Disposition','attachment; filename="'.str_replace('"','\"',$this->outputFileName).'"',false);
		$this->addHttpHeader('Content-Description','File Transfert',false);
		$this->addHttpHeader('Content-Transfer-Encoding','binary',false);
		$this->addHttpHeader('Pragma','public',false);
		$this->addHttpHeader('Cache-Control','maxage=3600',false);
	}
}
