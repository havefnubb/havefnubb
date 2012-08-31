<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @copyright   2011 Laurent Jouanneau
* @link        http://jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class  jLogSoapMessage extends jLogMessage{
	protected $headers;
	protected $request;
	protected $response;
	public function __construct($function_name,$soapClient,$category='default'){
		$this->category=$category;
		$this->headers=$soapClient->__getLastRequestHeaders();
		$this->request=$soapClient->__getLastRequest();
		$this->response=$soapClient->__getLastResponse();
		$this->functionName=$function_name;
		$this->message='Soap call: '.$function_name.'()';
	}
	public function getHeaders(){
		return $this->headers;
	}
	public function getResponse(){
		return $this->response;
	}
	public function getRequest(){
		return $this->request;
	}
	public function getFormatedMessage(){
		$message='Soap call: '.$this->functionName."()\n";
		$message.="HEADERS:\n\t".str_replace("\n","\n\t",$this->headers)."\n";
		$message.="REQUEST:\n\t".str_replace("\n","\n\t",$this->request)."\n";
		$message.="RESPONSE:\n\t".str_replace("\n","\n\t",$this->response)."\n";
		return $message;
	}
}
class SoapClientDebug extends SoapClient{
	public function __call($function_name,$arguments){
		$result=parent::__call($function_name,$arguments);
		$log=new jLogSoapMessage($function_name,$this,'soap');
		jLog::log($log,'soap');
		return $result;
	}
	public function __soapCall($function_name,$arguments,$options=array(),$input_headers=null,&$output_headers=null){
		$result=parent::__soapCall($function_name,$arguments,$options,$input_headers,$output_headers);
		$log=new jLogSoapMessage($function_name,$this,'soap');
		jLog::log($log,'soap');
		return $result;
	}
}
class jSoapClient{
	public static function get($profile=''){
		return jProfiles::getOrStoreInPool('jsoapclient',$profile,array('jSoapClient','_getClient'));
	}
	public static function _getClient($profile){
		$wsdl=null;
		$client='SoapClient';
		if(isset($profile['wsdl'])){
			$wsdl=$profile['wsdl'];
			if($wsdl=='')
				$wsdl=null;
			unset($profile['wsdl']);
		}
		if(isset($profile['trace'])){
			$profile['trace']=intval($profile['trace']);
			if($profile['trace'])
				$client='SoapClientDebug';
		}
		if(isset($profile['exceptions'])){
			$profile['exceptions']=intval($profile['exceptions']);
		}
		if(isset($profile['connection_timeout'])){
			$profile['connection_timeout']=intval($profile['connection_timeout']);
		}
		unset($profile['_name']);
		if(isset($profile['classmap'])&&is_string($profile['classmap'])&&$profile['classmap']!=''){
			$profile['classmap']=(array)json_decode(str_replace("'",'"',$profile['classmap']));
		}
		return new $client($wsdl,$profile);
	}
}
