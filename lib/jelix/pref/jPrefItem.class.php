<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package   jelix
* @subpackage pref
* @author    Florian Lonqueu-Brochard
* @copyright 2012 Florian Lonqueu-Brochard
* @link      http://jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jPrefItem{
	public $id;
	public $type='string';
	public $value;
	public $locale;
	public $group;
	public $read_acl_subject;
	public $write_acl_subject;
	public $default_value;
	protected $_writable;
	protected $_readable;
	public static $allowed_types=array('integer','decimal','string','boolean');
	public function isReadable(){
		return $this->_readable;
	}
	public function isWritable(){
		return $this->_writable;
	}
	public function setFromIniNode($node_key,$node){
		$this->id=substr($node_key,5);
		if(!empty($node['type'])){
			if(in_array($node['type'],self::$allowed_types))
				$this->type=$node['type'];
			else
				throw new jException('jPref~admin.type.not.allowed',array($node['type'],implode(',',self::$allowed_types)));
		}
		if(!empty($node['locale']))
			$this->locale=$node['locale'];
		if(!empty($node['group']))
			$this->group=$node['group'];
		$this->_readable=empty($node['read_acl_subject'])||jAcl2::check($node['read_acl_subject']);
		$this->_writable=empty($node['write_acl_subject'])||jAcl2::check($node['write_acl_subject']);
		if(!empty($node['default_value']))
			$this->default_value=$node['default_value'];
		if($this->type=='boolean'){
			if($this->default_value=='true'||$this->default_value=='1')
				$this->default_value=true;
			else if($this->default_value=='false'||(isset($node['default_value'])&&$node['default_value']==''))
				$this->default_value=false;
		}
	}
	public function loadValue(){
		$this->value=jPref::get($this->id);
	}
	public static function compareGroup($a,$b){
		if(empty($a->order)||empty($b->order)){
			if(empty($a->order)&&empty($b->order))
				return 0;
			elseif(empty($a->order))
				return 1;
			else
				return -1;
		}
		else if($a->order > $b->order)
			return 1;
		else if($a->order < $b->order)
			return -1;
		else
			return 0;
	}
}
