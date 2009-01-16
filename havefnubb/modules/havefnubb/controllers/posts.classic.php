<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postsCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=>array('auth.required'=>false),
        'add'	=>array('auth.required'=>true),
		'edit'	=>array('auth.required'=>true),
        'delete'=>array('auth.required'=>true),
        'quote'	=>array('auth.required'=>true),
        'view' 	=>array('history.add'=>true)
    );
    
    function view() {
        $id_post = (int) $this->param('id_post');
        if ($id_post == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
        
        $dao = jDao::get('posts');        
        $post = $dao->get($id_post);
        
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($post->subject) );
        
        // let's update the viewed counter
        $post->viewed = $post->viewed +1;
        $dao->update($post);
        
        $posts = $dao->findChildByIdPost($id_post);
        $posts_forum = $dao->findForumByIdPost($id_post);
        
        $dao = jDao::get('forum');
        $forum = $dao->get($posts_forum->id_forum);

		$daoCategory = jDao::get('category');
        // find category name for the current forum
		$category = $daoCategory->get($forum->id_cat);
        
        $tpl = new jTpl();
        $tpl->assign('posts',$posts);
        $tpl->assign('forum',$forum);
        $tpl->assign('category',$category);
        
        $rep = $this->getResponse('html');
        $rep->title = $post->subject;
        
        $rep->body->assign('MAIN', $tpl->fetch('postview'));
        return $rep;
    }
    
    function add () {
		
		$id_forum = (int) $this->param('id_forum');
		// invalid forum id
		if ($id_forum == 0) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;
		}
		
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
		
        $daoForum = jDao::get('havefnubb~forum');
        // find info for the current forum
        $forum = $daoForum->get($id_forum);

		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}

		$daoCategory = jDao::get('havefnubb~category');
        // find category name for the current forum
		$category = $daoCategory->get($forum->id_cat);
		
		$form = jForms::create('havefnubb~posts');
		$form->setData('id_forum',$id_forum);
		$form->setData('id_user',$user->id);
		$form->setData('id_post',0);
		
        $rep = $this->getResponse('html');		
		$rep->title = jLocale::get("havefnubb~post.form.new.message");
		
		#set the needed parameters to the template      
        $tpl = new jTpl();
        $tpl->assign('id_post', null);
        $tpl->assign('previewtext', null);
		$tpl->assign('form', $form);
		$tpl->assign('forum', $forum);
		$tpl->assign('id_post', 0);
		$tpl->assign('category', $category);		
		$tpl->assign('heading',jLocale::get('havefnubb~post.form.new.message'));
        $rep->body->assign('MAIN', $tpl->fetch('post'));
        return $rep;		
    }

    function edit () {
		
		$id_forum = (int) $this->param('id_forum');
		$id_post = (int) $this->param('id_post');
		$id_user = (int) $this->param('id_user');
		
		// invalid forum id
		if ($id_forum == 0 or $id_post == 0 or $id_user == 0 ) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;
		}
		
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
		
        $daoForum = jDao::get('havefnubb~forum');
        // find info for the current forum
        $forum = $daoForum->get($id_forum);

		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}

		$daoCategory = jDao::get('havefnubb~category');
        // find category name for the current forum
		$category = $daoCategory->get($forum->id_cat);
		
		$form = jForms::get('havefnubb~posts',$id_forum);
		$form->setData('id_forum',$id_forum);
		$form->setData('id_user',$user->id);
		$form->setData('id_post',$id_post);
		
        $rep = $this->getResponse('html');		
		$rep->title = jLocale::get("havefnubb~post.form.new.message");		
		#set the needed parameters to the template      
        $tpl = new jTpl();
        $tpl->assign('id_post', null);
        $tpl->assign('previewtext', null);
		$tpl->assign('form', $form);
		$tpl->assign('forum', $forum);
		$tpl->assign('id_post', 0);
		$tpl->assign('category', $category);		
		$tpl->assign('heading',jLocale::get('havefnubb~post.form.edit.message'));
        $rep->body->assign('MAIN', $tpl->fetch('post'));
        return $rep;	
    }
    
	
	function save() {
		
		$id_forum 	= (int) $this->param('id_forum');
		$id_post 	= (int) $this->param('id_post');
		$id_user 	= (int) $this->param('id_user');
		
		#check the datas / form ...
		if ($id_post == 0)
			$form = jForms::fill('havefnubb~posts');
		else
			$form = jForms::fill('havefnubb~posts',$id_post);

		$submit = $form->getData('validate');
		
		if($submit == 'cancel') {
			$rep 		 = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';	
            return $rep;
		}

		#.. if the data are not ok, return to the form and display errors messages form
		if (!$form->check()) {            
			$rep 		 = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';	
			return $rep;
		}

		#.. if the data are ok ; we get them !
		$subject	= $form->getData('subject');
		$message 	= $form->getData('message');
		
        #we click on Save : so we record the datas
        if ($submit == 'save' ) {
            #CreateRecord object
            $dao = jDao::get('havefnubb~posts');
            $record = jDao::createRecord('havefnubb~posts');
            
            $record->subject	= $subject;
            $record->message	= $message;
            
            # store the datas		
            #we never 'update' a page ; we always add one and 'version'ing it.
            $dao->insert($record);
			$rep 		 = $this->getResponse('redirect');
			$rep->params = array('id'=>$id_forum);
			$rep->action ='havefnubb~forum:index';
			return $rep;
        }
        
        #We click on Preview :
        #so :
        #1) we parse the submitted text
        #2) we return to the page to show the render of the given text
        else {

            #set the needed parameters to the template
            $tpl = new jTpl();
            $tpl->assign('id_post', $id_post);
            $tpl->assign('previewsubject', htmlentities($form->getData('subject')));
			$tpl->assign('previewtext', htmlentities($form->getData('message')));
            
            $rep = $this->getResponse('html');
            $rep->title = jLocale::get('havefnubb~post.form.edit.message');
			$tpl->assign('heading',jLocale::get('havefnubb~post.form.edit.message'));
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~post'));
            return $rep;            
        }		
	}
	
    function delete() {
        
    }
    
    function quote() {
        
    }
}

