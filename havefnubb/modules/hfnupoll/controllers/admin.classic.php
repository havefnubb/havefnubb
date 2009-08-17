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
        'ans_wizard1' => array( 'jacl2.right'=>'hfnu.admin.poll.add'),
        'ans_wizard2' => array( 'jacl2.right'=>'hfnu.admin.poll.add'),
        'answersave' => array( 'jacl2.right'=>'hfnu.admin.poll.add'),
        'edit'  => array( 'jacl2.right'=>'hfnu.admin.poll.edit'),
        'delete'=> array( 'jacl2.right'=>'hfnu.admin.poll.delete'),
        'saveadd'=> array( 'jacl2.right'=>'hfnu.admin.poll.add'),
        'saveedit'=> array( 'jacl2.right'=>'hfnu.admin.poll.edit'),
        'savedelete'=> array( 'jacl2.right'=>'hfnu.admin.poll.delete'),
    );
    
    public function index() {
        $tpl = new jTpl();

        //initializing of the Token
        $token = jClasses::getService("havefnubb~hfnutoken");
        $token->setHfnuToken();
        
        $form = jForms::create('hfnupoll~poll_add');
        
        $dao = jDao::get('hfnupoll~poll');
        $polls = $dao->findAll();

        $tpl->assign('hfnutoken',$token->getHfnuToken());		        
        $tpl->assign('polls',$polls);
        $tpl->assign('form',$form);
		
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', $tpl->fetch('hfnupoll~poll'));
		$rep->body->assign('selectedMenuItem','poll_list');
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

        $rep = $this->getResponse('redirect');
        $rep->action='hfnupoll~admin:index';
        return $rep;     
    }
    
    public function saveedit() {
        if ($this->param('validate') == jLocale::get('hfnupoll~poll.saveBt')) {
            $id_poll 	= $this->param('id_poll');
            $question 	= $this->param('question');
            $status 	= $this->param('status');
            
            $hfnutoken  = (string) $this->param('hfnutoken');
            //let's check if we have a valid token in our form
            $token = jClasses::getService("havefnubb~hfnutoken");       
            $token->checkHfnuToken($hfnutoken);

            if (count($id_poll) == 0) {
                jMessage::add(jLocale::get('hfnupoll~poll.invalid.datas'),'error');
                $rep = $this->getResponse('redirect');
                $rep->action='hfnupoll~admin:index';
                return $rep;                
            } 			
            
            $dao = jDao::get('hfnupoll~poll');

            foreach ($id_poll as $thisId) {
                $record 			= $dao->get( (int) $id_poll[$thisId]);
                $record->question	= (string) htmlentities($question[$id_poll[$thisId]]);
                $record->status	    = (int) $status[$id_poll[$thisId]];
                $dao->update($record);
            }
          
            jMessage::add(jLocale::get('hfnupoll~poll.updated'),'ok');
            
        }
            
        $rep = $this->getResponse('redirect');
        $rep->action='hfnupoll~admin:index';
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
    
    public function ans_wizard1() {
        $id = $this->intParam('id_poll');
        if ($id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action='hfnupoll~admin:index';
            return $rep; 
        }        
        $tpl = new jTpl();
        
        $dao = jDao::get('hfnupoll~poll_answer');               
        $form = jForms::create('hfnupoll~ans_wizard1');
        $form->setData('id_poll',$id);
        
        $rep = $this->getResponse('html');
        $tpl->assign('form',$form);
        $rep->body->assign('MAIN', $tpl->fetch('hfnupoll~poll_answers1'));
		$rep->body->assign('selectedMenuItem','poll_list');
        return $rep;        
    }
    
    public function ans_wizard2() {
        if ($this->param('validate') == jLocale::get('hfnupoll~poll.next.step')) {
            $id         = $this->intParam('id_poll');
            $nb_answers = $this->intParam('nb_answer');
            
            if ($id == 0 ) {
                $rep = $this->getResponse('redirect');
                $rep->action='hfnupoll~admin:index';
                return $rep; 
            }
            
            $tpl = new jTpl();
            //initializing of the Token
            $token = jClasses::getService("havefnubb~hfnutoken");
            $token->setHfnuToken();
            $tpl->assign('hfnutoken',$token->getHfnuToken());
            
            $rep = $this->getResponse('html');
            $tpl->assign('id_poll',$id);
            $tpl->assign('nb_answers',$nb_answers);
            $rep->body->assign('MAIN', $tpl->fetch('hfnupoll~poll_answers2'));
            $rep->body->assign('selectedMenuItem','poll_list');
            return $rep;
        }
        else {
            $rep = $this->getResponse('redirect');
            $rep->action='hfnupoll~admin:index';
            return $rep;             
        }
    }
    
    public function answersave() {
        if ($this->param('validate') == jLocale::get('hfnupoll~poll.saveBt')) {
            $id_poll 	= $this->intParam('id_poll');
            $nb_answers	= $this->intParam('nb_answers');
            $answers 	= $this->param('answers');

            $hfnutoken  = (string) $this->param('hfnutoken');
            //let's check if we have a valid token in our form
            $token = jClasses::getService("havefnubb~hfnutoken");       
            $token->checkHfnuToken($hfnutoken);
            
            // check if the number of answers and the number of entered data are the same
            if ( $id_poll  == 0 or count($answers) == 0 or ($nb_anwsers > count($answers)) ) {
                jMessage::add(jLocale::get('hfnupoll~poll.invalid.datas'),'error');
                $rep = $this->getResponse('redirect');
                $rep->action='hfnupoll~admin:index';
                return $rep;                
            } 			
            
            $dao = jDao::get('hfnupoll~poll_answer');

            for ($i = 0 ; $i < count ($answers) ; $i++ ) {
                $record->answer	= (string) htmlentities($answers[$i]);
                $record->id_poll = $id_poll;
                $record->id_user = jAuth::getUserSession()->id;
                $dao->insert($record);
            }
          
            jMessage::add(jLocale::get('hfnupoll~poll.answers.added'),'ok');
            
        }
            
        $rep = $this->getResponse('redirect');
        $rep->action='hfnupoll~admin:index';
        return $rep;     
        
    }

}


