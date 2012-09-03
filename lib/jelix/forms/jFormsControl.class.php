<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor Loic Mathaud, Dominique Papin, Julien Issler, Olivier Demah
* @copyright   2006-2008 Laurent Jouanneau, 2007-2008 Dominique Papin
* @copyright   2007 Loic Mathaud
* @copyright   2008 Julien Issler
* @copyright   2009 Olivier Demah
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
abstract class jFormsControl{
	public $type=null;
	public $ref='';
	public $datatype;
	public $required=false;
	public $label='';
	public $defaultValue='';
	public $help='';
	public $hint='';
	public $alertInvalid='';
	public $alertRequired='';
	public $initialReadOnly=false;
	public $initialActivation=true;
	protected $form;
	protected $container;
	function __construct($ref){
		$this->ref=$ref;
		$this->datatype=new jDatatypeString();
	}
	function setForm($form){
		$this->form=$form;
		$this->container=$form->getContainer();
		if($this->initialReadOnly)
			$this->container->setReadOnly($this->ref,true);
		if(!$this->initialActivation)
			$this->container->deactivate($this->ref,true);
	}
	function isContainer(){
		return false;
	}
	function check(){
		$value=$this->container->data[$this->ref];
		if(trim($value)==''){
			if($this->required)
				return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
			if(!$this->datatype->allowWhitespace()){
				$this->container->data[$this->ref]=trim($value);
			}
		}elseif(!$this->datatype->check($value)){
			return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
		}elseif($this->datatype instanceof jIFilteredDatatype){
			$this->container->data[$this->ref]=$this->datatype->getFilteredValue();
		}
		return null;
	}
	function setData($value){
		$this->container->data[$this->ref]=$value;
	}
	function setReadOnly($r=true){
		$this->container->setReadOnly($this->ref,$r);
	}
	function setValueFromRequest($request){
		$this->setData($request->getParam($this->ref,''));
	}
	function setDataFromDao($value,$daoDatatype){
		$this->setData($value);
	}
	function getDisplayValue($value){
		return $value;
	}
	public function isHtmlContent(){
		return false;
	}
	public function deactivate($deactivation=true){
		$this->container->deactivate($this->ref,$deactivation);
	}
	public function isActivated(){
		return $this->container->isActivated($this->ref);
	}
	public function isReadOnly(){
		return $this->container->isReadOnly($this->ref);
	}
}
abstract class jFormsControlDatasource extends jFormsControl{
	public $type="datasource";
	public $datasource;
	public $defaultValue=array();
	function getDisplayValue($value){
		if(is_array($value)){
			$labels=array();
			foreach($value as $val){
				$labels[$val]=$this->_getLabel($val);
			}
			return $labels;
		}else{
			return $this->_getLabel($value);
		}
	}
	protected function _getLabel($value){
		if($this->datasource instanceof jIFormsDatasource2)
			return $this->datasource->getLabel2($value,$this->form);
		else
			return $this->datasource->getLabel($value);
	}
}
abstract class jFormsControlGroups extends jFormsControl{
	public $type='groups';
	protected $childControls=array();
	function check(){
		$rv=null;
		foreach($this->childControls as $ctrl){
			if(($rv2=$ctrl->check())!==null){
				$rv=$rv2;
			}
		}
		return $rv;
	}
	function getDisplayValue($value){
		return $value;
	}
	function setValueFromRequest($request){
		foreach($this->childControls as $name=>$ctrl){
			if(!$this->form->isActivated($name)||$this->form->isReadOnly($name))
				continue;
			$ctrl->setValueFromRequest($request);
		}
		$this->setData($request->getParam($this->ref,''));
	}
	function addChildControl($control,$itemName=''){
		$this->childControls[$control->ref]=$control;
	}
	function getChildControls(){return $this->childControls;}
	function setReadOnly($r=true){
		$this->container->setReadOnly($this->ref,$r);
		foreach($this->childControls as $ctrl){
			$ctrl->setReadOnly($r);
		}
	}
	public function deactivate($deactivation=true){
		$this->container->deactivate($this->ref,$deactivation);
		foreach($this->childControls as $ctrl){
			$ctrl->deactivate($deactivation);
		}
	}
}
class jFormsControlCaptcha extends jFormsControl{
	public $type='captcha';
	public $question='';
	public $required=true;
	function check(){
		$value=$this->container->data[$this->ref];
		if(trim($value)==''){
			return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
		}elseif($value!=$this->container->privateData[$this->ref]){
			return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
		}
		return null;
	}
	function initExpectedValue(){
		$numbers=jLocale::get('jelix~captcha.number');
		$id=rand(1,intval($numbers));
		$this->question=jLocale::get('jelix~captcha.question.'.$id);
		$this->container->privateData[$this->ref]=jLocale::get('jelix~captcha.response.'.$id);
	}
}
class jFormsControlCheckbox extends jFormsControl{
	public $type='checkbox';
	public $defaultValue='0';
	public $valueOnCheck='1';
	public $valueOnUncheck='0';
	function __construct($ref){
		$this->ref=$ref;
		$this->datatype=new jDatatypeBoolean();
	}
	function check(){
		$value=$this->container->data[$this->ref];
		if($this->required&&$value==$this->valueOnUncheck)
			return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
		if($value!=$this->valueOnCheck&&$value!=$this->valueOnUncheck)
			return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
		return null;
	}
	function setValueFromRequest($request){
		$value=$request->getParam($this->ref);
		if($value){
			$this->setData($this->valueOnCheck);
		}else{
			$this->setData($this->valueOnUncheck);
		}
	}
	function setData($value){
		$value=(string) $value;
		if($value!=$this->valueOnCheck){
			if($value=='on')
				$value=$this->valueOnCheck;
			else
				$value=$this->valueOnUncheck;
		}
		parent::setData($value);
	}
	function setDataFromDao($value,$daoDatatype){
		if($daoDatatype=='boolean'){
			if(strtolower($value)=='true'||$value==='t'||intval($value)==1||$value==='on'||$value===true){
				$value=$this->valueOnCheck;
			}else{
				$value=$this->valueOnUncheck;
			}
		}
		$this->setData($value);
	}
}
class jFormsControlCheckboxes extends jFormsControlDatasource{
	public $type="checkboxes";
	function isContainer(){
		return true;
	}
	function check(){
		$value=$this->container->data[$this->ref];
		if(is_array($value)){
			if(count($value)==0&&$this->required){
				return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
			}
		}else{
			if(trim($value)==''){
				if($this->required)
					return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
			}else{
				return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
			}
		}
		return null;
	}
}
class jFormsControlChoice extends jFormsControlGroups{
	public $type="choice";
	public $items=array();
	public $itemsNames=array();
	function check(){
		$val=$this->container->data[$this->ref];
		if(isset($this->container->privateData[$this->ref][$val])){
			return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
		}
		if($val!==""&&$val!==null&&isset($this->items[$val])){
			$rv=null;
			foreach($this->items[$val] as $ctrl){
				if(!$ctrl->isActivated())
					continue;
				if(($rv2=$ctrl->check())!==null){
					$rv=$rv2;
				}
			}
			return $rv;
		}else if($this->required){
			return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
		}
		return null;
	}
	function createItem($value,$label){
		$this->items[$value]=array();
		$this->itemsNames[$value]=$label;
	}
	function deactivateItem($value,$deactivation=true){
		if(!isset($this->items[$value]))
			return;
		if($deactivation){
			$this->container->privateData[$this->ref][$value]=true;
		}
		else if(isset($this->container->privateData[$this->ref][$value])){
			unset($this->container->privateData[$this->ref][$value]);
		}
	}
	function isItemActivated($value){
		return !(isset($this->container->privateData[$this->ref][$value]));
	}
	function addChildControl($control,$itemValue=''){
		$this->childControls[$control->ref]=$control;
		$this->items[$itemValue][$control->ref]=$control;
	}
	function setValueFromRequest($request){
		$value=$request->getParam($this->ref,'');
		if(isset($this->container->privateData[$this->ref][$value])){
			$this->setData('');
			return;
		}
		$this->setData($value);
		if(isset($this->items[$this->container->data[$this->ref]])){
			foreach($this->items[$this->container->data[$this->ref]] as $name=>$ctrl){
				$ctrl->setValueFromRequest($request);
			}
		}
	}
}
class jFormsControlGroup extends jFormsControlGroups{
	public $type="group";
}
class jFormsControlReset extends jFormsControl{
	public $type='reset';
	public function check(){
		return null;
	}
}
class jFormsControlHidden extends jFormsControlReset{
	public $type='hidden';
}
class jFormsControlHtmlEditor extends jFormsControl{
	public $type='htmleditor';
	public $rows=5;
	public $cols=40;
	public $config='default';
	public $skin='default';
	function __construct($ref){
		$this->ref=$ref;
		$this->datatype=new jDatatypeHtml(true,true);
	}
	public function isHtmlContent(){
		return true;
	}
}
class jFormsControlInput extends jFormsControl{
	public $type='input';
	public $size=0;
	function setDataFromDao($value,$daoDatatype){
		if($this->datatype instanceof jDatatypeLocaleDateTime
			&&$daoDatatype=='datetime'){
			if($value!=''){
				$dt=new jDateTime();
				$dt->setFromString($value,jDateTime::DB_DTFORMAT);
				$value=$dt->toString(jDateTime::LANG_DTFORMAT);
			}
		}elseif($this->datatype instanceof jDatatypeLocaleDate
				&&$daoDatatype=='date'){
			if($value!=''){
				$dt=new jDateTime();
				$dt->setFromString($value,jDateTime::DB_DFORMAT);
				$value=$dt->toString(jDateTime::LANG_DFORMAT);
			}
		}
		$this->setData($value);
	}
	public function isHtmlContent(){
		return($this->datatype instanceof jDatatypeHtml);
	}
}
class jFormsControlListbox extends jFormsControlDatasource{
	public $type="listbox";
	public $multiple=false;
	public $size=4;
	public $emptyItemLabel;
	function isContainer(){
		return $this->multiple;
	}
	function check(){
		$value=$this->container->data[$this->ref];
		if(is_array($value)){
			if(!$this->multiple){
				return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
			}
			if(count($value)==0&&$this->required){
				return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
			}
		}else{
			if(trim($value)==''&&$this->required){
				return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
			}
		}
		return null;
	}
}
class jFormsControlRadiobuttons extends jFormsControlDatasource{
	public $type="radiobuttons";
	public $defaultValue='';
	function check(){
		if($this->container->data[$this->ref]==''&&$this->required){
			return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
		}
		return null;
	}
}
class jFormsControlMenulist extends jFormsControlRadiobuttons{
	public $type="menulist";
	public $defaultValue='';
	public $emptyItemLabel='';
}
class jFormsControlOutput extends jFormsControl{
	public $type='output';
	function setValueFromRequest($request){
	}
	public function check(){
		return null;
	}
	function setDataFromDao($value,$daoDatatype){
		if($this->datatype instanceof jDatatypeLocaleDateTime
			&&$daoDatatype=='datetime'){
			if($value!=''){
				$dt=new jDateTime();
				$dt->setFromString($value,jDateTime::DB_DTFORMAT);
				$value=$dt->toString(jDateTime::LANG_DTFORMAT);
			}
		}elseif($this->datatype instanceof jDatatypeLocaleDate
				&&$daoDatatype=='date'){
			if($value!=''){
				$dt=new jDateTime();
				$dt->setFromString($value,jDateTime::DB_DFORMAT);
				$value=$dt->toString(jDateTime::LANG_DFORMAT);
			}
		}
		$this->setData($value);
	}
}
class jFormsControlRepeat extends jFormsControlGroups{
	public $type="repeat";
}
class jFormsControlSecret extends jFormsControl{
	public $type='secret';
	public $size=0;
	function getDisplayValue($value){
		return str_repeat("*",strlen($value));
	}
}
class jFormsControlSecretConfirm extends jFormsControl{
	public $type='secretconfirm';
	public $size=0;
	public $primarySecret='';
	function check(){
		if($this->container->data[$this->ref]!=$this->form->getData($this->primarySecret))
			return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
		return null;
	}
}
class jFormsControlSubmit extends jFormsControlDatasource{
	public $type='submit';
	public $standalone=true;
	public function check(){
		return null;
	}
	function setValueFromRequest($request){
		$value=$request->getParam($this->ref,'');
		if($value&&!$this->standalone){
			$data=$this->datasource->getData($this->form);
			if(!isset($data[$value])){
				$data=array_flip($data);
				if(isset($data[$value])){
					$value=$data[$value];
				}
			}
		}
		$this->setData($value);
	}
}
class jFormsControlSwitch extends jFormsControlChoice{
	public $type="switch";
	function setValueFromRequest($request){
		if(isset($this->items[$this->container->data[$this->ref]])){
			foreach($this->items[$this->container->data[$this->ref]] as $name=>$ctrl){
				$ctrl->setValueFromRequest($request);
			}
		}
	}
}
class jFormsControlTextarea extends jFormsControl{
	public $type='textarea';
	public $rows=5;
	public $cols=40;
	public function isHtmlContent(){
		return($this->datatype instanceof jDatatypeHtml);
	}
}
class jFormsControlUpload extends jFormsControl{
	public $type='upload';
	public $mimetype=array();
	public $maxsize=0;
	public $fileInfo=array();
	function check(){
		if(isset($_FILES[$this->ref]))
			$this->fileInfo=$_FILES[$this->ref];
		else
			$this->fileInfo=array('name'=>'','type'=>'','size'=>0,'tmp_name'=>'','error'=>UPLOAD_ERR_NO_FILE);
		if($this->fileInfo['error']==UPLOAD_ERR_NO_FILE){
			if($this->required)
				return $this->container->errors[$this->ref]=jForms::ERRDATA_REQUIRED;
		}else{
			if($this->fileInfo['error']==UPLOAD_ERR_NO_TMP_DIR
				||$this->fileInfo['error']==UPLOAD_ERR_CANT_WRITE)
				return $this->container->errors[$this->ref]=jForms::ERRDATA_FILE_UPLOAD_ERROR;
			if($this->fileInfo['error']==UPLOAD_ERR_INI_SIZE
				||$this->fileInfo['error']==UPLOAD_ERR_FORM_SIZE
				||($this->maxsize&&$this->fileInfo['size'] > $this->maxsize))
				return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID_FILE_SIZE;
			if($this->fileInfo['error']==UPLOAD_ERR_PARTIAL
				||!is_uploaded_file($this->fileInfo['tmp_name']))
				return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID;
			if(count($this->mimetype)){
				$this->fileInfo['type']=jFile::getMimeType($this->fileInfo['tmp_name']);
				if($this->fileInfo['type']=='application/octet-stream'){
					$this->fileInfo['type']=jFile::getMimeTypeFromFilename($this->fileInfo['name']);
				}
				if(!in_array($this->fileInfo['type'],$this->mimetype))
					return $this->container->errors[$this->ref]=jForms::ERRDATA_INVALID_FILE_TYPE;
			}
		}
		return null;
	}
	function setValueFromRequest($request){
		if(isset($_FILES[$this->ref])){
			$this->setData($_FILES[$this->ref]['name']);
		}else{
			$this->setData('');
		}
	}
}
class jFormsControlDate extends jFormsControl{
	public $type='date';
	public function __construct($ref){
		$this->ref=$ref;
		$this->datatype=new jDatatypeDate();
	}
	function setValueFromRequest($request){
		$value=$request->getParam($this->ref,'');
		if(is_array($value))
			$value=$value['year'].'-'.$value['month'].'-'.$value['day'];
		if($value=='--')
			$value='';
		$this->setData($value);
	}
	function getDisplayValue($value){
		if($value!=''){
			$dt=new jDateTime();
			$dt->setFromString($value,jDateTime::DB_DFORMAT);
			$value=$dt->toString(jDateTime::LANG_DFORMAT);
		}
		return $value;
	}
}
class jFormsControlDatetime extends jFormsControlDate{
	public $type='datetime';
	public $enableSeconds=false;
	public function __construct($ref){
		$this->ref=$ref;
		$this->datatype=new jDatatypeDateTime();
	}
	function setValueFromRequest($request){
		$value=$request->getParam($this->ref,'');
		$this->setData($value);
		if(is_array($value)){
			if($value['year']===''&&$value['month']===''&&$value['day']===''&&$value['hour']===''&&$value['minutes']===''&&(!$this->enableSeconds||$value['seconds']===''))
				$this->setData('');
			else{
				if($value['seconds']==='')
					$value['seconds']='00';
				$this->setData($value['year'].'-'.$value['month'].'-'.$value['day'].' '.$value['hour'].':'.$value['minutes'].':'.$value['seconds']);
			}
		}
	}
	function getDisplayValue($value){
		if($value!=''){
			$dt=new jDateTime();
			$dt->setFromString($value,jDateTime::DB_DTFORMAT);
			$value=$dt->toString(jDateTime::LANG_DTFORMAT);
		}
		return $value;
	}
}
class jFormsControlWikiEditor extends jFormsControl{
	public $type='wikieditor';
	public $rows=5;
	public $cols=40;
	public $config='default';
	function __construct($ref){
		$this->ref=$ref;
		$this->datatype=new jDatatypeString();
	}
	public function isHtmlContent(){
		return true;
	}
	public function getDisplayValue($value){
		$engine=jApp::config()->wikieditors[$this->config.'.wiki.rules'];
		$wiki=new jWiki($engine);
		return $wiki->render($value);
	}
}
