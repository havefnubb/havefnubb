<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @copyright   2008-2010 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require(LIB_PATH.'clearbricks/net/class.net.socket.php');
require(LIB_PATH.'clearbricks/net.http/class.net.http.php');
class jHttp extends netHttp{
	protected $user_agent='Clearbricks/Jelix HTTP Client';
	public function delete($path,$data=false)
	{
		$this->path=$path;
		$this->method='DELETE';
		if($data){
			$this->path.='?'.$this->buildQueryString($data);
		}
		return $this->doRequest();
	}
	public function put($path,$data,$charset=null)
	{
		if($charset){
			$this->post_charset=$charset;
		}
		$this->path=$path;
		$this->method='PUT';
		$this->postdata=$this->buildQueryString($data);
		return $this->doRequest();
	}
	protected function debug($msg,$object=false){
		if($this->debug){
			if($object){
				jLog::dump($object,'jhttp debug, '.$msg);
			}
			else{
				jLog::log('jhttp debug, '.$msg);
			}
		}
	}
}
