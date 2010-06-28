<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Laurent Jouanneau
* @contributor F. Fernandez
* @copyright  2006 Laurent Jouanneau, 2007 F. Fernandez
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jLog{
	private function __construct(){}
	public static function dump($obj,$label='',$type='default'){
		if($label!=''){
			$message=$label.': '.var_export($obj,true);
		}else{
			$message=var_export($obj,true);
		}
		self::log($message,$type);
	}
	public static function log($message,$type='default'){
		$f=$GLOBALS['gJConfig']->logfiles[$type];
		if($f[0]=='!'){
			$GLOBALS['gJCoord']->addLogMsg("log $type: $message",substr($f,1));
		}
		else{
			if(!isset($_SERVER['REMOTE_ADDR'])){
				$_SERVER['REMOTE_ADDR']='127.0.0.1';
			}
			$f=str_replace('%ip%',$_SERVER['REMOTE_ADDR'],$f);
			$f=str_replace('%m%',date("m"),$f);
			$f=str_replace('%Y%',date("Y"),$f);
			$f=str_replace('%d%',date("d"),$f);
			$f=str_replace('%H%',date("H"),$f);
			$sel=new jSelectorLog($f);
			error_log(date("Y-m-d H:i:s")."\t".$_SERVER['REMOTE_ADDR']."\t$type\t$message\n",3,$sel->getPath());
		}
	}
}
