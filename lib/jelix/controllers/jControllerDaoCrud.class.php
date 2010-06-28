<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package      jelix
* @subpackage   controllers
* @author       Laurent Jouanneau
* @contributor  Bastien Jaillot
* @contributor  Thibault PIRONT < nuKs >
* @contributor  Mickael Fradin
* @copyright    2007-2009 Laurent Jouanneau
* @copyright    2007 Thibault PIRONT
* @copyright    2007,2008 Bastien Jaillot
* @copyright    2009 Mickael Fradin
* @link         http://www.jelix.org
* @licence      http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class jControllerDaoCrud extends jController{
	protected $dao='';
	protected $form='';
	protected $propertiesForList=array();
	protected $propertiesForRecordsOrder=array();
	protected $listTemplate='jelix~crud_list';
	protected $editTemplate='jelix~crud_edit';
	protected $viewTemplate='jelix~crud_view';
	protected $listPageSize=20;
	protected $templateAssign='MAIN';
	protected $offsetParameterName='offset';
	protected $pseudoFormId='jelix_crud_roxor';
	protected $uploadsDirectory='';
	protected $dbProfile='';
	protected function _getResponse(){
		return $this->getResponse('html');
	}
	protected function _createForm($formId=null){
		return jForms::create($this->form,$formId);
	}
	protected function _getForm($formId=null){
		return jForms::get($this->form,$formId);
	}
	protected function _getAction($method){
		global $gJCoord;
		return $gJCoord->action->module.'~'.$gJCoord->action->controller.':'.$method;
	}
	protected function _checkData($form,$calltype){
		return $this->_checkDatas($form,$calltype);
	}
	protected function _checkDatas($form,$calltype){
		return true;
	}
	function index(){
		$offset=$this->intParam($this->offsetParameterName,0,true);
		$rep=$this->_getResponse();
		$dao=jDao::get($this->dao,$this->dbProfile);
		$cond=jDao::createConditions();
		$this->_indexSetConditions($cond);
		$results=$dao->findBy($cond,$offset,$this->listPageSize);
		$pk=$dao->getPrimaryKeyNames();
		$form=$this->_createForm($this->pseudoFormId);
		$tpl=new jTpl();
		$tpl->assign('list',$results);
		$tpl->assign('primarykey',$pk[0]);
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
		$tpl->assign('page',$offset);
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
		$form=$this->_createForm();
		$this->_preCreate($form);
		$rep=$this->getResponse('redirect');
		$rep->action=$this->_getAction('create');
		return $rep;
	}
	protected function _preCreate($form){
	}
	function create(){
		$form=$this->_getForm();
		if($form==null){
			$form=$this->_createForm();
		}
		$rep=$this->_getResponse();
		$tpl=new jTpl();
		$tpl->assign('id',null);
		$tpl->assign('form',$form);
		$tpl->assign('submitAction',$this->_getAction('savecreate'));
		$tpl->assign('listAction',$this->_getAction('index'));
		$this->_create($form,$rep,$tpl);
		$rep->body->assign($this->templateAssign,$tpl->fetch($this->editTemplate));
		return $rep;
	}
	protected function _create($form,$resp,$tpl){
	}
	protected function _beforeSaveCreate($form,$form_daorec){
	}
	function savecreate(){
		$form=$this->_getForm();
		$rep=$this->getResponse('redirect');
		if($form==null){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$form->initFromRequest();
		if($form->check()&&$this->_checkData($form,false)){
			extract($form->prepareDaoFromControls($this->dao,null,$this->dbProfile),
				EXTR_PREFIX_ALL,"form");
			$this->_beforeSaveCreate($form,$form_daorec);
			$form_dao->insert($form_daorec);
			$id=$form_daorec->getPk();
			$rep->action=$this->_getAction('view');
			$rep->params['id']=$id;
			$this->_afterCreate($form,$id,$rep);
			if($this->uploadsDirectory!==false)
				$form->saveAllFiles($this->uploadsDirectory);
			jForms::destroy($this->form);
			return $rep;
		}else{
			$rep->action=$this->_getAction('create');
			return $rep;
		}
	}
	protected function _afterCreate($form,$id,$resp){
	}
	function preupdate(){
		$id=$this->param('id');
		$rep=$this->getResponse('redirect');
		if($id===null){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$form=$this->_createForm($id);
		try{
			$rec=$form->initFromDao($this->dao,null,$this->dbProfile);
			foreach($rec->getPrimaryKeyNames()as $pkn){
				$c=$form->getControl($pkn);
				if($c!==null){
					$c->setReadOnly(true);
				}
			}
		}catch(Exception $e){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$this->_preUpdate($form);
		$rep->action=$this->_getAction('editupdate');
		$rep->params['id']=$id;
		return $rep;
	}
	protected function _preUpdate($form){
	}
	function editupdate(){
		$id=$this->param('id');
		$form=$this->_getForm($id);
		if($form===null||$id===null){
			$rep=$this->getResponse('redirect');
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$rep=$this->_getResponse();
		$tpl=new jTpl();
		$tpl->assign('id',$id);
		$tpl->assign('form',$form);
		$tpl->assign('submitAction',$this->_getAction('saveupdate'));
		$tpl->assign('listAction',$this->_getAction('index'));
		$tpl->assign('viewAction',$this->_getAction('view'));
		$this->_editUpdate($form,$rep,$tpl);
		$rep->body->assign($this->templateAssign,$tpl->fetch($this->editTemplate));
		return $rep;
	}
	protected function _editUpdate($form,$resp,$tpl){
	}
	protected function _beforeSaveUpdate($form,$form_daorec,$id){
	}
	function saveupdate(){
		$rep=$this->getResponse('redirect');
		$id=$this->param('id');
		$form=$this->_getForm($id);
		if($form===null||$id===null){
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$form->initFromRequest();
		if($form->check()&&$this->_checkData($form,true)){
			extract($form->prepareDaoFromControls($this->dao,$id,$this->dbProfile),
				EXTR_PREFIX_ALL,"form");
			$this->_beforeSaveUpdate($form,$form_daorec,$id);
			$form_dao->update($form_daorec);
			$rep->action=$this->_getAction('view');
			$rep->params['id']=$id;
			$this->_afterUpdate($form,$id,$rep);
			if($this->uploadsDirectory!==false)
				$form->saveAllFiles($this->uploadsDirectory);
			jForms::destroy($this->form,$id);
		}else{
			$rep->action=$this->_getAction('editupdate');
			$rep->params['id']=$id;
		}
		return $rep;
	}
	protected function _afterUpdate($form,$id,$resp){
	}
	function view(){
		$id=$this->param('id');
		if($id===null){
			$rep=$this->getResponse('redirect');
			$rep->action=$this->_getAction('index');
			return $rep;
		}
		$rep=$this->_getResponse();
		$form=$this->_createForm($id);
		$form->initFromDao($this->dao,$id,$this->dbProfile);
		$tpl=new jTpl();
		$tpl->assign('id',$id);
		$tpl->assign('form',$form);
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
		$id=$this->param('id');
		$rep=$this->getResponse('redirect');
		$rep->action=$this->_getAction('index');
		if($id!==null&&$this->_delete($id,$rep)){
			$dao=jDao::get($this->dao,$this->dbProfile);
			$dao->delete($id);
		}
		return $rep;
	}
	protected function _delete($id,$resp){
		return true;
	}
}
