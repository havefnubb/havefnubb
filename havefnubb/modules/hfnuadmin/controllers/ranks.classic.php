<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @license    All right reserved
*/

class ranksCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        'create'    => array( 'jacl2.right'=>'hfnu.admin.rank.create'),
        'edit'      => array( 'jacl2.right'=>'hfnu.admin.rank.edit'),
        'delete'    => array( 'jacl2.right'=>'hfnu.admin.rank.delete'),
    );
    

    function create () {
        $form = jForms::create('hfnuadmin~ranks');
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('action','hfnuadmin~ranks:savecreate');
        $tpl->assign('rank_heading',jLocale::get('hfnuadmin~rank.create.a.rank'));
        $rep->body->assign('MAIN',$tpl->fetch('ranks_edit'));
        return $rep;        
    }

    function savecreate () {
        $form = jForms::get('hfnuadmin~ranks');
        if ($form->check()) {
            jMessage::add(jLocale::get('hfnuadmin~rank.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:ranks';
            return $rep;
        }
               
        if ($this->param('validate') == jLocale::get('hfnuadmin~rank.saveBt')) {

            $dao = jDao::get('havefnubb~ranks');
            
            $form = jForms::fill('hfnuadmin~ranks');
            
            $record = jDao::createRecord('havefnubb~ranks');        
            $record->rank_name = $form->getData('rank_name');
            $record->rank_limit = $form->getData('rank_limit');
            
            $dao->insert($record);
            
            jMessage::add(jLocale::get('hfnuadmin~rank.rank.added'),'ok');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:ranks';
            return $rep;
        }
        else {
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:ranks';
            return $rep;
        }        
    }

    function edit () {
        $id_rank = (int) $this->param('id_rank');
        
        if ($id_rank == 0) {
            jMessage::add(jLocale::get('hfnuadmin~rank.unknown.rank'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:rank';
            return $rep;                 
        }      

		$form = jForms::create('hfnuadmin~ranks',$id_rank);
		$form->initFromDao("havefnubb~ranks");
				
		$form->setData('id_rank',$id_rank);
            
        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $tpl->assign('action','hfnuadmin~ranks:saveedit');
        $tpl->assign('rank_heading',jLocale::get('hfnuadmin~rank.edit.this.rank'));
        $rep->body->assign('MAIN',$tpl->fetch('ranks_edit'));
        return $rep;        
    }

    function saveedit () {
        $id_rank = (int) $this->param('id_rank');
        
        if ($id_rank == 0) {
            jMessage::add(jLocale::get('hfnuadmin~rank.unknown.rank'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:rank';
            return $rep;                 
        } 
        
        
        $form = jForms::fill('hfnuadmin~ranks',$id_rank);
        if (!$form->check()) {
            jMessage::add(jLocale::get('hfnuadmin~rank.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action='hfnuadmin~default:ranks';
            return $rep;
        }
        
        $dao = jDao::get('havefnubb~ranks');
        
        $record = $dao->get($id_rank);
        $record->rank_name = $form->getData('rank_name');
        $record->rank_limit = $form->getData('rank_limit');
        
        $dao->update($record);
        jMessage::add(jLocale::get('hfnuadmin~rank.rank.modified'),'ok');
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~default:ranks';
        return $rep;
    }

    function delete() {
		$id_rank = (integer) $this->param('id_rank');
	
		$dao = jDao::get('havefnubb~ranks');        
        $dao->delete($id_rank);
        
        jMessage::add(jLocale::get('hfnuadmin~rank.rank.deleted'),'ok');
        
        $rep = $this->getResponse('redirect');
        $rep->action='hfnuadmin~default:ranks';
        return $rep;        
    }   
    
}   