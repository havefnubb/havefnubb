<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  logger
* @author      Laurent Jouanneau
* @contributor Julien Issler
* @copyright   2010 Laurent Jouanneau
* @copyright   2011 Julien Issler
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class firebugLogger implements jILogger{
	protected $messages=array();
	function logMessage($message){
		$this->messages[]=$message;
	}
	function output($response){
		if(!count($this->messages))
			return;
		$type=$response->getType();
		if($type!='html'&&$type!='htmlfragment')
			return;
		$src='<script type="text/javascript">//<![CDATA[
if(console){';
		foreach($this->messages  as $m){
			switch($m->getCategory()){
			case 'warning':
				$src.='console.warn("';
				break;
			case 'error':
				$src.='console.error("';
				break;
			case 'notice':
				$src.='console.debug("';
				break;
			default:
				$src.='console.info("';
				break;
			}
			$src.=str_replace(array('"',"\n","\r","\t"),array('\"','\\n','\\r','\\t'),$m->getFormatedMessage());
			$src.='");';
		}
		$src.='} //]]>
</script>';
		$response->addContent($src);
	}
}
