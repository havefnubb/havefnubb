<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Loic Mathaud
* @contributor Laurent Jouanneau, Erika31, Julien Issler
* @copyright  2006 Loic Mathaud, 2008-2012 Laurent Jouanneau, 2017 Erika31, 2017 Julien Issler
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jIniFile{
	public static function read($filename){
		if(file_exists($filename)){
			return parse_ini_file($filename,true);
		}else{
			return false;
		}
	}
	public static function write($array,$filename,$header='',$chmod=null){
		$result='';
		foreach($array as $k=>$v){
			if(is_array($v)){
				$result.='['.$k."]\n";
				foreach($v as $k2=>$v2){
					$result.=self::_iniValue($k2,$v2);
				}
			}else{
				$result=self::_iniValue($k,$v).$result;
			}
		}
		if($f=@fopen($filename,'wb')){
			fwrite($f,$header.$result);
			fclose($f);
			if($chmod){
				chmod($filename,$chmod);
			}
		}else{
			if(jApp::config()){
				throw new jException('jelix~errors.inifile.write.error',array($filename));
			}else{
				throw new Exception('(24)Error while writing ini file '.$filename);
			}
		}
	}
	static private function _iniValue($key,$value){
		if(is_array($value)){
			$res='';
			foreach($value as $v)
				$res.=self::_iniValue($key.'[]',$v);
			return $res;
		}elseif($value==''
			||is_numeric($value)
			||(is_string($value)&&
				preg_match("/^[\w\\-\\.]*$/",$value)&&
				strpos("\n",$value)===false)
		){
			return $key.'='.$value."\n";
		}else if($value===false){
			return $key."=0\n";
		}else if($value===true){
			return $key."=1\n";
		}else{
			return $key.'="'.$value."\"\n";
		}
	}
}
