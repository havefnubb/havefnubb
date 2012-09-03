<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  dao
 * @author      Laurent Jouanneau
 * @contributor Loic Mathaud, Olivier Demah, Sid-Ali Djenadi
 * @copyright   2005-2012 Laurent Jouanneau
 * @copyright   2007 Loic Mathau, 2012 Sid-Ali Djenadid
 * @copyright   2010 Olivier Demah
 * @link        http://www.jelix.org
 * @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
abstract class jDaoRecordBase{
	const ERROR_REQUIRED=1;
	const ERROR_BAD_TYPE=2;
	const ERROR_BAD_FORMAT=3;
	const ERROR_MAXLENGTH=4;
	const ERROR_MINLENGTH=5;
	abstract public function getSelector();
	abstract public function getProperties();
	abstract public function getPrimaryKeyNames();
	public function check(){
		$errors=array();
		foreach($this->getProperties()as $prop=>$infos){
			$value=$this->$prop;
			if($infos['required']&&$value===null){
				$errors[$prop][]=self::ERROR_REQUIRED;
				continue;
			}
			switch($infos['datatype']){
			case 'varchar':
			case 'string' :
				if(!is_string($value)&&$value!==null){
					$errors[$prop][]=self::ERROR_BAD_TYPE;
					break;
				}
				if($infos['regExp']!==null&&preg_match($infos['regExp'],$value)===0){
					$errors[$prop][]=self::ERROR_BAD_FORMAT;
					break;
				}
				$len=iconv_strlen($value,jApp::config()->charset);
				if($infos['maxlength']!==null&&$len > intval($infos['maxlength'])){
					$errors[$prop][]=self::ERROR_MAXLENGTH;
				}
				if($infos['minlength']!==null&&$len < intval($infos['minlength'])){
					$errors[$prop][]=self::ERROR_MINLENGTH;
				}
				break;
			case 'int';
			case 'integer':
			case 'numeric':
			case 'double':
			case 'float':
				if($value!==null&&!is_numeric($value)){
					$errors[$prop][]=self::ERROR_BAD_TYPE;
				}
				break;
			case 'datetime':
				if(!preg_match('/^(\d{4}-(((0[1,3-9]|1[0-2])-([012][0-9]|3[01]))|((02-([01][0-9]|2[0-9])))) (([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])?$/',$value))
					$errors[$prop][]=self::ERROR_BAD_FORMAT;
				break;
			case 'time':
				if(!preg_match('/^((([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])?$/',$value))
					$errors[$prop][]=self::ERROR_BAD_FORMAT;
				break;
			case 'varchardate':
			case 'date':
				if(!preg_match('/^(\d{4}-(((0[1,3-9]|1[0-2])-([012][0-9]|3[01]))|((02-([01][0-9]|2[0-9])))))?$/',$value))
					$errors[$prop][]=self::ERROR_BAD_FORMAT;
				break;
			}
		}
		return(count($errors)?$errors:false);
	}
	public function setPk(){
		$args=func_get_args();
		if(count($args)==1&&is_array($args[0])){
			$args=$args[0];
		}
		$pkf=$this->getPrimaryKeyNames();
		if(count($args)==0||count($args)!=count($pkf))
			throw new jException('jelix~dao.error.keys.missing');
		foreach($pkf as $k=>$prop){
			$this->$prop=$args[$k];
		}
		return true;
	}
	public function getPk(){
		$pkf=$this->getPrimaryKeyNames();
		if(count($pkf)==1){
			return $this->{$pkf[0]};
		}else{
			$list=array();
			foreach($pkf as $k=>$prop){
				$list[]=$this->$prop;
			}
			return $list;
		}
	}
	function save(){
		$dao=jDao::get($this->getSelector());
		$pkFields=$this->getPrimaryKeyNames();
		if($this->{$pkFields[0]}==null)
			return $dao->insert($this);
		else
			return $dao->update($this);
	}
}
