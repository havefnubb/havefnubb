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
        'lists'	=>array('auth.required'=>false),
		'add'	=>array('auth.required'=>true),
		'edit'	=>array('auth.required'=>true),
        'delete'=>array('auth.required'=>true),
        'quote'	=>array('auth.required'=>true),
        'reply'	=>array('auth.required'=>true),
        'savereply'	=>array('auth.required'=>true),        
        'save'	=>array('auth.required'=>true),
        
        'lists'	=>array('history.add'=>true),
        'view' 	=>array('history.add'=>true)
    );
	
	// main list of all posts of a given forum ($id)	
    function lists() {
        global $HfnuConfig;
        $id = (int) $this->param('id');
        if ($id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }

		// crumbs infos
		list($forum,$category) = $this->getCrumbs($id);
		        
        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => ' ',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("havefnubb~main.common.pagelinks.end"),
                      'area-size'   => 5);
        // 1- get the offset parm if exist
        $page = 0;
        if ( $this->param('page') > 0 )
            $page = (int) $this->param('page');
        // 2- limit per page 
        $nbPostPerPage = 0;
        $nbPostPerPage = $HfnuConfig->getValue('posts_per_page','board');
              
        $daoPost = jDao::get('havefnubb~posts');
        // 3- total number of posts
        $nbPosts = $daoPost->findNbOfPostByForumId($id);
        // 4- get the posts of the current forum, limited by point 1 and 2
        $posts = $daoPost->findByIdForum($id,$page,$nbPostPerPage);

        // change the label of the breadcrumb
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', $forum->forum_name . ' - ' . jLocale::get('havefnubb~main.common.page') . ' ' .($page+1));
		
        $rep = $this->getResponse('html');
        if ($page == 0)
            $rep->title = $forum->forum_name;
        else
            $rep->title = $forum->forum_name . ' - ' . jLocale::get('havefnubb~main.common.page') . ' ' .($page+1) ;
			
        $tpl = new jTpl();        
        // B- Using the collected datas
        $tpl->assign('tableclass','forumView');
        // 1- the posts 
        $tpl->assign('posts',$posts);		
        // 2- the forum		
        $tpl->assign('forum',$forum);
		// 3 - the category
		$tpl->assign('category',$category);
        // 4- vars for pagelinks
        // see A-1 / A-2 / A-3
        $tpl->assign('page',$page);                
        $tpl->assign('nbPostPerPage',$nbPostPerPage);
        $tpl->assign('nbPosts',$nbPosts);
        $tpl->assign('id',$id);
        $tpl->assign('properties',$properties);
        
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.list'));
        return $rep;        
    }        

	//display the thread of the given post 
    function view() {
        global $HfnuConfig;
        $id_post = (int) $this->param('id_post');
        if ($id_post == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
        
        // let's update the viewed counter
        $dao = jDao::get('havefnubb~posts'); 
        $post = $dao->get($id_post);   
        $post->viewed = $post->viewed +1;
        $dao->update($post);
        
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', $post->subject );        
		// crumbs infos
		list($forum,$category) = $this->getCrumbs($post->id_forum);
		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}              
		
        $rep = $this->getResponse('html');
        
        $page = 0;
        if ( $this->param('page') )
            $page = (int) $this->param('page');
            
        $tpl = new jTpl();				
        $tpl->assign('id_post',$id_post);
        $tpl->assign('forum',$forum);
		$tpl->assign('category',$category);
        $tpl->assign('page',$page);
        $tpl->assign('subject',$post->subject);
        $rep->title = $post->subject;                
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.view'));
        return $rep;
    }
    
    // display the add 'blank' form 
    function add () {		
		$id_forum = (int) $this->param('id_forum');
		
		$id_post = 0;

		// invalid forum id
		if ($id_forum == 0) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;
		}
		
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
			
		// crumbs infos
		list($forum,$category) = $this->getCrumbs($id_forum);
		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}
		
		$form = jForms::create('havefnubb~posts',$id_post);
		$form->setData('id_forum',$id_forum);
		$form->setData('id_user',$user->id);
		$form->setData('id_post',$id_post);
		
        $rep = $this->getResponse('html');		
		$rep->title = jLocale::get("havefnubb~post.form.new.message");
		
		//set the needed parameters to the template      
        $tpl = new jTpl();
        $tpl->assign('id_post', 0);
		$tpl->assign('id_forum', $id_forum);
        $tpl->assign('previewtext', null);
		$tpl->assign('previewsubject',null);
		$tpl->assign('form', $form);
		$tpl->assign('forum', $forum);
		$tpl->assign('category', $category);		
		$tpl->assign('heading',jLocale::get('havefnubb~post.form.new.message'));
		$tpl->assign('submitAction','havefnubb~posts:save');
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
        return $rep;		
    }

    // display the edit form with the corresponding selected post
    function edit () {		
		$id_post = (int) $this->param('id_post');
		
		if ($id_post == 0 ) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;
		}
		
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
		
		$daoPost = jDao::get('havefnubb~posts');
		$post = $daoPost->get($id_post);

		// crumbs infos
		list($forum,$category) = $this->getCrumbs($post->id_forum);		
		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}
		
		$form = jForms::create('havefnubb~posts',$id_post);
		$form->initFromDao("havefnubb~posts");
				
		$form->setData('id_forum',$post->id_forum);
		$form->setData('id_user',$user->id);
		$form->setData('id_post',$id_post);
		
        $rep = $this->getResponse('html');		
		$rep->title = jLocale::get("havefnubb~post.form.edit.message");		
		//set the needed parameters to the template      
        $tpl = new jTpl();
        $tpl->assign('id_post', $id_post);
		$tpl->assign('id_forum',$post->id_forum);
        $tpl->assign('previewtext', null);
		$tpl->assign('previewsubject', null);
		$tpl->assign('form', $form);
		$tpl->assign('forum', $forum);
		$tpl->assign('category', $category);		
		$tpl->assign('heading',jLocale::get('havefnubb~post.form.edit.message'));
		$tpl->assign('submitAction','havefnubb~posts:save');		
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
        return $rep;	
    }
    
    // Save the data submitted from add/edit form
	function save() {
        global $HfnuConfig;
		$id_forum = (int) $this->param('id_forum');
		$id_post = (int) $this->param('id_post');
        
        $parent_id = (int) $this->param('parent_id');
		
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);

		$submit = $this->param('validate');
		// preview ?
		if ($submit == jLocale::get('havefnubb~post.form.previewBt') ) {
			list($forum,$category) = $this->getCrumbs($id_forum);
            
			$form = jForms::fill('havefnubb~posts',$id_post);
	
			$form->setData('id_forum',$id_forum);
			$form->setData('id_user',$user->id);
			$form->setData('id_post',$id_post);
            $form->setData('parent_id',$parent_id);
			$form->setData('subject',$form->getData('subject'));
			$form->setData('message',$form->getData('message'));
			
			//set the needed parameters to the template
			$tpl = new jTpl();
            $tpl->assign('wr_engine',$HfnuConfig->getValue('forum_post_render','board'));
			$tpl->assign('id_post', $id_post);
			$tpl->assign('id_forum', $id_forum);
            $tpl->assign('id_user', $user->id);
			$tpl->assign('previewsubject', $form->getData('subject'));
			$tpl->assign('previewtext', $form->getData('message'));
			$tpl->assign('form', $form);
			$tpl->assign('forum', $forum);
			$tpl->assign('category', $category);
			
			$rep = $this->getResponse('html');
			$rep->title = jLocale::get('havefnubb~post.form.new.message');
				
			$tpl->assign('heading',jLocale::get('havefnubb~post.form.new.message'));
			$tpl->assign('submitAction','havefnubb~posts:save');
			
			$rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
			return $rep;		
			
		}
        // save ?
        elseif ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {
			$rep = $this->getResponse('redirect');
			
			if ($id_forum == 0 or $user->id == 0 ) {			
				$rep->action = 'havefnubb~default:index';	
				return $rep;
			}
			
			$form = jForms::fill('havefnubb~posts',$id_post);
	
			//.. if the data are not ok, return to the form and display errors messages form
			if (!$form->check()) {            
				$rep->action = 'havefnubb~posts:lists';
				$rep->param = array('id'=>$id_forum);
				return $rep;
			}
	
			//.. if the data are ok ; we get them !
			$subject	= $form->getData('subject');
			$message 	= $form->getData('message');
			
			//CreateRecord object
			$dao = jDao::get('havefnubb~posts');
			
			if ($id_post == 0) 
				$record = jDao::createRecord('havefnubb~posts');
			else
				$record = $dao->get($id_post);
			
			$record->subject	= $subject;
			$record->message	= $message;
			
			// store the datas
			// if id_post = 0 then
			// it's an adding 			
			if ($id_post == 0 ) {			
				$record->id_post  	= $id_post;
				$record->id_user 	= $user->id;
				$record->id_forum 	= $id_forum;

				$record->parent_id  = 0;
				$record->status		= 1;
				$record->date_created = date('Y-m-d H:i:s');
				$record->date_modified = date('Y-m-d H:i:s');
				$record->viewed		= 0;
				
				$dao->insert($record);
				$record->parent_id = $record->id_post;
				$id_post = $record->id_post;
				
			} else {
				$record->date_modified = date('Y-m-d H:i:s');
			}
			// otherwise it's an update
			// in all case we have to
			// update as we store the last insert id in the parent_id column
			$dao->update($record);
			jForms::destroy('havefnubb~posts', $id_post);
			$rep->params = array('id_post'=>$id_post);
			$rep->action ='havefnubb~posts:view';
			return $rep;			
		}
		else {
			$rep = $this->getResponse('redirect');
			$rep->action ='havefnubb~default:index';
			return $rep;						
		}		
	}
	
	//reply to a given post (from the parent_id)
    function reply() {
        global $HfnuConfig;
        $parent_id = (int) $this->param('id_post');
        $id_post = (int) $this->param('id_post');
        if ($parent_id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
        //get the info of the current user who's replying
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
        
		$daoPost = jDao::get('havefnubb~posts');
		$post = $daoPost->get($parent_id);              
		// crumbs infos
		list($forum,$category) = $this->getCrumbs($post->id_forum);
		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}
        
        $form = jForms::create('havefnubb~posts',$parent_id);
		$form->setData('id_forum',$post->id_forum);
		$form->setData('id_user',$user->id);
		$form->setData('id_post',0);
        $form->setData('parent_id',$id_post);
        
		//set the needed parameters to the template              
        $tpl = new jTpl();        
        $tpl->assign('forum',$forum);
        $tpl->assign('id_post',$id_post);
        $tpl->assign('parent_id',$parent_id);
        $tpl->assign('category',$category);
		$tpl->assign('id_forum', $forum->id_forum);
        $tpl->assign('previewtext', null);
		$tpl->assign('previewsubject',null);
		$tpl->assign('form', $form);
		$tpl->assign('forum', $forum);
		$tpl->assign('category', $category);		
		$tpl->assign('heading',jLocale::get("havefnubb~post.form.reply.message") . ' ' . $post->subject);
		$tpl->assign('submitAction','havefnubb~posts:savereply');
        
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.reply.message") . ' ' . $post->subject;                
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.reply'));
        return $rep;		
    }

    // save the datas posted from the reply form
	function savereply() {
        global $HfnuConfig;
		$id_forum   = (int) $this->param('id_forum');
		$id_post    = (int) $this->param('id_post');       
        $parent_id  = (int) $this->param('parent_id');
		
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);

		$submit = $this->param('validate');
		// preview ?
		if ($submit == jLocale::get('havefnubb~post.form.previewBt') ) {
			list($forum,$category) = $this->getCrumbs($id_forum);
            
			$form = jForms::fill('havefnubb~posts',$parent_id);
	
			$form->setData('id_forum',$id_forum);
			$form->setData('id_user',$user->id);
			$form->setData('id_post',$id_post);
            $form->setData('parent_id',$parent_id);
			$form->setData('subject',$form->getData('subject'));
			$form->setData('message',$form->getData('message'));
			
			//set the needed parameters to the template
			$tpl = new jTpl();
            $tpl->assign('wr_engine',$HfnuConfig->getValue('forum_post_render','board'));
			$tpl->assign('id_post', 0);
            $tpl->assign('parent_id', $parent_id);
			$tpl->assign('id_forum', $id_forum);
			$tpl->assign('previewsubject', $form->getData('subject'));
			$tpl->assign('previewtext', $form->getData('message'));
			$tpl->assign('form', $form);
			$tpl->assign('forum', $forum);
			$tpl->assign('category', $category);
			
			$rep = $this->getResponse('html');
			$rep->title = jLocale::get('havefnubb~post.form.reply.message') . ' ' . $form->getData('subject');
				
			$tpl->assign('heading',jLocale::get('havefnubb~post.form.reply.message') . ' ' . $form->getData('subject'));
			$tpl->assign('submitAction','havefnubb~posts:savereply');
			
			$rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.reply'));
			return $rep;		
			
		}
        // save ?
        elseif ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {
			$rep = $this->getResponse('redirect');
			
			if ($id_forum == 0 or $user->id == 0 ) {			
				$rep->action = 'havefnubb~default:index';	
				return $rep;
			}
			
			$form = jForms::fill('havefnubb~posts',$parent_id);
	
			//.. if the data are not ok, return to the form and display errors messages form
			if (!$form->check()) {            
				$rep->action = 'havefnubb~posts:lists';
				$rep->param = array('id'=>$id_forum);
				return $rep;
			}
	
			//.. if the data are ok ; we get them !
			$subject	= $form->getData('subject');
			$message 	= $form->getData('message');
			
			//CreateRecord object
			$dao = jDao::get('havefnubb~posts');		
			$record = jDao::createRecord('havefnubb~posts');
			
            // let's create the record of this reply
			$record->subject	= $subject;
			$record->message	= $message;			

			$record->id_post  	= 0;
			$record->id_user 	= $user->id;
			$record->id_forum 	= $id_forum;

			$record->parent_id  = $parent_id;
			$record->status		= 1;
			$record->date_created = date('Y-m-d H:i:s');
			$record->date_modified = date('Y-m-d H:i:s');
			$record->viewed		= 0;
				
			$dao->insert($record);
			
			jForms::destroy('havefnubb~posts', $parent_id);
			$rep->params = array('id_post'=>$parent_id);
			$rep->action ='havefnubb~posts:view';
			return $rep;			
		}
		else {
			$rep = $this->getResponse('redirect');
			$rep->action ='havefnubb~default:index';
			return $rep;						
		}

		
	}    
	// @TODO 
    function quote() {       
        global $HfnuConfig;
        $parent_id = (int) $this->param('parent_id');
        $id_post = (int) $this->param('id_post');
        if ($parent_id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
        //get the info of the current user who's replying
		$daoUser = jDao::get('havefnubb~member');
		$me = $daoUser->getByLogin( jAuth::getUserSession ()->login);
        
        
		$daoPost = jDao::get('havefnubb~posts');
		$post = $daoPost->get($id_post);
        if (!$post) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        } 
        $author = $daoUser->getById( $post->id_user);
        
		// crumbs infos
		list($forum,$category) = $this->getCrumbs($post->id_forum);
		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}
        
        $form = jForms::create('havefnubb~posts',$parent_id);
        $form->initFromDao('havefnubb~posts');
		$form->setData('id_forum',$post->id_forum);
		$form->setData('id_user',$me->id);
		$form->setData('id_post',0);
        $form->setData('parent_id',$parent_id);
        
        
        $newMessage = ">".preg_replace("/\\n/","\n>",$post->message);
        
		$quoteMessage = ">".$author->login.' ' .
						jLocale::get('havefnubb~post.form.author.said') .
                        "\n".
						$newMessage;

        $form->setData('message',$quoteMessage);
        
		//set the needed parameters to the template              
        $tpl = new jTpl();        
        $tpl->assign('forum',$forum);
        $tpl->assign('id_post',0);
        $tpl->assign('parent_id',$parent_id);
        $tpl->assign('category',$category);
		$tpl->assign('id_forum', $forum->id_forum);
        $tpl->assign('previewtext', null);
		$tpl->assign('previewsubject',null);
		$tpl->assign('form', $form);
		$tpl->assign('forum', $forum);
		$tpl->assign('category', $category);		
		$tpl->assign('heading',jLocale::get("havefnubb~post.form.quote.message") . ' ' . $post->subject);
		$tpl->assign('submitAction','havefnubb~posts:savereply');
        
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.quote.message") . ' ' . $post->subject;                
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.reply'));
        return $rep;		
    }    
	
    function delete() {
        
    }

	private function getCrumbs($id_forum) {
		
		// get info to display them in the breadcrumb
        $daoForum = jDao::get('havefnubb~forum');
        // find info for the current forum
        $forum = $daoForum->get($id_forum);
		
		
		$daoCategory = jDao::get('havefnubb~category');
		// find category name for the current forum
		$category = $daoCategory->get($forum->id_cat);
		
		$info = array($forum,$category);
		
		return $info;
	}
}
