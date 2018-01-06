<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   controllers
* @author       Laurent Jouanneau
* @contributor  Bastien Jaillot
* @contributor  Thibault Piront (nuKs)
* @copyright    2007-2008 Laurent Jouanneau
* @copyright    2007 Thibault Piront
* @copyright    2007,2008 Bastien Jaillot
* @link         http://www.jelix.org
* @licence      http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class jControllerDaoCrudDfk extends jController{
	protected $dpkName='id';
	protected $spkName='spk';
	protected $dao='';
	protected $form='';
	protected $propertiesForList=array();
	protected $propertiesForRecordsOrder=array();
	protected $listTemplate='jelix~cruddfk_list';
	protected $editTemplate='jelix~cruddfk_edit';
	protected $viewTemplate='jelix~cruddfk_view';
	protected $listPageSize=20;
	protected $templateAssign='MAIN';
	protected $offsetParameterName='offset';
	protected $pseudoFormId='jelix_cruddf_roxor';
	protected $uploadsDirectory='';
	protected $dbProfile='';
	protected function _getResponse(){
		return $this->getResponse('html');
	}
	protected function _getAction($method){
		$act=jApp::coord()->action;
		return $act->module.'~'.$act->controller.':'.$method;
	}
	protected function _checkData($spk,$form,$calltype){
		return true;
	}
	protected function _isPkAutoIncrement($dao=null){
		if($dao==null)
			$dao=jDao::get($this->dao,$this->dbProfile);
		$props=$dao->getProperties();
		return($props[$this->dpkName]['autoIncrement']==true);
	}
	protected function _getPk($spk,$dpk,$dao=null){
		if($dao==null)
			$dao=jDao::get($this->dao,$this->dbProfile);
		$pks=$dao->getPrimaryKeyNames();
		if($pks[0]==$this->spkName){
			return array($spk,$dpk);
		}
		else{
			return array($dpk,$spk);
		}
	}
	function index(){
		$offset=$this->intParam($this->offsetParameterName,0,true);
		$rep=$this->_getResponse();
		$dao=jDao::get($this->dao,$this->dbProfile);
		$cond=jDao::createConditions();
		$cond->addCondition($this->spkName,'=',$this->param($this->spkName));
		$this->_indexSetConditions($cond);
		$results=$dao->findBy($cond,$offset,$this->listPageSize);
		$form=jForms::create($this->form,$this->pseudoFormId);
		$tpl=new jTpl();
		$tpl->assign('list',$results);
		$tpl->assign('dpkName',$this->dpkName);
		$tpl->assign('spkName',$this->spkName);
		$tpl->assign('spk',$this->param($this->spkName));
		if(count($this->propertiesForList)){
			$prop=$this->propertiesForList;
		}else{
			$prop=array_keys($dao->getProperties());
		}
		$tpl->assign('properties',$prop);
		$tpl->assign('controls',$form->getControls());
		$tpl->assign('editAction',$this->_getAction('preupdate'));
		$tpl->assign('createAction',$this->_getAction('precreate'));
		$tpl->assign('deleteAction',$this->_getAction('delete'));
		$tpl->assign('viewAction',$this->_getAction('view'));
		$tpl->assign('listAction',$this->_getAction('index'));
		$tpl->assign('listPageSize',$this->listPageSize);
		$tpl->assign('page',$offset>0?$offset:null);
		$tpl->assign('recordCount',$dao->countBy($cond));
		$tpl->assign('offsetParameterName',$this->offsetParameterName);
		$this->_index($rep,$tpl);
		$rep->body->assign($this->templateAssign,$tpl->fetch($this->listTemplate));
		jForms::destroy($this->form,$this->pseudoFormId);
		return $rep;
	}
	protected function _index($resp,$tpl){
	}
	protected function _indexSetConditions($cond){
		foreach($this->propertiesForRecordsOrder as $p=>$order){
			$cond->addItemOrder($p,$order);
		}
	}
	function precreate(){
		$form=jForms::create($this->form);
		$this->_preCreate($form);
		$rep=$this->getResponse('redirect');
		$rep->action=$this->_getAction('create');
		$rep->params[$this->spkName]=$this->param($this->spkName);
		return $rep;
	}
	protected function _preCreate($form){
	}
	function create(){
		$form=jForms::get($this->form);
		if($form==null){
			$form=jForms::create($this->form);
		}
		$rep=$this->_getResponse();
		$tpl=new jTpl();
		$tpl->assign('dpk',null);
		$tpl->assign('page',null);
		$tpl->assign('offsetParameterName',null);
		$tpl->assign('dpkName',$this->dpkName);
		$tpl->assign('spkName',$this->spkName);
		$tpl->assign('spk',$this->param($this->spkName));
		$tpl->assign('form',$form);
		$tpl->assign('submitAction',$this->_getAction('savecreate'));
		$tpl->assign('listAction',$this->_getAction('index'));
		$this->_create($form,$rep,$tpl);
		$rep->body->assign($this->templateAssign,$tpl->fetch($this->editTemplate));
		return $rep;
	}
	protected function _create($form,$resp,$tpl){
	}
	function savecreate(){
		$form=jForms::fill($this->form);
		$spk=$this->param($this->spkName);
		$rep=$this->getResponse('redirect');
		$rep->params[$this->spkName]=$spk;
		if($form==null){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		if($form->check()&&$this->_checkData($spk,$form,false)){
			$results=$form->prepareDaoFromControls($this->dao,null,$this->dbProfile);
			extract($results,EXTR_PREFIX_ALL,"form");
			$form_daorec->{$this->spkName}=$spk;
			if(!$this->_isPkAutoIncrement($form_dao)){
				$form_daorec->{$this->dpkName}=$this->param($this->dpkName);
			}
			$this->_beforeSaveCreate($form,$form_daorec);
			$form_dao->insert($form_daorec);
			$id=$form_daorec->getPk();
			$rep->action=$this->_getAction('view');
			$this->_afterCreate($form,$id,$rep);
			if($this->uploadsDirectory!==false)
				$form->saveAllFiles($this->uploadsDirectory);
			jForms::destroy($this->form);
			$pknames=$form_dao->getPrimaryKeyNames();
			if($pknames[0]==$this->spkName){
				$rep->params[$this->spkName]=$id[0];
				$rep->params[$this->dpkName]=$id[1];
			}
			else{
				$rep->params[$this->spkName]=$id[1];
				$rep->params[$this->dpkName]=$id[0];
			}
			return $rep;
		}else{
			$rep->action=$this->_getAction('create');
			return $rep;
		}
	}
	protected function _beforeSaveCreate($form,$form_daorec){
	}
	protected function _afterCreate($form,$id,$resp){
	}
	function preupdate(){
		$spk=$this->param($this->spkName);
		$dpk=$this->param($this->dpkName);
		$page=$this->param($this->offsetParameterName);
		$rep=$this->getResponse('redirect');
		$rep->params[$this->spkName]=$spk;
		if($dpk===null){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$id=$this->_getPk($spk,$dpk);
		$form=jForms::create($this->form,$id);
		try{
			$form->initFromDao($this->dao,$id,$this->dbProfile);
		}catch(Exception $e){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$this->_preUpdate($form);
		$rep->params[$this->dpkName]=$dpk;
		$rep->params[$this->offsetParameterName]=$page;
		$rep->action=$this->_getAction('editupdate');
		return $rep;
	}
	protected function _preUpdate($form){
	}
	function editupdate(){
		$spk=$this->param($this->spkName);
		$dpk=$this->param($this->dpkName);
		$page=$this->param($this->offsetParameterName);
		$id=$this->_getPk($spk,$dpk);
		$form=jForms::get($this->form,$id);
		if($form===null||$dpk===null){
			$rep=$this->getResponse('redirect');
			$rep->params[$this->spkName]=$spk;
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$rep=$this->_getResponse();
		$tpl=new jTpl();
		$tpl->assign('dpk',$dpk);
		$tpl->assign('dpkName',$this->dpkName);
		$tpl->assign('spkName',$this->spkName);
		$tpl->assign('spk',$spk);
		$tpl->assign('form',$form);
		$tpl->assign('page',$page);
		$tpl->assign('offsetParameterName',$this->offsetParameterName);
		$tpl->assign('submitAction',$this->_getAction('saveupdate'));
		$tpl->assign('listAction',$this->_getAction('index'));
		$tpl->assign('viewAction',$this->_getAction('view'));
		$this->_editUpdate($form,$rep,$tpl);
		$rep->body->assign($this->templateAssign,$tpl->fetch($this->editTemplate));
		return $rep;
	}
	protected function _editUpdate($form,$resp,$tpl){
	}
	function saveupdate(){
		$spk=$this->param($this->spkName);
		$dpk=$this->param($this->dpkName);
		$page=$this->param($this->offsetParameterName);
		$rep=$this->getResponse('redirect');
		$rep->params[$this->spkName]=$spk;
		$id=$this->_getPk($spk,$dpk);
		$form=jForms::fill($this->form,$id);
		if($form===null||$dpk===null){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$rep->params[$this->dpkName]=$dpk;
		$rep->params[$this->offsetParameterName]=$page;
		if($form->check()&&$this->_checkData($spk,$form,true)){
			$results=$form->prepareDaoFromControls($this->dao,$id,$this->dbProfile);
			extract($results,EXTR_PREFIX_ALL,"form");
			$this->_beforeSaveUpdate($form,$form_daorec,$id);
			$form_dao->update($form_daorec);
			$rep->action=$this->_getAction('view');
			$this->_afterUpdate($form,$id,$rep);
			if($this->uploadsDirectory!==false)
				$form->saveAllFiles($this->uploadsDirectory);
			jForms::destroy($this->form,$id);
		}else{
			$rep->action=$this->_getAction('editupdate');
		}
		return $rep;
	}
	protected function _beforeSaveUpdate($form,$form_daorec,$id){
	}
	protected function _afterUpdate($form,$id,$resp){
	}
	function view(){
		$spk=$this->param($this->spkName);
		$dpk=$this->param($this->dpkName);
		$page=$this->param($this->offsetParameterName);
		if($dpk===null){
			$rep=$this->getResponse('redirect');
			$rep->action=$this->_getAction('index');
			$rep->params[$this->spkName]=$spk;
			return $rep;
		}
		$rep=$this->_getResponse();
		$id=$this->_getPk($spk,$dpk);
		$form=jForms::create($this->form,$id);
		$form->initFromDao($this->dao,$id,$this->dbProfile);
		$tpl=new jTpl();
		$tpl->assign('dpk',$dpk);
		$tpl->assign('dpkName',$this->dpkName);
		$tpl->assign('spkName',$this->spkName);
		$tpl->assign('spk',$spk);
		$tpl->assign('form',$form);
		$tpl->assign('page',$page);
		$tpl->assign('offsetParameterName',$this->offsetParameterName);
		$tpl->assign('editAction',$this->_getAction('preupdate'));
		$tpl->assign('deleteAction',$this->_getAction('delete'));
		$tpl->assign('listAction',$this->_getAction('index'));
		$this->_view($form,$rep,$tpl);
		$rep->body->assign($this->templateAssign,$tpl->fetch($this->viewTemplate));
		return $rep;
	}
	protected function _view($form,$resp,$tpl){
	}
	function delete(){
		$spk=$this->param($this->spkName);
		$dpk=$this->param($this->dpkName);
		$page=$this->param($this->offsetParameterName);
		$rep=$this->getResponse('redirect');
		$rep->action=$this->_getAction('index');
		$rep->params[$this->spkName]=$spk;
		$rep->params=array($this->offsetParameterName=>$page);
		$dao=jDao::get($this->dao,$this->dbProfile);
		$id=$this->_getPk($spk,$dpk,$dao);
		if($dpk!==null&&$this->_delete($spk,$dpk,$rep)){
			$dao->delete($id);
		}
		return $rep;
	}
	protected function _delete($spk,$dpk,$resp){
		return true;
	}
}
