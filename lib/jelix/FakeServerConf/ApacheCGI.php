<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   tests
* @author       Laurent Jouanneau
* @copyright    2012 Laurent Jouanneau
* @link         http://jelix.org
* @licence      MIT
*/
namespace jelix\FakeServerConf;
class ApacheCGI extends FakeServerConf{
	protected $cgiBin='/usr/lib/cgi-bin/php5';
	protected $cgiAlias='/cgi-bin/php5';
	function __construct($documentRoot=null,
						$scriptName=null,
						$cgiBin=null,
						$cgiAlias=null){
		parent::__construct($documentRoot,$scriptName);
		if($cgiBin)
			$this->cgiBin=$cgiBin;
		if($cgiAlias)
			$this->cgiAlias=$cgiAlias;
	}
	public function setHttpRequest($url,$method='get',$body='',$bodyContentType='application/x-www-form-urlencoded'){
		parent::setHttpRequest($url,$method,$body,$bodyContentType);
		if(isset($_SERVER['PATH_INFO'])){
			$_SERVER['PATH_TRANSLATED']=$_SERVER["DOCUMENT_ROOT"].ltrim($_SERVER['PATH_INFO'],'/');
		}
		$_SERVER['ORIG_PATH_INFO']=$_SERVER['PHP_SELF'];
		$_SERVER['ORIG_PATH_TRANSLATED']=$_SERVER['SCRIPT_FILENAME'];
		if(isset($_SERVER['PATH_INFO'])){
			$_SERVER['ORIG_PATH_TRANSLATED'].=$_SERVER['PATH_INFO'];
		}
		$_SERVER['ORIG_SCRIPT_FILENAME']=$this->cgiBin;
		$_SERVER['ORIG_SCRIPT_NAME']=$this->cgiAlias;
		$_SERVER['REDIRECT_URL']=$_SERVER['PHP_SELF'];
	}
}
