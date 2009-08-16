<?php
/**
* @package   havefnubb
* @subpackage hfnupoll
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class adminCtrl extends jController {
    /**
    * status = 1 poll to be complete by adding answer
    * status = 2 poll completed and opened
    * status = 3 poll closed
    */
    public $pluginParams = array(
        '*'		=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),
		'index' => array( 'jacl2.right'=>'hfnu.admin.poll.list'),
		'add'   => array( 'jacl2.right'=>'hfnu.admin.poll.add'),
        'edit'  => array( 'jacl2.right'=>'hfnu.admin.poll.edit'),
        'delete'=> array( 'jacl2.right'=>'hfnu.admin.poll.delete'),
        'saveadd'=> array( 'jacl2.right'=>'hfnu.admin.poll.add'),
        'saveedit'=> array( 'jacl2.right'=>'hfnu.admin.poll.edit'),
        'savedelete'=> array( 'jacl2.right'=>'hfnu.admin.poll.delete'),
    );
    
    public function index() {
        $tpl = new jTpl();
        
        $form = jForms::create('hfnupoll~poll_add');
        
        $dao = jDao::get('hfnupoll~poll');
        $polls = $dao->findAll();
        
        $tpl->assign('polls',$polls);
        $tpl->assign('form',$form);
		
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnupoll~poll'));
		$rep->body->assign('selectedMenuItem','poll_list');
        return $rep;
    }
   
    public function delete () {
        $rep = $this->getResponse('redirect');
        $id = $this->intParam('id_poll');
        if ($id > 0 ) {
            $dao = jDao::get('hfnupoll~poll');
            $dao->delete($id);
            jMessage::add(jLocale::get('hfnupoll~poll.deleted'),'ok');
        }
        else {
            jMessage::add(jLocale::get('hfnupoll~poll.invalid.datas'),'error');
        }
        $rep->action='hfnupoll~admin:index';
        return $rep;        
    }
    
    public function savecreate() {
        $id = $this->intParam('id_poll');
        if ($id == 0) {
            $form = jForms::get('hfnupoll~poll_add');
            if ($form->check()) {
                jMessage::add(jLocale::get('hfnupoll~poll.invalid.datas'),'error');
                $rep = $this->getResponse('redirect');
                $rep->action='hfnupoll~admin:index';
                return $rep;
            }
                   
            if ($this->param('validate') == jLocale::get('hfnupoll~poll.saveBt')) {
    
                $dao = jDao::get('hfnupoll~poll');
                
                $form = jForms::fill('hfnupoll~poll_add');
                
                $record = jDao::createRecord('hfnupoll~poll');        
                $record->question = htmlentities($form->getData('question'));
                $record->date_created = time();
                $record->status = 1; // to add answer 
               
                $dao->insert($record);
    
                jForms::destroy('hfnupoll~poll_add');
                
                jMessage::add(jLocale::get('hfnupoll~poll.added'),'ok');
            }
        }
        else {
            if ($this->param('validate') == jLocale::get('hfnupoll~poll.saveBt')) {
                
                $form = jForms::fill('hfnupoll~poll_edit',$id);
                
                $dao = jDao::get('hfnupoll~poll');
                $dao->get($id);

                $record->question = htmlentities($form->getData('question'));
                $record->date_created = time();
                $record->status = $this->intParam('status'); 
               
                $dao->update($record);
    
                jForms::destroy('hfnupoll~poll_add');
                
                jMessage::add(jLocale::get('hfnupoll~poll.updated'),'ok');
            }
            
        }
        $rep = $this->getResponse('redirect');
        $rep->action='hfnupoll~admin:index';
        return $rep;     
    }
    
    public function edit() {
        $tpl = new jTpl();
        $id = $this->intParam('id_poll');
        if ($id > 0) {
            $form = jForms::create('hfnupoll~poll_edit',$id);
            
            $dao = jDao::get('hfnupoll~poll');
            $poll = $dao->get($id);
            
            $tpl->assign('poll',$poll);
            $tpl->assign('form',$form);
            
            $rep = $this->getResponse('html');
            $rep->body->assign('MAIN', $tpl->fetch('hfnupoll~poll_edit'));
            $rep->body->assign('selectedMenuItem','poll_list');
            return $rep;
        }
        else {
            $rep = $this->getResponse('redirect');
            $rep->action='hfnupoll~admin:index';        
            return $rep;    
        }
    }
       
}


