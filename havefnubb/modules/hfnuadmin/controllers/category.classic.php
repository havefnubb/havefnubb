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
        'create'    => array( 'jacl2.right'=>'hfnu.admin.category.create'),
        'edit'      => array( 'jacl2.right'=>'hfnu.admin.category.edit'),
        'delete'    => array( 'jacl2.right'=>'hfnu.admin.category.delete'),
    );
    

    function create () {
        $form = jForms::create('hfnuadmin~category');
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('action','hfnuadmin~category:savecreate');
        $tpl->assign('category_heading',jLocale::get('hfnuadmin~category.create.a.category'));
        $rep->body->assign('MAIN',$tpl->fetch('category_edit'));
        return $rep;        
    }

    function savecreate () {
        $form = jForms::get('hfnuadmin~category');
        if ($form->check()) {
            jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:categories';
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
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:categories';
            return $rep;
        }
        else {
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:categories';
            return $rep;
        }        
    }

    function edit () {
        $id_cat = (int) $this->param('id_cat');
        
        if ($id_cat == 0) {
            jMessage::add(jLocale::get('hfnuadmin~category.unknown.category'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:categories';
            return $rep;                 
        }      

		$form = jForms::create('hfnuadmin~category',$id_cat);
		$form->initFromDao("havefnubb~category");
				
		$form->setData('id_cat',$id_cat);
            
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('action','hfnuadmin~category:saveedit');
        $tpl->assign('category_heading',jLocale::get('hfnuadmin~category.edit.this.category'));
        $rep->body->assign('MAIN',$tpl->fetch('category_edit'));
        return $rep;        
    }

    function saveedit () {
        $id_cat = (int) $this->param('id_cat');
        
        if ($id_cat == 0) {
            jMessage::add(jLocale::get('hfnuadmin~category.unknown.category'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:categories';
            return $rep;                 
        } 
        
        
        $form = jForms::fill('hfnuadmin~category',$id_cat);
        if (!$form->check()) {
            jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:categories';
            return $rep;
        }
        
        $dao = jDao::get('havefnubb~category');
        
        $record = $dao->get($id_cat);
        $record->cat_name = $form->getData('cat_name');
        $record->cat_order = $form->getData('cat_order');
        
        $dao->update($record);
        
        jMessage::add(jLocale::get('hfnuadmin~category.category.modified'),'ok');
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~default:categories';
        return $rep;
    }

    function delete() {
		$id_cat = (integer) $this->param('id_cat');
	
		$dao = jDao::get('havefnubb~category');        
        $dao->delete($id_cat);
        
        jMessage::add(jLocale::get('hfnuadmin~category.category.deleted'),'ok');
        
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~default:categories';
        return $rep;        
    }   
    
}   