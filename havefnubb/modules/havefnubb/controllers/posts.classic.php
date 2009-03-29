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
        '*'		=> array( 'auth.required'=>false,
						  'hfnu.check.installed'=>true),
		
        'goto'	=> array( 'jacl2.right'=>'hfnu.forum.goto'),
		
		'add'	=> array('auth.required'=>true,'flood.same.ip'=>true),
		'edit'	=> array('auth.required'=>true,'flood.editing'=>true),
		'quote'	=> array('auth.required'=>true,'flood.editing'=>true),
		'reply'	=> array('auth.required'=>true,'flood.editing'=>true),
		
        'lists'	=> array( 'history.add'=>true),
        'view' 	=> array( 'history.add'=>true),		
		
   );	

	// main list of all posts of a given forum ($id_forum)	
    function lists() {
        global $HfnuConfig;
        $id_forum = (int) $this->param('id_forum');
		
		if ( ! jAcl2::check('hfnu.posts.list','forum'.$id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
        if ($id_forum == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }

		// crumbs infos
		list($forum,$category) = $this->getCrumbs($id_forum);
		        
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
		
		if ($page < 0) $page = 0;
		
        // 2- limit per page 
        $nbPostPerPage = 0;
        $nbPostPerPage = (int) $HfnuConfig->getValue('posts_per_page');
              
        $daoPost = jDao::get('havefnubb~posts');
        // 3- total number of posts
        $nbPosts = $daoPost->countPostsByForumId($id_forum);
        // 4- get the posts of the current forum, limited by point 1 and 2
        $posts = $daoPost->findByIdForum($id_forum,$page,$nbPostPerPage);

		// check if we have found record ; 
		if ($posts->rowCount() == 0) {
			$posts = $daoPost->findByIdForum($id_forum,0,$nbPostPerPage);
			$page = 0;
		}
		
        // change the label of the breadcrumb
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', $forum->forum_name . ' - ' . jLocale::get('havefnubb~main.common.page') . ' ' .($page+1));
		
        $rep = $this->getResponse('html');
		
		//build the rss link in the header of the html page
		$url = jUrl::get('havefnubb~posts:rss', array('ftitle'=>$forum->forum_name,
													'id_forum'=>$forum->id_forum	));
		$rep->addHeadContent('<link rel="alternate" type="application/rss+xml" title="'.$forum->forum_name.'" href="'.$url.'" />');
		// end rss link 
			
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
        $tpl->assign('id_forum',$id_forum);
		$tpl->assign('lvl',$forum->child_level);
        $tpl->assign('properties',$properties);
			
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.list'));
        return $rep;        
    }        

	// display the first post + call zone posts_replies in template to display all the thread
	// Method 1 : default usage : list all messages of a thread (id_post known)
	// Method 2 : display a message from anywhere in the thread (id_post + parent_id known)
    function view() {
		
		global $HfnuConfig;
        $id_post 	= (int) $this->param('id_post');
		$parent_id 	= (int) $this->param('parent_id');

        $dao = jDao::get('havefnubb~posts'); 
        $post = $dao->get($id_post);		

        if ($id_post == 0 or $post === false) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
        }
                		
		if ( ! jAcl2::check('hfnu.posts.view','forum'.$post->id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
		$goto = 0;
		if ($parent_id > 0) {
			// the number of post between the current post_id and the parent_id
			$child = (int) $dao->countReplies($post->id_post,$post->parent_id);
	
			$nbRepliesPerPage = (int) $HfnuConfig->getValue('replies_per_page');			
			// calculate the offset of this id_post
			$goto = (ceil ($child/$nbRepliesPerPage) * $nbRepliesPerPage) - $nbRepliesPerPage;
			if ($goto < 0 ) $goto = 0;
			// replacing the id_post by parent_id for the posts_replies.zones.php
			$id_post = $parent_id;	
		}		
		
		// let's update the viewed counter
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
			
        if ($goto > 0 ) $page = $goto;
		
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
		
		if ( ! jAcl2::check('hfnu.posts.create','forum'.$id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
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
		$tpl->assign('reply',0);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
        return $rep;		
    }

    // display the edit form with the corresponding selected post
    function edit () {
		
		global $HfnConfig;
		
		$id_post = (int) $this->param('id_post');
			
		if ($id_post == 0 ) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;
		}
				
		$daoPost = jDao::get('havefnubb~posts');
		$post = $daoPost->get($id_post);
        
		if ( ! jAcl2::check('hfnu.posts.edit','forum'.$post->id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}

		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);    
        
        $srvTags = jClasses::getService("jtags~tags");
        $tagsArray = $srvTags->getTagsBySubject('forumscope', $id_post);
		$tags= '';
		for ($i = 0; $i < count($tagsArray) ; $i ++) {			
			$tags .= $tagsArray[$i] . ',';
		}     
        
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
		$form->setData('tags', $tags);		
		
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
		
		$id_forum = (int) $this->param('id_forum');

		if ( ! jAcl2::check('hfnu.posts.create','forum'.$id_forum) or 
			 ! jAcl2::check('hfnu.posts.edit','forum'.$id_forum)
			) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
		$id_post 	= (int) $this->param('id_post');
		$parent_id 	= (int) $this->param('parent_id');		
		$tags 		= $this->param("tags", false);                
		
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
			$tpl->assign('id_post', $id_post);
			$tpl->assign('id_forum', $id_forum);
            $tpl->assign('user', $user);
			$tpl->assign('signature', $user->member_comment);
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
				$rep->param = array('id_forum'=>$id_forum);
				return $rep;
			}
	
			//.. if the data are ok ; we get them !
			$subject	= $form->getData('subject');
			$message 	= $form->getData('message');
			
			//CreateRecord object
			$dao = jDao::get('havefnubb~posts');						
			
			if ($id_post == 0) {
				jEvent::notify('HfnuPostBeforeSave',array('id'=>$id_post));
				$record = jDao::createRecord('havefnubb~posts');
			}
			else {
				jEvent::notify('HfnuPostBeforeUpdate',array('id'=>$id_post));
				$record = $dao->get($id_post);
			}
			
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
				$record->poster_ip = $_SERVER['REMOTE_ADDR'];
				
				$dao->insert($record);
				$record->parent_id = $record->id_post;
				$id_post = $record->id_post;
				$parent_id = $record->parent_id;
				
				jEvent::notify('HfnuPostAfterInsert',array('id'=>$id_post));
				
				jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));
				
				// let's update the counter of posts in member table
				$daoUser = jDao::get('havefnubb~member');			
				// increment the nb_msg of the poster
				$daoUser->updateNbMsg($user->id,$user->nb_msg +1);
				// let's update the last time the user "touch" a post
				$daoUser->updateLastPostedMsg($user->id,time());
			} else {
				$record->date_modified = date('Y-m-d H:i:s');
				// let's update the last time the user "touch" a post
				$daoUser = jDao::get('havefnubb~member');
				$daoUser->updateLastPostedMsg($user->id,time());
			}
			// otherwise it's an update
			// in all case we have to
			// update as we store the last insert id in the parent_id column
			$dao->update($record);
			
			jEvent::notify('HfnuPostAfterSave',array('id'=>$id_post));
			
			jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));
			
			$tags = explode(",", $form->getData("tags"));

			jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'forumscope', $id_post);
			
			jForms::destroy('havefnubb~posts', $id_post);
			
			jMessage::add(jLocale::get('havefnubb~main.common.posts.saved'),'ok');
			//after editing, returning to the parent_id post !
			$daoPost = jDao::get('havefnubb~posts');
			$post = $daoPost->get($id_post);
			$rep->params = array('id_post'=>$id_post,
								 'parent_id'=>$parent_id,
								 'id_forum'=>$post->id_forum,
								 'ftitle'=>$post->forum_name,
								 'ptitle'=>$post->subject);
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
	
        $parent_id 	= (int) $this->param('id_post');
        $id_post 	= (int) $this->param('id_post');
		
        if ($parent_id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
        
		$daoPost = jDao::get('havefnubb~posts');
		$post = $daoPost->get($parent_id);

		if ( ! jAcl2::check('hfnu.posts.reply','forum'.$post->id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
        //get the info of the current user who's replying
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
		
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
		$tpl->assign('reply',1);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
        return $rep;		
    }

    // save the datas posted from the reply form
	function savereply() {
		$id_forum   = (int) $this->param('id_forum');
		
		if ( ! jAcl2::check('hfnu.posts.quote','forum'.$id_forum) or
			 ! jAcl2::check('hfnu.posts.reply','forum'.$id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
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
			$tpl->assign('id_post', 	0);
            $tpl->assign('parent_id', 	$parent_id);
			$tpl->assign('id_forum', 	$id_forum);
			$tpl->assign('previewsubject', $form->getData('subject'));
			$tpl->assign('previewtext', $form->getData('message'));
			$tpl->assign('form', 		$form);
			$tpl->assign('forum', 		$forum);
			$tpl->assign('category', 	$category);
			$tpl->assign('signature',	$user->member_comment);
			
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
			
			$daoForum = jDao::get('havefnubb~forum');			
			$forum = $daoForum->get($id_forum);
			
			$form = jForms::fill('havefnubb~posts',$parent_id);
	
			//.. if the data are not ok, return to the form and display errors messages form
			if (!$form->check()) {            
				$rep->action = 'havefnubb~posts:lists';
				$rep->param = array('id_forum'=>$id_forum);
				return $rep;
			}
	
			//.. if the data are ok ; we get them !
			$subject	= $form->getData('subject');
			$message 	= $form->getData('message');
			
			jEvent::notify('HfnuPostBeforeSaveReply',array('id'=>$id_post));
			
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
			$record->poster_ip = $_SERVER['REMOTE_ADDR'];
			$dao->insert($record);
			
			jEvent::notify('HfnuPostAfterSaveReply',array('id_post'=>$record->id_post));
			
			jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));
			
			// let's update the counter of posts in member table
			$daoUser = jDao::get('havefnubb~member');			
			// increment the nb_msg of the poster
			$daoUser->updateNbMsg($user->id,$user->nb_msg +1);
			// let's update the last time the user "touch" a post
			$daoUser->updateLastPostedMsg($user->id,time());
			
			jForms::destroy('havefnubb~posts', $parent_id);
			
			jMessage::add(jLocale::get('havefnubb~main.common.reply.added'),'ok');
			
			$rep->params = array('ftitle'=>$forum->forum_name,
								 'ptitle'=>$subject,
								 'id_forum'=>$id_forum,
								 'id_post'=>$record->id_post,
								 'parent_id'=>$record->parent_id);
			$rep->action ='havefnubb~posts:view';
			return $rep;			
		}
		else {
			$rep = $this->getResponse('redirect');
			$rep->action ='havefnubb~default:index';
			return $rep;						
		}		
	}
	
	
	// quote message
    function quote() {
		
        $parent_id 	= (int) $this->param('parent_id');
        $id_post 	= (int) $this->param('id_post');
		
        if ($parent_id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
		
		$daoPost = jDao::get('havefnubb~posts');
		$post = $daoPost->get($id_post);
        
        if (!$post) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        } 
        
		if ( ! jAcl2::check('hfnu.posts.create','forum'.$post->id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
        //get the info of the current user who's replying
		$daoUser = jDao::get('havefnubb~member');
		$me = $daoUser->getByLogin( jAuth::getUserSession ()->login);                
        
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
        $tpl->assign('forum',		$forum);
        $tpl->assign('id_post',		0);
        $tpl->assign('parent_id',	$parent_id);
        $tpl->assign('category',	$category);
		$tpl->assign('id_forum', 	$forum->id_forum);
        $tpl->assign('previewtext', null);
		$tpl->assign('previewsubject',null);
		$tpl->assign('form', 		$form);
		$tpl->assign('forum', 		$forum);
		$tpl->assign('category', 	$category);		
		$tpl->assign('heading',		jLocale::get("havefnubb~post.form.quote.message") . ' ' . $post->subject);
		$tpl->assign('submitAction','havefnubb~posts:savereply');
        
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.quote.message") . ' ' . $post->subject;                
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.reply'));
        return $rep;		
    }

	
    function delete() {
	
		$id_post 	= (integer) $this->param('id_post');
		$id_forum 	= (integer) $this->param('id_forum');
		
		if ( ! jAcl2::check('hfnu.posts.delete','forum'.$id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
		jEvent::notify('HfnuPostBeforeDelete',array('id'=>$id_post));
		
		$dao = jDao::get('havefnubb~posts');
        $dao->delete($id_post);
		
		$dao = jDao::get('havefnubb~forum');
		$forum = $dao->get($id_forum);
		
		jEvent::notify('HfnuPostAfterDelete',array('id'=>$id_post));
		
		jEvent::notify('HfnuSearchEngineDeleteContent',array('id'=>$id_post));
		
        jMessage::add(jLocale::get('havefnubb~main.common.posts.deleted'),'ok');
        $rep = $this->getResponse('redirect');		
        $rep->action='havefnubb~posts:lists';
		$rep->params=array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name);
        return $rep;		
    }

    function goto() {
        $id_forum = (int) $this->param('id_forum');
        if ($id_forum == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
		$dao = jDao::get('havefnubb~forum');
		$forum = $dao->get($id_forum);
        $rep = $this->getResponse('redirect');
        $rep->action='havefnubb~posts:lists';
		$rep->params=array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name);  
        return $rep;        
    }

	//notify something from a given post (from the parent_id)	
    function notify() {
		
        $id_post = (int) $this->param('id_post');
        if ($id_post == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }
        //get the info of the current user who's notifying
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
        
		$daoPost = jDao::get('havefnubb~posts');
		$post = $daoPost->get($id_post);              
        
		if ( ! jAcl2::check('hfnu.posts.notify','forum'.$post->id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}        
		// crumbs infos
		list($forum,$category) = $this->getCrumbs($post->id_forum);
		if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
			$rep->action = 'havefnubb~default:index';
            return $rep;			
		}		
		
        $form = jForms::create('havefnubb~notify',$id_post);
		$form->setData('id_user',$user->id);
		$form->setData('id_post',$id_post);
		$form->setData('id_forum',$post->id_forum);
        
		//set the needed parameters to the template              
        $tpl = new jTpl();        
        $tpl->assign('forum',$forum);
        $tpl->assign('id_post',$id_post);
        $tpl->assign('category',$category);
		$tpl->assign('form', $form);
		$tpl->assign('forum', $forum);
		$tpl->assign('category', $category);		
		$tpl->assign('heading',jLocale::get("havefnubb~post.form.notify.message") . ' - ' . $post->subject);
		$tpl->assign('submitAction','havefnubb~posts:savenotify');
        
        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.notify.message") . ' - ' . $post->subject;
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.notify'));
        return $rep;		
    }	

    // save the datas posted from the notify form
	function savenotify() {
		$id_post    = (int) $this->param('id_post');
		$id_forum   = (int) $this->param('id_forum');

		if ( ! jAcl2::check('hfnu.posts.notify','forum'.$id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
		}
		
		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);

		$submit = $this->param('validate');
        if ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {
			$rep = $this->getResponse('redirect');
			
			if ($id_post ==  0 or $user->id == 0  or $id_forum == 0) {			
				$rep->action = 'havefnubb~default:index';	
				return $rep;
			}
			
			$form = jForms::fill('havefnubb~notify',$id_post);
	
			//.. if the data are not ok, return to the form and display errors messages form
			if (!$form->check()) {            
				$rep->action = 'havefnubb~default:index';
				return $rep;
			}
	
			//.. if the data are ok ; we get them !
			$subject	= $form->getData('subject');
			$message 	= $form->getData('message');
			
			jEvent::notify('HfnuPostBeforeSaveNotify',array('id'=>$id_post));
			
			//CreateRecord object
			$dao = jDao::get('havefnubb~notify');		
			$record = jDao::createRecord('havefnubb~notify');
			
            // let's create the record of this reply
			$record->subject	= $subject;
			$record->message	= $message;			

			$record->id_post  	= $id_post;
			$record->id_forum  	= $id_forum;
			$record->id_user 	= $user->id;

			$record->date_created = date('Y-m-d H:i:s');
			$record->date_modified = date('Y-m-d H:i:s');

			$dao->insert($record);
			
			jEvent::notify('HfnuPostAfterSaveNotify',array('id'=>$id_post));
			
			jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));
			
			// let's update the counter of posts in member table
			$daoUser = jDao::get('havefnubb~member');			
			
			jForms::destroy('havefnubb~notify', $id_post);
			
			jMessage::add(jLocale::get('havefnubb~main.common.notify.added'),'ok');
		}
		
		$rep = $this->getResponse('redirect');
		$rep->action ='havefnubb~default:index';
		return $rep;						

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
	
	public function rss() {
        global $HfnuConfig;
        $id_forum = (int) $this->param('id_forum');
		// TODO : create this Subject hfnu.posts.rss
        // this is necessary to avoid to publish "pirvate" post/forum
		/*if ( ! jAcl2::check('hfnu.posts.rss','forum'.$id_forum) ) {
			$rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
		}*/
        if ($id_forum == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $rep;
        }
			
		$rep = $this->getResponse('rss2.0');
		
		// entete du flux rss
		$rep->infos->title = $HfnuConfig->getValue('title');
		$rep->infos->webSiteUrl= $_SERVER['HTTP_HOST'];
		$rep->infos->copyright = $HfnuConfig->getValue('title');
		$rep->infos->description = $HfnuConfig->getValue('description');
		$rep->infos->updated = date('Y-m-d H:i:s');
		$rep->infos->published = date('Y-m-d H:i:s');
		$rep->infos->ttl=60;

		$dao = jDao::get('havefnubb~forum');
		$forum = $dao->get($id_forum);
		
        // 1- limit of posts 
        $nbPostPerPage = 0;
        $nbPostPerPage = (int) $HfnuConfig->getValue('posts_per_page');
              
        $daoPost = jDao::get('havefnubb~posts');
        // 2- get the posts of the current forum, limited by point 1
        $posts = $daoPost->findByIdForum($id_forum,0,$nbPostPerPage);		
		$first = true;
		foreach($posts as $post){
		
		  if($first){
			  // le premier enregistrement permet de connaitre
			  // la date du channel
			  $rep->infos->updated = $post->date_created;
			  $rep->infos->published = $post->date_created;
			  $first=false;
		  }
		
		  $url = jUrl::get('havefnubb~posts:view', array('id_post'=>$post->id_post,
														 'parent_id'=>$post->parent_id,
														 'ftitle'=>$post->forum_name,
														 'id_forum'=>$post->id_forum,
														 'ptitle'=>$post->subject,
														 ));
				
		  $item = $rep->createItem($post->subject, $url, $post->date_created);
		
		  $item->authorName = $post->login;	

		  $item->content = $post->message;
		  $item->contentType='html';
		
		  $item->idIsPermalink = true;
		
		  // on ajoute l'item dans le fil RSS
		  $rep->addItem($item);
				
		}
		return $rep;
	}
}
