<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor Dominique Papin
* @contributor Bastien Jaillot, Steven Jehannet
* @contributor Christophe Thiriot, Julien Issler, Olivier Demah
* @copyright   2006-2010 Laurent Jouanneau, 2007 Dominique Papin, 2008 Bastien Jaillot
* @copyright   2008-2009 Julien Issler, 2009 Olivier Demah, 2010 Steven Jehannet
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require(JELIX_LIB_PATH.'forms/jFormsControl.class.php');
require(JELIX_LIB_PATH.'forms/jFormsDatasource.class.php');
require_once(JELIX_LIB_UTILS_PATH.'jDatatype.class.php');
class jExceptionForms extends jException{
}
abstract class jFormsBase{
	const SECURITY_LOW=0;
	const SECURITY_CSRF=1;
	public $securityLevel=1;
	protected $controls=array();
	protected $rootControls=array();
	protected $submits=array();
	protected $reset=null;
	protected $uploads=array();
	protected $hiddens=array();
	protected $htmleditors=array();
	protected $wikieditors=array();
	protected $container=null;
	protected $builders=array();
	protected $sel;
	public function __construct($sel,$container,$reset=false){
		$this->container=$container;
		if($reset){
			$this->container->clear();
		}
		$this->container->updatetime=time();
		$this->sel=$sel;
	}
	public function getSelector(){
		return $this->sel;
	}
	public function initFromRequest(){
		$req=jApp::coord()->request;
		if($this->securityLevel==jFormsBase::SECURITY_CSRF){
			if($this->container->token!==$req->getParam('__JFORMS_TOKEN__'))
				throw new jException("jelix~formserr.invalid.token");
		}
		foreach($this->rootControls as $name=>$ctrl){
			if($ctrl instanceof jFormsControlSecret||$ctrl instanceof jFormsControlSecretConfirm){
				jApp::config()->error_handling['sensitiveParameters'][]=$ctrl->ref;
			}
			if(!$this->container->isActivated($name)||$this->container->isReadOnly($name))
				continue;
			$ctrl->setValueFromRequest($req);
		}
	}
	public function check(){
		$this->container->errors=array();
		foreach($this->rootControls as $name=>$ctrl){
			if($this->container->isActivated($name))
				$ctrl->check();
		}
		return count($this->container->errors)==0;
	}
	public function prepareObjectFromControls($object,$properties=null){
		if($properties==null){
			$properties=get_object_vars($object);
			foreach($properties as $n=>$v){
				if(!is_null($v)){
					$r=true;
					$t=gettype($v);
				}
				else{
					$t='varchar';
					$r=false;
				}
				$properties[$n]=array('required'=>$r,'defaultValue'=>$v,'unifiedType'=>$t);
			}
		}
		foreach($this->controls as $name=>$ctrl){
			if(!isset($properties[$name]))
				continue;
			if(is_array($this->container->data[$name])){
				if(count($this->container->data[$name])==1){
					$object->$name=$this->container->data[$name][0];
				}
				else
					continue;
			}
			else{
				$object->$name=$this->container->data[$name];
			}
			if($object->$name==''&&!$properties[$name]['required']){
				$object->$name=null;
			}
			else{
				if(isset($properties[$name]['unifiedType']))
					$type=$properties[$name]['unifiedType'];
				else
					$type=$properties[$name]['datatype'];
				if($object->$name==''&&$properties[$name]['defaultValue']!==null
						&&in_array($type,
									array('int','integer','double','float','numeric','decimal'))){
					$object->$name=$properties[$name]['defaultValue'];
				}
				else if($type=='boolean'&&!is_bool($object->$name)){
					$object->$name=(intval($object->$name)==1||strtolower($object->$name)==='true'
									||$object->$name==='t'||$object->$name==='on');
				}
				else if($ctrl->datatype instanceof jDatatypeLocaleDateTime
						&&$type=='datetime'){
					$dt=new jDateTime();
					$dt->setFromString($object->$name,jDateTime::LANG_DTFORMAT);
					$object->$name=$dt->toString(jDateTime::DB_DTFORMAT);
				}
				elseif($ctrl->datatype instanceof jDatatypeLocaleDate
						&&$type=='date'){
					$dt=new jDateTime();
					$dt->setFromString($object->$name,jDateTime::LANG_DFORMAT);
					$object->$name=$dt->toString(jDateTime::DB_DFORMAT);
				}
			}
		}
	}
	public function initFromDao($daoSelector,$key=null,$dbProfile=''){
		if($key===null)
			$key=$this->container->formId;
		$dao=jDao::create($daoSelector,$dbProfile);
		$daorec=$dao->get($key);
		if(!$daorec){
			if(is_array($key))
				$key=var_export($key,true);
			throw new jExceptionForms('jelix~formserr.bad.formid.for.dao',
									array($daoSelector,$key,$this->sel));
		}
		$prop=$dao->getProperties();
		foreach($this->controls as $name=>$ctrl){
			if(isset($prop[$name])){
				$ctrl->setDataFromDao($daorec->$name,$prop[$name]['datatype']);
			}
		}
		return $daorec;
	}
	public function prepareDaoFromControls($daoSelector,$key=null,$dbProfile=''){
		$dao=jDao::get($daoSelector,$dbProfile);
		if($key===null)
			$key=$this->container->formId;
		if($key!=null&&($daorec=$dao->get($key))){
			$toInsert=false;
		}else{
			$daorec=jDao::createRecord($daoSelector,$dbProfile);
			if($key!=null)
				$daorec->setPk($key);
			$toInsert=true;
		}
		$this->prepareObjectFromControls($daorec,$dao->getProperties());
		return compact("daorec","dao","toInsert");
	}
	public function saveToDao($daoSelector,$key=null,$dbProfile=''){
		$results=$this->prepareDaoFromControls($daoSelector,$key,$dbProfile);
		extract($results);
		if($toInsert){
			$dao->insert($daorec);
		}else{
			$dao->update($daorec);
		}
		return $daorec->getPk();
	}
	public function initControlFromDao($name,$daoSelector,$primaryKey=null,$primaryKeyNames=null,$dbProfile=''){
		if(!isset($this->controls[$name]))
			throw new jExceptionForms('jelix~formserr.unknown.control2',array($name,$this->sel));
		if(!$this->controls[$name]->isContainer()){
			throw new jExceptionForms('jelix~formserr.control.not.container',array($name,$this->sel));
		}
		if(!$this->container->formId)
			throw new jExceptionForms('jelix~formserr.formid.undefined.for.dao',array($name,$this->sel));
		if($primaryKey===null)
			$primaryKey=$this->container->formId;
		if(!is_array($primaryKey))
			$primaryKey=array($primaryKey);
		$dao=jDao::create($daoSelector,$dbProfile);
		$conditions=jDao::createConditions();
		if($primaryKeyNames)
			$pkNamelist=$primaryKeyNames;
		else
			$pkNamelist=$dao->getPrimaryKeyNames();
		foreach($primaryKey as $k=>$pk){
			$conditions->addCondition($pkNamelist[$k],'=',$pk);
		}
		$results=$dao->findBy($conditions);
		$valuefield=$pkNamelist[$k+1];
		$val=array();
		foreach($results as $res){
			$val[]=$res->$valuefield;
		}
		$this->controls[$name]->setData($val);
	}
	public function saveControlToDao($controlName,$daoSelector,$primaryKey=null,$primaryKeyNames=null,$dbProfile=''){
		if(!isset($this->controls[$controlName]))
			throw new jExceptionForms('jelix~formserr.unknown.control2',array($controlName,$this->sel));
		if(!$this->controls[$controlName]->isContainer()){
			throw new jExceptionForms('jelix~formserr.control.not.container',array($controlName,$this->sel));
		}
		$values=$this->container->data[$controlName];
		if(!is_array($values)&&$values!='')
			throw new jExceptionForms('jelix~formserr.value.not.array',array($controlName,$this->sel));
		if(!$this->container->formId&&!$primaryKey)
			throw new jExceptionForms('jelix~formserr.formid.undefined.for.dao',array($controlName,$this->sel));
		if($primaryKey===null)
			$primaryKey=$this->container->formId;
		if(!is_array($primaryKey))
			$primaryKey=array($primaryKey);
		$dao=jDao::create($daoSelector,$dbProfile);
		$daorec=jDao::createRecord($daoSelector,$dbProfile);
		$conditions=jDao::createConditions();
		if($primaryKeyNames)
			$pkNamelist=$primaryKeyNames;
		else
			$pkNamelist=$dao->getPrimaryKeyNames();
		foreach($primaryKey as $k=>$pk){
			$conditions->addCondition($pkNamelist[$k],'=',$pk);
			$daorec->{$pkNamelist[$k]}=$pk;
		}
		$dao->deleteBy($conditions);
		if(is_array($values)){
			$valuefield=$pkNamelist[$k+1];
			foreach($values as $value){
				$daorec->$valuefield=$value;
				$dao->insert($daorec);
			}
		}
	}
	public function getErrors(){return $this->container->errors;}
	public function setErrorOn($field,$mesg){
		$this->container->errors[$field]=$mesg;
	}
	public function setData($name,$value){
		if(!isset($this->controls[$name]))
			throw new jExceptionForms('jelix~formserr.unknown.control2',array($name,$this->sel));
		$this->controls[$name]->setData($value);
	}
	public function getData($name){
		if(isset($this->container->data[$name]))
			return $this->container->data[$name];
		else return null;
	}
	public function getAllData(){return $this->container->data;}
	public function deactivate($name,$deactivation=true){
		if(!isset($this->controls[$name]))
			throw new jExceptionForms('jelix~formserr.unknown.control2',array($name,$this->sel));
		$this->controls[$name]->deactivate($deactivation);
	}
	public function isActivated($name){
		return $this->container->isActivated($name);
	}
	public function setReadOnly($name,$r=true){
		if(!isset($this->controls[$name]))
			throw new jExceptionForms('jelix~formserr.unknown.control2',array($name,$this->sel));
		$this->controls[$name]->setReadOnly($r);
	}
	public function isReadOnly($name){
		return $this->container->isReadOnly($name);
	}
	public function getContainer(){return $this->container;}
	public function getRootControls(){return $this->rootControls;}
	public function getControls(){return $this->controls;}
	public function getControl($name){
		if(isset($this->controls[$name]))
			return $this->controls[$name];
		else return null;
	}
	public function getSubmits(){return $this->submits;}
	public function getHiddens(){return $this->hiddens;}
	public function getHtmlEditors(){return $this->htmleditors;}
	public function getWikiEditors(){return $this->wikieditors;}
	public function getUploads(){return $this->uploads;}
	public function initModifiedControlsList(){
		$this->container->originalData=$this->container->data;
	}
	public function getModifiedControls(){
		if(count($this->container->originalData)){
			$result=array();
			$orig=& $this->container->originalData;
			foreach($this->container->data as $k=>$v1){
				if(!array_key_exists($k,$orig)){
					continue;
				}
				if($this->_diffValues($orig[$k],$v1)){
					$result[$k]=$orig[$k];
					continue;
				}
			}
			return $result;
		}
		else
			return $this->container->data;
	}
	protected function _diffValues(&$v1,&$v2){
		if(is_array($v1)&&is_array($v2)){
			$comp=array_merge(array_diff($v1,$v2),array_diff($v2,$v1));
			return !empty($comp);
		}
		elseif(empty($v1)&&empty($v2)){
			return false;
		}
		elseif(is_array($v1)||is_array($v2)){
			return true;
		}
		else{
			return !($v1==$v2);
		}
	}
	public function getReset(){return $this->reset;}
	public function id(){return $this->container->formId;}
	public function hasUpload(){return count($this->uploads)>0;}
	public function getBuilder($buildertype){
		$legacy=false;
		if($buildertype==''){
			$buildertype=$plugintype='html';
		}
		else if(preg_match('/^legacy\.(.*)$/',$buildertype,$m)){
			$legacy=true;
			$plugintype=$m[1];
		}
		else{
			$plugintype=$buildertype;
		}
		if(isset($this->builders[$buildertype]))
			return $this->builders[$buildertype];
		if(!$legacy){
			$o=jApp::loadPlugin($plugintype,'formbuilder','.formbuilder.php',$plugintype.'FormBuilder',$this);
		}
		else{
			include_once(JELIX_LIB_PATH.'forms/legacy/jFormsBuilderBase.class.php');
			$o=jApp::loadPlugin($plugintype,'jforms','.jformsbuilder.php',$plugintype.'JformsBuilder',$this);
		}
		if($o){
			$this->builders[$buildertype]=$o;
			return $o;
		}else{
			throw new jExceptionForms('jelix~formserr.invalid.form.builder',array($buildertype,$this->sel));
		}
	}
	public function saveFile($controlName,$path='',$alternateName=''){
		if($path==''){
			$path=jApp::varPath('uploads/'.$this->sel.'/');
		}else if(substr($path,-1,1)!='/'){
			$path.='/';
		}
		if(!isset($this->controls[$controlName])||$this->controls[$controlName]->type!='upload')
			throw new jExceptionForms('jelix~formserr.invalid.upload.control.name',array($controlName,$this->sel));
		if(!isset($_FILES[$controlName])||$_FILES[$controlName]['error']!=UPLOAD_ERR_OK)
			return false;
		if($this->controls[$controlName]->maxsize&&$_FILES[$controlName]['size'] > $this->controls[$controlName]->maxsize){
			return false;
		}
		jFile::createDir($path);
		if($alternateName==''){
			$path.=$_FILES[$controlName]['name'];
		}else{
			$path.=$alternateName;
		}
		return move_uploaded_file($_FILES[$controlName]['tmp_name'],$path);
	}
	public function saveAllFiles($path=''){
		if($path==''){
			$path=jApp::varPath('uploads/'.$this->sel.'/');
		}else if(substr($path,-1,1)!='/'){
			$path.='/';
		}
		if(count($this->uploads))
			jFile::createDir($path);
		foreach($this->uploads as $ref=>$ctrl){
			if(!isset($_FILES[$ref])||$_FILES[$ref]['error']!=UPLOAD_ERR_OK)
				continue;
			if($ctrl->maxsize&&$_FILES[$ref]['size'] > $ctrl->maxsize)
				continue;
			move_uploaded_file($_FILES[$ref]['tmp_name'],$path.$_FILES[$ref]['name']);
		}
	}
	public function addControl($control){
		$this->rootControls [$control->ref]=$control;
		$this->addChildControl($control);
		if($control instanceof jFormsControlGroups){
			foreach($control->getChildControls()as $ctrl)
				$this->addChildControl($ctrl);
		}
	}
	public function addControlBefore($control,$ref){
		if(isset($this->rootControls[$ref])){
			$controls=array();
			foreach($this->rootControls as $k=>$c){
				if($k==$ref)
					$controls[$control->ref]=null;
				$controls[$k]=$c;
			}
			$this->rootControls=$controls;
		}
		$this->addControl($control);
	}
	function removeControl($name){
		if(!isset($this->rootControls [$name]))
			return;
		unset($this->rootControls [$name]);
		unset($this->controls [$name]);
		unset($this->submits [$name]);
		if($this->reset&&$this->reset->ref==$name)
			$this->reset=null;
		unset($this->uploads [$name]);
		unset($this->hiddens [$name]);
		unset($this->htmleditors [$name]);
		unset($this->wikieditors [$name]);
		unset($this->container->data[$name]);
	}
	public function addChildControl($control){
		$this->controls [$control->ref]=$control;
		switch($control->type){
			case 'submit':
				$this->submits [$control->ref]=$control;
				break;
			case 'reset':
				$this->reset=$control;
				break;
			case 'upload':
				$this->uploads [$control->ref]=$control;
				break;
			case 'hidden':
				$this->hiddens [$control->ref]=$control;
				break;
			case 'htmleditor':
				$this->htmleditors [$control->ref]=$control;
				break;
			case 'wikieditor':
				$this->wikieditors [$control->ref]=$control;
				break;
		}
		$control->setForm($this);
		if(!isset($this->container->data[$control->ref])){
			if($control->datatype instanceof jDatatypeDateTime&&$control->defaultValue=='now'){
				$dt=new jDateTime();
				$dt->now();
				$this->container->data[$control->ref]=$dt->toString($control->datatype->getFormat());
			}
			else{
				$this->container->data[$control->ref]=$control->defaultValue;
			}
		}
	}
	public function createNewToken(){
	if($this->container->token==''){
		$tok=md5($this->container->formId.time().session_id());
		return($this->container->token=$tok);
	}
	return $this->container->token;
	}
}
