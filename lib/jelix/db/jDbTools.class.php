<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Gérald Croes, Laurent Jouanneau
* @contributor Laurent Jouanneau, Gwendal Jouannic, Julien Issler
* @copyright  2001-2005 CopixTeam, 2005-2010 Laurent Jouanneau
* @copyright  2008 Gwendal Jouannic
* @copyright  2008 Julien Issler
*
* This class was get originally from the Copix project (CopixDbTools, CopixDbConnection, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this class was adapted for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDbFieldProperties{
	public $type;
	public $unifiedtype;
	public $name;
	public $notNull=true;
	public $primary=false;
	public $autoIncrement=false;
	public $default='';
	public $hasDefault=false;
	public $length=0;
	public $sequence=false;
	public $unsigned=false;
	public $minLength=null;
	public $maxLength=null;
	public $minValue=null;
	public $maxValue=null;
}
abstract class jDbTools{
	public $trueValue='1';
	public $falseValue='0';
	protected $_conn;
	function __construct($connector){
		$this->_conn=$connector;
	}
	protected $unifiedToPhp=array(
		'boolean'=>'boolean',
		'integer'=>'integer',
		'float'=>'float',
		'double'=>'float',
		'numeric'=>'numeric',
		'decimal'=>'decimal',
		'date'=>'string',
		'time'=>'string',
		'datetime'=>'string',
		'year'=>'string',
		'char'=>'string',
		'varchar'=>'string',
		'text'=>'string',
		'blob'=>'string',
		'binary'=>'string',
		'varbinary'=>'string',
	);
	protected $typesInfo=array();
	public function getTypeInfo($nativeType){
		if(isset($this->typesInfo[$nativeType])){
			$r=$this->typesInfo[$nativeType];
		}
		else
			$r=$this->typesInfo['varchar'];
		$r[]=($nativeType=='serial'||$nativeType=='bigserial'||$nativeType=='autoincrement'||$nativeType=='bigautoincrement');
		return $r;
	}
	public function unifiedToPHPType($unifiedType){
		if(isset($this->unifiedToPhp[$unifiedType])){
			return $this->unifiedToPhp[$unifiedType];
		}
		throw new Exception('bad unified type name:'.$unifiedType);
	}
	public function stringToPhpValue($unifiedType,$value,$checkNull=false){
		if($checkNull&&($value===null||strtolower($value)=='null'))
			return null;
		switch($this->unifiedToPHPType($unifiedType)){
			case 'boolean':
				return($this->getBooleanValue($value)==$this->trueValue);
			case 'integer':
				return intval($value);
			case 'float':
				return floatval($value);
			case 'numeric':
			case 'decimal':
				if(is_numeric($value))
					return $value;
				else
					return floatval($value);
			default:
				return $value;
		}
	}
	public function escapeValue($unifiedType,$value,$checkNull=false,$toPhpSource=false){
		if($checkNull&&($value===null||strtolower($value)=='null'))
			return 'NULL';
		switch($this->unifiedToPHPType($unifiedType)){
			case 'boolean':
				return $this->getBooleanValue($value);
			case 'integer':
				return (string)intval($value);
			case 'float':
				return (string)doubleval($value);
			case 'numeric':
			case 'decimal':
				if(is_numeric($value))
					return $value;
				else
					return (string)floatval($value);
			default:
				if($toPhpSource){
					if($unifiedType=='varbinary'||$unifiedType=='binary'){
						return '\'.$this->_conn->quote2(\''.str_replace('\'','\\\'',$value).'\',true,true).\'';
					}
					else if(strpos($value,"'")!==false){
						return '\'.$this->_conn->quote(\''.str_replace('\'','\\\'',$value).'\').\'';
					}
					else{
						return "\\'".$value."\\'";
					}
				}
				elseif($this->_conn)
					return $this->_conn->quote($value);
				else
					return "'".addslashes($value)."'";
		}
	}
	public function getBooleanValue($value){
	if(is_string($value))
		$value=strtolower($value);
	if($value==='true'||$value===true||intval($value)===1||$value==='t'||$value==='on')
		return $this->trueValue;
	else
		return $this->falseValue;
	}
	public function encloseName($fieldName){
		return $fieldName;
	}
	abstract public function getTableList();
	abstract public function getFieldList($tableName,$sequence='');
	protected $dbmsStyle=array('/^\s*#/','/;\s*$/');
	public function execSQLScript($file){
		if(!isset($this->_conn->profile['table_prefix']))
			$prefix='';
		else
			$prefix=$this->_conn->profile['table_prefix'];
		$lines=file($file);
		$cmdSQL='';
		$nbCmd=0;
		$style=$this->dbmsStyle;
		foreach((array)$lines as $key=>$line){
			if((!preg_match($style[0],$line))&&(strlen(trim($line))>0)){
				$cmdSQL.=$line;
				if(preg_match($style[1],$line)){
					$cmdSQL=preg_replace($style[1],'',$cmdSQL);
					$cmdSQL=str_replace('%%PREFIX%%',$prefix,$cmdSQL);
					$this->_conn->query($cmdSQL);
					$nbCmd++;
					$cmdSQL='';
				}
			}
		}
		return $nbCmd;
	}
}
