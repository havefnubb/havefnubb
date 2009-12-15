<?php
/**
* Controller to manage any specific Posts events
* 
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postsCtrl extends jController {
	
    /**
     * plugins to manage the behavior of the controller
     */		
    public $pluginParams = array(
        '*'		=> array( 'auth.required'=>false,
                                'hfnu.check.installed'=>true,
                                'banuser.check'=>true
                                ),
		
        'goto'	=> array( 'jacl2.right'=>'hfnu.forum.goto'),
		
        'add'	=> array('auth.required'=>true),
        'edit'	=> array('auth.required'=>true),
        'quote'	=> array('auth.required'=>true),
        'reply'	=> array('auth.required'=>true),
        'moveToForum' => array('auth.required'=>true),
        
        'save'		=> array('flood.editing'=>true,'flood.same.ip'=>true),
        'savereply'	=> array('flood.editing'=>true,'flood.same.ip'=>true),
		
        'lists'	=> array( 'history.add'=>true),
        'view' 	=> array( 'history.add'=>true),
        'status' => array('jacl2.right'=>'hfnu.admin.post'),
        'censor' => array('jacl2.right'=>'hfnu.admin.post'),
        'uncensor' => array('jacl2.right'=>'hfnu.admin.post'),
        'savecensor' => array('jacl2.right'=>'hfnu.admin.post'),
        'shownew'=> array('auth.required'=>true),
		
   );
	
    public static $statusClosed = array('closed','pinedclosed','censored');

    /**
     * main list of all posts of a given forum ($id_forum)
     */ 
    function lists() {
        global $gJConfig;
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

        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        
        // crumbs infos
        list($forum,$category) = $hfnuposts->getCrumbs($id_forum);
		        
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
        $nbPostPerPage = (int) $gJConfig->havefnubb['posts_per_page'];
        
        // get all the posts of the current Forum by its Id
        list($page,$nbPosts,$posts) = $hfnuposts->getPostsByIdForum($id_forum,$page,$nbPostPerPage);
        		
        // change the label of the breadcrumb
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($forum->forum_name,ENT_COMPAT,'UTF-8') . ' - ' . jLocale::get('havefnubb~main.common.page') . ' ' .($page+1));
		
        $rep = $this->getResponse('html');
		
        //build the rss link in the header of the html page
        $url = jUrl::get('havefnubb~posts:rss', array('ftitle'=>$forum->forum_name,
                                                    'id_forum'=>$forum->id_forum	));
        $rep->addHeadContent('<link rel="alternate" type="application/rss+xml" title="'.$forum->forum_name.'" href="'.htmlentities($url).'" />');
        // end rss link 
			
        if ($page == 0)
            $rep->title = $forum->forum_name;
        else
            $rep->title = $forum->forum_name . ' - ' . jLocale::get('havefnubb~main.common.page') . ' ' .($page+1) ;
		
        $tpl = new jTpl();        
        // B- Using the collected datas
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
        
        $rep->body->assign('currentIdForum',$forum->id_forum);		
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.list'));
        return $rep;        
    }        

    /**
     * display the first post + call zone posts_replies in template to display all the thread
     * 	Method 1 : default usage : list all messages of a thread (id_post known)
     * 	Method 2 : display a message from anywhere in the thread (id_post + parent_id known)
     */
    function view() {
        $id_post = (int) $this->param('id_post');
	$parent_id = (int) $this->param('parent_id');

        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');

        // business check :
        // 1) do those id exist ?
        // 2) permission ok ?
        // 3) if parent_id > 0 then calculate the page + assign id_post with parent_id
        // business update :
        // 1) update the count of view of this thread
        list($id_post,$post,$goto) = $hfnuposts->view($id_post,$parent_id);
             
        if ($post === null and $goto == null) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
	    return $rep;
        }        
		
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($post->subject,ENT_COMPAT,'UTF-8'));

	// crumbs infos
        
	list($forum,$category) = $hfnuposts->getCrumbs($post->id_forum);
	if (! $forum) {
            $rep  = $this->getResponse('redirect');
	    $rep->action = 'havefnubb~default:index';
            return $rep;			
	}
        
        $day_in_secondes = 24 * 60 * 60;
	$dateDiff =  ($post->date_modified == '') ? floor( (time() - $post->date_created ) / $day_in_secondes) : floor( (time() - $post->date_modified ) / $day_in_secondes) ;

	if ( $forum->post_expire > 0 and $dateDiff >= $forum->post_expire )
	    $status = 'closed';
	else
	    $status = $post->status;
		
        $rep = $this->getResponse('html');
        
        $page = 0;
        if ( $this->param('page') )
            $page = (int) $this->param('page');
			
        if ($goto > 0 ) $page = $goto;
		
        $tpl = new jTpl();				
        $tpl->assign('id_post'	,$id_post);
        $tpl->assign('forum'	,$forum);
	$tpl->assign('category'	,$category);
        $tpl->assign('page'	,$page);
        $tpl->assign('subject'	,$post->subject);
	$tpl->assign('status'	,$status);
		
        $rep->title = '['.jLocale::get('havefnubb~post.status.'.$status).'] '.$post->subject;                
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.view'));
        return $rep;
    }
    
    /**
    * display the add 'blank' form
    */
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
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }		
        
        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        // crumbs infos
        list($forum,$category) = $hfnuposts->getCrumbs($id_forum);
        if (! $forum) {
            $rep = $this->getResponse('redirect');
	    $rep->action = 'havefnubb~default:index';
            return $rep;			
	}
				
        $form = jForms::create('havefnubb~posts',$id_post);
        $form->setData('id_forum',$id_forum);
        $form->setData('id_user',jAuth::getUserSession ()->id);
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

    /**
    * display the edit form with the corresponding selected post
    */
    function edit () {		
        $id_post = (int) $this->param('id_post');
                
        if ($id_post == 0 ) {
            $rep  = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
        
        $post = jClasses::getService('havefnubb~hfnuposts')->getPost($id_post);
        if (jAuth::getUserSession ()->id == $post->id_user) {
            if ( ! jAcl2::check('hfnu.posts.edit.own','forum'.$post->id_forum)  ) {
                $rep = $this->getResponse('redirect');
                $rep->action = 'default:index';
                return $rep;
            }
        }        
        else
        if ( ! jAcl2::check('hfnu.posts.edit','forum'.$post->id_forum) ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }

        $srvTags = jClasses::getService("jtags~tags");
        $tags = implode(',',$srvTags->getTagsBySubject('forumscope',$id_post));		
		
        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        // crumbs infos
        list($forum,$category) = $hfnuposts->getCrumbs($post->id_forum);		
        if (! $forum) {
            $rep = $this->getResponse('redirect');
	    $rep->action = 'havefnubb~default:index';
            return $rep;			
	}
		
        $form = jForms::create('havefnubb~posts',$id_post);
        $form->initFromDao("havefnubb~posts");
                        
        $form->setData('id_forum',$post->id_forum);
        $form->setData('id_user',jAuth::getUserSession ()->id);
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
    
    /**
    * Save the data submitted from add/edit form
    */
    function save() {
            
        $id_forum = (int) $this->param('id_forum');
        $id_post  = (int) $this->param('id_post');
        
        if ($id_post > 0) {
                $daoPost = jDao::get('havefnubb~posts');
                $post = $daoPost->get($id_post);
                //it's my post
                if (jAuth::getUserSession ()->id == $post->id_user) {
                        if ( ! jAcl2::check('hfnu.posts.edit.own','forum'.$post->id_forum)  ) {
                                $rep = $this->getResponse('redirect');
                                $rep->action = 'default:index';
                                return $rep;
                        }
                }
                // am i an admin or modo to be able to edit this post which is not mine ?
                else {
                        if (! jAcl2::check('hfnu.posts.edit','forum'.$id_forum) ) {
                                $rep = $this->getResponse('redirect');
                                $rep->action = 'default:index';
                                return $rep;				
                        }
                }
        }
        else {
                if ( ! jAcl2::check('hfnu.posts.create','forum'.$id_forum) ) {
                        $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
                        return $rep;
                }
        }
        
        $parent_id 	= (int) $this->param('parent_id');		
        
        $submit = $this->param('validate');
        // preview ?
        if ($submit == jLocale::get('havefnubb~post.form.previewBt') ) {
                $daoUser = jDao::get('havefnubb~member');
                $user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
                    
        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        list($forum,$category) = $hfnuposts->getCrumbs($id_forum);

        $form = jForms::fill('havefnubb~posts',$id_post);

        $form->setData('id_forum',$id_forum);
        $form->setData('id_user',jAuth::getUserSession ()->id);
        $form->setData('id_post',$id_post);
        $form->setData('parent_id',$parent_id);
        $form->setData('subject',$form->getData('subject'));
        $form->setData('message',$form->getData('message'));
        
        //set the needed parameters to the template
        $tpl = new jTpl();
        $tpl->assign('id_post', $id_post);
        $tpl->assign('id_forum', $id_forum);            
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
                    
            if ($id_forum == 0  ) {			
                    $rep->action = 'havefnubb~default:index';	
                    return $rep;
            }
            //let's save the post 
            $hfnuposts = jClasses::getService('havefnubb~hfnuposts');            
            $result = $hfnuposts->save($id_forum,$id_post);
            
            if ($result === false) {
                $rep->action = 'havefnubb~posts:lists';
                $rep->param = array('id_forum'=>$id_forum);
                return $rep;        
            }
            else $id_post = $result;
                    
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
    
    /**
     * reply to a given post (from the parent_id)
     */
    function reply() {
	
        $parent_id 	= (int) $this->param('id_post');
        $id_post 	= (int) $this->param('id_post');
		
        if ($parent_id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
			return $ep;
        }
        
        $post = jClasses::getService('havefnubb~hfnupost')->get($parent_id);
        
        //check if this message is close and if i am an admin/mod
        if ( in_array($post->status,self::$statusClosed) and
                ! jAcl2::check('hfnu.posts.reply','forum'.$post->id_forum)
                ) {
                jMessage::add(jLocale::get('havefnubb~post.status.you.cant.reply.to.this.message'));
                $rep = $this->getResponse('redirect');
                $rep->action = 'default:index';
                return $rep;
        }
		
        if (!$post) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }
        // check if the post is expired
        $day_in_secondes = 24 * 60 * 60;
        $dateDiff =  ($post->date_modified == 0) ? floor( (time() - $post->date_created ) / $day_in_secondes) : floor( (time() - $post->date_modified ) / $day_in_secondes) ;

        if ( jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum)->post_expire > 0 and
                $dateDiff >= jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum)->post_expire ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }
		
        if ( ! jAcl2::check('hfnu.posts.reply','forum'.$post->id_forum) ) {
                $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }

        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        // crumbs infos
        list($forum,$category) = $hfnuposts->getCrumbs($post->id_forum);
        
        if (! $forum) {
            $rep  = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;			
	}		
        
        $form = jForms::create('havefnubb~posts',$parent_id);
        $form->initFromDao('havefnubb~posts');
        
        $form->setData('id_user',jAuth::getUserSession ()->id);
        $form->setData('id_post',0);
        $form->setData('parent_id',$id_post);
        $form->setData('message','');
        
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
	
    /**
     * quote message
     */
    function quote() {
		
        $parent_id 	= (int) $this->param('parent_id');
        $id_post 	= (int) $this->param('id_post');
		
        if ($parent_id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
		
        $daoPost = jDao::get('havefnubb~posts');
        $post = $daoPost->get($parent_id);
        
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
		       
        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');        
        // crumbs infos
        list($forum,$category) = $hfnuposts->getCrumbs($post->id_forum);
        if (! $forum) {
            $rep = $this->getResponse('redirect');
	    $rep->action = 'havefnubb~default:index';
            return $rep;			
	}
        // check if the post is expired
        $day_in_secondes = 24 * 60 * 60;
        $dateDiff =  ($post->date_modified == '') ? floor( (time() - $post->date_created ) / $day_in_secondes) : floor( (time() - $post->date_modified ) / $day_in_secondes) ;

        if ( $forum->post_expire > 0 and $dateDiff >= $forum->post_expire ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
	}
         
        $form = jForms::create('havefnubb~posts',$parent_id);
	$form->initFromDao('havefnubb~posts');
		
        $form->setData('subject',$post->subject);
        $form->setData('id_forum',$post->id_forum);
        $form->setData('id_user',jAuth::getUserSession ()->id);
        $form->setData('id_post',0);
        $form->setData('parent_id',$parent_id);
                
        $newMessage = ">".preg_replace("/\\n/","\n>",$post->message);
        
        $quoteMessage = ">".$post->login.' ' .
                        jLocale::get('havefnubb~post.form.author.said') . "\n".
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
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
        return $rep;		
    }


    /**
    * save the datas posted from the reply form
    */
    function savereply() {
        $id_forum   = (int) $this->param('id_forum');
        
        if ( ! jAcl2::check('hfnu.posts.quote','forum'.$id_forum) and
            ! jAcl2::check('hfnu.posts.reply','forum'.$id_forum) ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
    	}
		
        $id_post    = (int) $this->param('id_post');       
        $parent_id  = (int) $this->param('parent_id');

        $submit = $this->param('validate');
        // preview ?
        if ($submit == jLocale::get('havefnubb~post.form.previewBt') ) {
                
            $daoUser = jDao::get('havefnubb~member');
            $user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
                
            $hfnuposts = jClasses::getService('havefnubb~hfnuposts');            
            list($forum,$category) = $hfnuposts->getCrumbs($id_forum);

            $form = jForms::fill('havefnubb~posts',$parent_id);

            $form->setData('id_forum',$id_forum);
            $form->setData('id_user',jAuth::getUserSession ()->id);
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
            
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
            return $rep;		            
        }
        // save ?
        elseif ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {
	    $rep = $this->getResponse('redirect');

            if ($id_forum == 0 ) {
                $rep->action = 'havefnubb~default:index';	
                return $rep;        
            }

            //let's save the reply
            $hfnuposts = jClasses::getService('havefnubb~hfnuposts');            
            $result = $hfnuposts->savereply($parent_id);
            
            if ($result === false ) {
                $rep->action = 'havefnubb~posts:lists';
                $rep->param = array('id_forum'=>$id_forum);
                return $rep;                
            } else {
                $record = $result;
            }

            jMessage::add(jLocale::get('havefnubb~main.common.reply.added'),'ok');
            
            $rep->params = array('ftitle'=>$record->forum_name,
                                                     'ptitle'=>$record->subject,
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

    /**
     * delete a post
     */
    function delete() {

        $id_post = (integer) $this->param('id_post');
        $id_forum = (integer) $this->param('id_forum');
        
        if ( ! jAcl2::check('hfnu.posts.delete','forum'.$id_forum) ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }

        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);
		
        jEvent::notify('HfnuPostBeforeDelete',array('id'=>$id_post));       
		
        if ( jClasses::getService('havefnubb~hfnuposts')->delete($id_post) === true ) {    

            jEvent::notify('HfnuPostAfterDelete',array('id'=>$id_post));
            
            jEvent::notify('HfnuSearchEngineDeleteContent',array('id'=>$id_post,'havefnubb~forum'));
            
            jMessage::add(jLocale::get('havefnubb~main.common.posts.deleted'),'ok');
        }
        $rep = $this->getResponse('redirect');		
        $rep->action='havefnubb~posts:lists';
        $rep->params=array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name);
        return $rep;		
    }

    /**
     * goto another forum
     */
    function goesto() {
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

    /**
     * notify something from a given post (from the parent_id) to the admin
     */
    function notify() {
		
        $id_post = (int) $this->param('id_post');
        if ($id_post == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }
        
        $daoPost = jDao::get('havefnubb~posts');
        $post = $daoPost->get($id_post);              

        if ( ! jAcl2::check('hfnu.posts.notify','forum'.$post->id_forum) ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }

        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        // crumbs infos
        list($forum,$category) = $hfnuposts->getCrumbs($post->id_forum);
        if (! $forum) {
            $rep 		 = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;			
        }		
		
        $form = jForms::create('havefnubb~notify',$id_post);
        $form->setData('id_user',jAuth::getUserSession ()->id);
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

    /**
    * save the datas posted from the notify form
    */
    function savenotify() {
        $id_post    = (int) $this->param('id_post');
        $id_forum   = (int) $this->param('id_forum');

        if ( ! jAcl2::check('hfnu.posts.notify','forum'.$id_forum) ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }

        $submit = $this->param('validate');
        if ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {
            $rep = $this->getResponse('redirect');
            
            if ($id_post ==  0 or $id_forum == 0) {			
                    $rep->action = 'havefnubb~default:index';	
                    return $rep;
            }
			
            //let's save the post 
            $hfnuposts = jClasses::getService('havefnubb~hfnuposts');            
            $result = $hfnuposts->savenotify($id_post);
            if ($result === false) {
                $rep->action = 'havefnubb~default:index';
                return $rep;               
            }
            
            jMessage::add(jLocale::get('havefnubb~main.common.notify.added'),'ok');
        }
        
        $rep = $this->getResponse('redirect');
        $rep->action ='havefnubb~default:index';
        return $rep;						

    }

    /**
     * change the status of the post
     * known status : 'opened','closed','pined','pinedclosed'
     */
    function status () {
		
        $parent_id = (int) $this->param('parent_id');
        $status = $this->param('status');
        
        $rep = $this->getResponse('redirect');
        
        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        $result = $hfnuposts->switchStatus($parent_id,$status);
        
        if ($result === false ) {
            $rep->action = 'havefnubb~default:index';
        }
        else {
                $post = $result;
                jMessage::add(jLocale::get('havefnubb~post.status.'.$status),'ok');
                $rep->action = 'havefnubb~posts:view';		
                $rep->params = array('id_post'=>$post->id_post,
                                    'parent_id'=>$parent_id,
                                    'id_forum'=>$post->id_forum,
                                    'ftitle'=>$post->forum_name,
                                    'ptitle'=>$post->subject);
                
        }

        return $rep;

    }
	
    /**
     * provide a rss feeds for each forum
     */
    public function rss() {
        global $gJConfig;
        $id_forum = (int) $this->param('id_forum');
            
        // if the forum is accessible by anonymous then the rss will be available
        // otherwise NO RSS will be available
        if ( ! jAcl2::check('hfnu.posts.rss','forum'.$id_forum) ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }
            
        if ($id_forum == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
                        return $rep;
        }
                    
        $rep = $this->getResponse('rss2.0');
        
        // entete du flux rss
        $rep->infos->title = $gJConfig->havefnubb['title'];
        $rep->infos->webSiteUrl= $_SERVER['HTTP_HOST'];
        $rep->infos->copyright = $gJConfig->havefnubb['title'];
        $rep->infos->description = $gJConfig->havefnubb['description'];
        $rep->infos->updated = date('Y-m-d H:i:s');
        $rep->infos->published = date('Y-m-d H:i:s');
        $rep->infos->ttl=60;

        $dao = jDao::get('havefnubb~forum');
        $forum = $dao->get($id_forum);
            
        // 1- limit of posts 
        $nbPostPerPage = 0;
        $nbPostPerPage = (int)  $gJConfig->havefnubb['posts_per_page'];
              
        $daoPost = jDao::get('havefnubb~posts');
        // 2- get the posts of the current forum, limited by point 1
        $posts = $daoPost->findByIdForum($id_forum,0,$nbPostPerPage);		
        $first = true;
        foreach($posts as $post){
        
          if($first){
                  // le premier enregistrement permet de connaitre
                  // la date du channel
                  $rep->infos->updated = date('Y-m-d H:i:s',$post->date_created);
                  $rep->infos->published = date('Y-m-d H:i:s',$post->date_created);
                  $first=false;
          }
        
          $url = jUrl::get('havefnubb~posts:view',
                           array('id_post'=>$post->id_post,
                            'parent_id'=>$post->parent_id,
                            'ftitle'=>$post->forum_name,
                            'id_forum'=>$post->id_forum,
                            'ptitle'=>$post->subject,
                            ));
                        
          $item = $rep->createItem($post->subject, $url, date('Y-m-d H:i:s',$post->date_created));
        
          $item->authorName = $post->login;	

          $render = new jWiki();
          $item->content = $render->render($post->message);
          $item->contentType='html';
        
          $item->idIsPermalink = true;
        
          // on ajoute l'item dans le fil RSS
          $rep->addItem($item);
                        
        }
        return $rep;
    }	
    /**
     * this function permits to move a complet thread to another forum
     */
    public function moveToForum() {
        $id_forum = (int) $this->param('id_forum');
        $id_post = (int) $this->param('id_post');
        
        if ( $id_forum == 0) {
                $rep = $this->getResponse('redirect');
                $rep->action = 'havefnubb~default:index';
                return $rep;			
        }
        
        if ($id_post == 0) {
                $rep = $this->getResponse('redirect');
                $rep->action = 'havefnubb~posts:lists';
                $rep->param = array('id_forum'=>$id_forum);
                return $rep;
        }
        
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
            
        //let's move the thread
        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');            
        $result = $hfnuposts->moveToForum($id_post,$id_forum);
            
        if ($result === false ) {
                $rep = $this->getResponse('redirect');
                $rep->action = 'havefnubb~posts:lists';
                $rep->param = array('id_forum'=>$id_forum);
                return $rep;                
        }

        $daoForum = jDao::get('havefnubb~posts');			
        $forum = $daoForum->get($id_post);			
        jMessage::add(jLocale::get('havefnubb~main.common.thread.moved'),'ok');
        $rep = $this->getResponse('redirect');
        $rep->params = array('ftitle'=>$forum->forum_name,
                                                 'ptitle'=>$forum->subject,
                                                 'id_forum'=>$id_forum,
                                                 'id_post'=>$forum->id_post,
                                                 'parent_id'=>$forum->parent_id);
        $rep->action ='havefnubb~posts:view';
        return $rep;		
    }
	
    /**
     * 'Wizard' to ask to the admin where to move the selected thread,
     * starting from the current message
     */
    public function splitTo() {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
            
        $id_post 	= (int) $this->param('id_post');
        $parent_id 	= (int) $this->param('parent_id');
        $id_forum 	= (int) $this->param('id_forum');
        $step 		= (int) $this->param('step');

        if ($id_post == 0 or $id_forum == 0 or $parent_id == 0) {
                $rep 			= $this->getResponse('redirect');
                $rep->action 	= 'havefnubb~default:index';
                return $rep;
        }
            
        if ($step == 0) {
            $form = jForms::create('havefnubb~split');
            $form->setData('id_post',	$id_post);
            $form->setData('parent_id',	$parent_id);
            $form->setData('id_forum',	$id_forum);
            $form->setData('step',	1);
            
            $dao = jDao::get('havefnubb~posts');
            $post = $dao->get($id_post);

            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $tpl->assign('title',$post->subject);
            $tpl->assign('form',$form);			
            $tpl->assign('step',1);
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~split.to'));
            $rep->title = jLocale::get("havefnubb~main.split.this.thread.from.this.message") . ' : ' . $post->subject;
            return $rep;			
                    
        }
        elseif ($step == 1) {
            $submit = $this->param('validate');
            if ( $submit == jLocale::get('havefnubb~post.form.saveBt') ) {
                // let's define the possible actions we can do :
                // where to split this thread  : 
                // 1) in the same forum and create a new one
                // 2) in another forum and create a new one
                // 3) link to an existing thread in the SAME forum
                $possibleActions = array('same_forum','others','existings');		
                // the choice is ?
                $choice = (string) $this->param('choice');

                if (! in_array($choice,$possibleActions) ) {
                        $rep 			= $this->getResponse('redirect');
                        $rep->action 	= 'havefnubb~default:index';
                        return $rep;				
                }

                $dao = jDao::get('havefnubb~posts');
                $post = $dao->get($id_post);
                switch ($choice) {
                    case 'same_forum' :
                        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
                        $id_post = $hfnuposts->splitToForum($parent_id,$id_post,$id_forum);
                        if ($id_post > 0 ) $result = true; else $result = false;
                        break;
                    case 'others' :
                        // the id_forum change to the new selected one
                        $id_forum = (int) $this->param('other_forum');
                        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
                        $id_post = $hfnuposts->splitToForum($parent_id,$id_post,$id_forum);
                        if ($id_post > 0 ) $result = true; else $result = false;
                        break;
                    case 'existings' :
                        // the parent_id change to the new selected one
                        $new_parent_id = (int) $this->param('existing_thread');						
                        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
                        $result = $hfnuposts->splitToThread($id_post,$parent_id,$new_parent_id);
                        $id_post = (int) $this->param('existing_thread');
                        break;
                        
                }
                
                $dao = jDao::get('havefnubb~posts');			
                $post = $dao->get($id_post);
                if ($result === false) {
                    jMessage::add(jLocale::get('havefnubb~main.common.thread.cant.be.moved'),'error');
                }
                else {
                    jMessage::add(jLocale::get('havefnubb~main.common.thread.moved'),'ok');
                }
                $rep = $this->getResponse('redirect');
                $rep->params = array('ftitle'=>$post->forum_name,
                                    'ptitle'=>$post->subject,
                                    'id_forum'=>$id_forum,
                                    'id_post'=>$post->id_post,
                                    'parent_id'=>$post->parent_id);
                $rep->action ='havefnubb~posts:view';
                return $rep;
            }
        }
        else {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;			
        }
            
    }
	
    /**
     * censored this post (or thread if parent_id = id_post )
     */
    public function censor () {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
            
        $rep = $this->getResponse('html');
        
        $id_post    = (int) $this->param('id_post');
        $parent_id  = (int) $this->param('parent_id');
        
        if ($id_post < 1 or $parent_id < 0) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;			
        }
        
        $form = jForms::create('havefnubb~censor',$id_post);
        $form->setData('id_post',$id_post);
        $form->setData('parent_id',$parent_id);
        
        $tpl = new jTpl();
        $tpl->assign('form',$form);
        $tpl->assign('id_post',$id_post);
        $tpl->assign('parent_id',$parent_id);
        $tpl->assign('title',jClasses::getService('havefnubb~hfnuposts')->getPost($id_post)->subject);
        $rep->body->assign('MAIN',$tpl->fetch('havefnubb~censor'));
        return $rep;
    }
    
    /**
     * save the censored message 
     */	
    public function savecencor() {
        $rep = $this->getResponse('redirect');
            
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
        $id_post 	= (int) $this->param('id_post');
        $parent_id 	= (int) $this->param('parent_id');
            
        if ($id_post < 1 or $parent_id < 0) {
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
            
        $form = jForms::fill('havefnubb~censor',$id_post);
        if (!$form->check()) {
            $rep->action = 'havefnubb~posts:censor';
            $rep->params = array('id_post'=>$id_post,'parent_id'=>$parent_id);
            return $rep;			
        }
        else {
            //censoring an entire thread
            $result = jClasses::getService('havefnubb~hfnuposts')->switchStatus(
                                        $parent_id,$id_post,'censored',$form->getData('censored_msg')
                                        );
            
            if ($result === false ) {
                $rep->action = 'havefnubb~default:index';
                return $rep;								
            }
            else {
                    $post  = $result;
                    jMessage::add(jLocale::get('havefnubb~post.status.censored'),'ok');
                    $rep->action = 'havefnubb~posts:view';		
                    $rep->params = array('id_post'=>$post->id_post,
                                        'parent_id'=>$parent_id,
                                        'id_forum'=>$post->id_forum,
                                        'ftitle'=>$post->forum_name,
                                        'ptitle'=>$post->subject);
                    return $rep;		
            }

        }
            
    }
    /**
     * uncensored this id post (or thread if parent_id = id_post)
     */		
    public function uncensor() {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
        $id_post 	= (int) $this->param('id_post');
        $parent_id 	= (int) $this->param('parent_id');
        
        $post = jClasses::getService('havefnubb~hfnuposts')->switchStatus($parent_id,$id_post,'uncensored');
        
        if ($post === false) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;								
        }
        
        $rep = $this->getResponse('redirect');
        jMessage::add(jLocale::get('havefnubb~post.status.uncensored'),'ok');
        $rep->action = 'havefnubb~posts:view';		
        $rep->params = array('id_post'=>$post->id_post,
                            'parent_id'=>$parent_id,
                            'id_forum'=>$post->id_forum,
                            'ftitle'=>$post->forum_name,
                            'ptitle'=>$post->subject);
        return $rep;		
    }
    /**
     * Show all new posts not read by the current user
     */
    public function shownew() {
	$rep = $this->getResponse('html');
        $tpl = new jTpl();
        $posts = jClasses::getService('havefnubb~hfnuread')->getUnreadThread();
        $tpl->assign('posts',$posts);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.shownew'));
        return $rep;
    }
}
