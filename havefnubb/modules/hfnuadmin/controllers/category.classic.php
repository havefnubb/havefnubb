<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @license    All right reserved
*/

class categoryCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'index'    => array( 'jacl2.rights.and'=>array('hfnu.admin.category.create',
														'hfnu.admin.category.edit')
							),
        'delete'   => array( 'jacl2.right'=>'hfnu.admin.category.delete'),
    );
    
    function index() {
        $tpl = new jTpl();
		
		$form = jForms::create('hfnuadmin~category');

        $dao = jDao::get('havefnubb~category');
        $categories = $dao->findAll();
		
        $tpl->assign('form', $form);
        $tpl->assign('categories',$categories);
		
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~category_index'));
        return $rep;
	
    }
	
    function savecreate () {
        $form = jForms::get('hfnuadmin~category');
        if ($form->check()) {
            jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~category:index';
            return $rep;
        }
               
        if ($this->param('validate') == jLocale::get('hfnuadmin~category.saveBt')) {

            $dao = jDao::get('havefnubb~category');
            
            $form = jForms::fill('hfnuadmin~category');
            
            $record = jDao::createRecord('havefnubb~category');        
            $record->cat_name = $form->getData('cat_name');
            $record->cat_order = $form->getData('cat_order');
            
            $dao->insert($record);
            
            jMessage::add(jLocale::get('hfnuadmin~category.category.added'),'ok');
        }
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~category:index';
        return $rep;

    }

    function saveedit () {
        $id_cat = (int) $this->param('id_cat');
        
        if ($id_cat == 0) {
            jMessage::add(jLocale::get('hfnuadmin~category.unknown.category'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~category:index';
            return $rep;                 
        } 
        
        
        $form = jForms::fill('hfnuadmin~category',$id_cat);
        if (!$form->check()) {
            jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~category:index';
            return $rep;
        }
        
        $dao = jDao::get('havefnubb~category');
        
        $record 			= $dao->get($id_cat);
        $record->cat_name 	= $form->getData('cat_name');
        $record->cat_order 	= $form->getData('cat_order');
        
        $dao->update($record);
        
        jMessage::add(jLocale::get('hfnuadmin~category.category.modified'),'ok');
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~category:index';
        return $rep;
    }

    function delete() {
		$id_cat = (integer) $this->param('id_cat');
		if ($id_cat == 0) {
			jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
		} else {		
			//@TODO : check if there is existing Forum linked to this category
			// if so, ask the user if he wants to remove the forum and all the linked posts !
			$dao = jDao::get('havefnubb~category');        
			$dao->delete($id_cat);
        
	        jMessage::add(jLocale::get('hfnuadmin~category.category.deleted'),'ok');
		}
		
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~category:index';
        return $rep;        
    }   
    
}   