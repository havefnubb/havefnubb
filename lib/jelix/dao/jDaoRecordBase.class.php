<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  dao
 * @author      Laurent Jouanneau
 * @contributor Loic Mathaud
 * @copyright   2005-2007 Laurent Jouanneau
 * @copyright   2007 Loic Mathaud
 * @link        http://www.jelix.org
 * @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
abstract class jDaoRecordBase{
	const ERROR_REQUIRED=1;
	const ERROR_BAD_TYPE=2;
	const ERROR_BAD_FORMAT=3;
	const ERROR_MAXLENGTH = 4;
	const ERROR_MINLENGTH = 5;
	abstract public function getProperties();
	abstract public function getPrimaryKeyNames();
	public function check(){
		$errors=array();
		foreach($this->getProperties() as $prop=>$infos){
			$value = $this->$prop;
			if($infos['required'] && $value === null){
				$errors[$prop][] = self::ERROR_REQUIRED;
				continue;
			}
			switch($infos['datatype']){
			  case 'varchar':
			  case 'string' :
				if(!is_string($value) && $value !== null){
					$errors[$prop][] = self::ERROR_BAD_TYPE;
					break;
				}
				if($infos['regExp'] !== null && preg_match($infos['regExp'], $value) === 0){
					$errors[$prop][] = self::ERROR_BAD_FORMAT;
					break;
				}
				$len = iconv_strlen($value, $GLOBALS['gJConfig']->charset);
				if($infos['maxlength'] !== null && $len > intval($infos['maxlength'])){
					$errors[$prop][] = self::ERROR_MAXLENGTH;
				}
				if($infos['minlength'] !== null && $len < intval($infos['minlength'])){
					$errors[$prop][] = self::ERROR_MINLENGTH;
				}
				break;
			case 'int';
			case 'integer':
			case 'numeric':
			case 'double':
			case 'float':
				if($value !== null && !is_numeric($value)){
					$errors[$prop][] = self::ERROR_BAD_TYPE;
				}
				break;
			case 'datetime':
				if(!preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})?$/', $value))
					$errors[$prop][] = self::ERROR_BAD_FORMAT;
				break;
			case 'time':
				if(!preg_match('/^(\d{2}:\d{2}:\d{2})?$/', $value))
					$errors[$prop][] = self::ERROR_BAD_FORMAT;
				break;
			case 'varchardate':
			case 'date':
				if(!preg_match('/^(\d{4}-\d{2}-\d{2})?$/', $value))
					$errors[$prop][] = self::ERROR_BAD_FORMAT;
				break;
			}
		}
		return(count($errors)?$errors:false);
	}
	public function setPk(){
		$args=func_get_args();
		if(count($args)==1 && is_array($args[0])){
			$args=$args[0];
		}
		$pkf = $this->getPrimaryKeyNames();
		if(count($args) == 0 || count($args) != count($pkf))
			throw new jException('jelix~dao.error.keys.missing');
		foreach($pkf as $k=>$prop){
			$this->$prop = $args[$k];
		}
		return true;
	}
	public function getPk(){
		$pkf = $this->getPrimaryKeyNames();
		if(count($pkf) == 1){
			return $this->{$pkf[0]};
		}else{
			$list = array();
			foreach($pkf as $k=>$prop){
				$list[] = $this->$prop;
			}
			return $list;
		}
	}
}