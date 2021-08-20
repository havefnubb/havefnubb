<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2011-2019 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage any specific Posts events
*/
class postsCtrl extends jController {
    /**
     * @var $pluginParams plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*' => array('auth.required'=>false,
                    'banuser.check'=>true
                    ),

        'goesto' => array( 'jacl2.right'=>'hfnu.forum.goto'),

        'add'   => array('auth.required'=>false),
        'edit'  => array('auth.required'=>true ),
        'quote' => array('auth.required'=>false),
        'reply' => array('auth.required'=>false),

        'save'      =>  array('auth.required'=>false, 'check.flood'=>true),
        'savereply' =>  array('auth.required'=>false, 'check.flood'=>true),
        'lists'=>       array('history.add'=>true),
        'view' =>       array('history.add'=>true),
        'viewtogo' =>   array('history.add'=>false),
        'shownew'=>     array('auth.required'=>true),
        'unsubscribe'=> array('auth.required'=>true),
        'unsub'=>       array('auth.required'=>true),
        'subscribe'=>   array('auth.required'=>true),
    );
    /**
     * @var static $statusClosed array of the 'closed' status
     */
    //4='closed',2='pinedclosed',5='censored'
    public static $statusClosed = array(4,2,5);

    public static $statusAvailable = array('pined',
                                           'pinedclosed',
                                           'opened',
                                           'closed',
                                           'censored',
                                           'uncensored',
                                           'hidden');


    /**
     * main list of all posts of a given forum ($id_forum)
     */
    public function lists() {
        $ftitle = jUrl::escape($this->param('ftitle'),true);

        $id_forum = (int) $this->param('id_forum');

        if ( ! jAcl2::check('hfnu.posts.list','forum'.$id_forum) ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html', true);
            $rep->bodyTpl = 'havefnubb~403.html';
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        if ($id_forum == 0 ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] id_forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        // crumbs infos
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);

        if (jUrl::escape($forum->forum_name,true) != $ftitle ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be different ] '.$forum->forum_name . ' and ' .$ftitle ,'DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => '',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("havefnubb~main.common.pagelinks.end"),
                      'area-size'   => 5);
        // 1- get the offset parm if exist
        $page = 0;
        if ( $this->param('page') > 0 )
            $page = (int) $this->param('page');

        if ($page < 0) $page = 0;

        $gJConfig = jApp::config();
        
        // 2- limit per page
        $nbPostPerPage = 0;
        $nbPostPerPage = (int) $gJConfig->havefnubb['posts_per_page'];

        // get all the posts of the current Forum by its Id
        list($page,$nbPosts,$posts) = jClasses::getService('havefnubb~hfnuposts')->getThreads($id_forum,$page,$nbPostPerPage);

        // change the label of the breadcrumb
        jApp::coord()->getPlugin('history')->change('label', htmlentities($forum->forum_name,ENT_COMPAT,'UTF-8') . ' - ' . jLocale::get('havefnubb~main.common.page') . ' ' .($page+1));

        $rep = $this->getResponse('html');

        //build the rss link in the header of the html page
        $url = jUrl::get('havefnubb~posts:rss', array('ftitle'=>$forum->forum_name,
                                                    'id_forum'=>$forum->id_forum));
        $rep->addHeadContent('<link rel="alternate" type="application/rss+xml" title="'.$forum->forum_name.' - RSS" href="'.htmlentities($url).'" />');
        $url = jUrl::get('havefnubb~posts:atom', array('ftitle'=>$forum->forum_name,
                                                    'id_forum'=>$forum->id_forum));
        $rep->addHeadContent('<link rel="alternate" type="application/atom+xml" title="'.$forum->forum_name.' - ATOM" href="'.htmlentities($url).'" />');

        // end rss link

        if ($page == 0)
            $rep->title = $forum->forum_name;
        else
            $rep->title = $forum->forum_name . ' - ' . jLocale::get('havefnubb~main.common.page') . ' ' .($page+1) ;

        $tpl = new jTpl();
        // B- Using the collected datas
        // 1- the posts
        if (jAuth::isConnected() and 
            jDao::get('havefnubb~forum_sub')->get(jAuth::getUserSession()->id,$forum->id_forum)
            )
            $tpl->assign('subcribedToThisForum',true);
        else
            $tpl->assign('subcribedToThisForum',false);

        $tpl->assign('posts',$posts);
        // 2- the forum
        $tpl->assign('forum',$forum);
        // 3- vars for pagelinks
        // see A-1 / A-2 / A-3
        $tpl->assign('page',$page);
        $tpl->assign('nbPostPerPage',$nbPostPerPage);
        $tpl->assign('nbPosts',$nbPosts);
        $tpl->assign('important_nb_replies',$gJConfig->havefnubb['important_nb_replies']);
        $tpl->assign('important_nb_views',$gJConfig->havefnubb['important_nb_views']);
        $tpl->assign('id_forum',$id_forum);
        $tpl->assign('lvl',$forum->child_level);
        $tpl->assign('properties',$properties);
        $tpl->assign('currentIdForum',$forum->id_forum);
        $tpl->assign('ftitle',$ftitle);
        $tpl->assign('statusAvailable',self::$statusAvailable);
        $tpl->assign('lastMarkThreadAsRead',
                     jClasses::getService('havefnubb~hfnuread')->getLastDateRead($forum->id_forum));
        $rep->body->assign('currentIdForum',$forum->id_forum);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.list'));
        return $rep;
    }
    /**
     * display determine the page number of the post id then redirect to the view function
     */
    function viewtogo () {

        if ($this->param('go')) {
            $gotoPostId = (int) $this->param('go');
            $thread_id = (int) $this->param('thread_id');
            $rec = jDao::get('havefnubb~posts')->findAllPostByThreadId($thread_id);
            $nbRec = $rec->rowCount();
            if ($nbRec > 0 ) {
                $nbRepliesPerPage = (int) jApp::config()->havefnubb['replies_per_page'];
                for ($nbReplies = 0; $nbReplies < $nbRec; ++$nbReplies) {
                    foreach ($rec as $child) {
                        if ($gotoPostId == $child->id_post) {
                            break;
                        }
                    }
                }
                $page = (ceil ($nbReplies/$nbRepliesPerPage) * $nbRepliesPerPage) - $nbRepliesPerPage;

                $rep = $this->getResponse('redirect');
                $rep->action = 'posts:view';

                if ($page == 0 )
                    $rep->params = array(
                                'id_forum'=>$this->param('id_forum'),
                                'ftitle'=>jUrl::escape($this->param('ftitle'),true),
                                'id_post'=>$this->param('id_post'),
                                'ptitle'=>jUrl::escape($this->param('ptitle'),true),
                                'thread_id'=>$thread_id
                                );
                else
                    $rep->params = array(
                                'id_forum'=>$this->param('id_forum'),
                                'ftitle'=>jUrl::escape($this->param('ftitle'),true),
                                'id_post'=>$this->param('id_post'),
                                'ptitle'=>jUrl::escape($this->param('ptitle'),true),
                                'thread_id'=>$thread_id,
                                'page'=>$page
                                );

            }else {
                jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $rec->rowCount()','DEBUG');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
                $rep->setHttpStatus('404', 'Not found');
            }
        } else {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should contains "go"] $this->param()','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
        }
        return $rep;
    }
    /**
     * display the first post + call zone posts_replies in template to display all the thread
     * 	Method 1 : default usage : list all messages of a thread (id_post known)
     * 	Method 2 : display a message from anywhere in the thread (id_post + thread_id known)
     */
    function view() {

        $ftitle = jUrl::escape($this->param('ftitle'),true);
        $ptitle = jUrl::escape($this->param('ptitle'),true);
        $id_post    = (int) $this->param('id_post');
        $thread_id  = (int) $this->param('thread_id');

        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');

        // business check :
        // 1) do those id exist ?
        // 2) permission ok ?
        // 3) if thread_id > 0 then calculate the page + assign id_post with thread_id
        // business update :
        // 1) update the count of view of this thread
        list($id_post,$post,$goto,$nbReplies) = $hfnuposts->view($id_post,$thread_id);

        if ($post === null and $goto === null) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be null] $post and $goto','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        jApp::coord()->getPlugin('history')->change('label', htmlentities($post->subject,ENT_COMPAT,'UTF-8'));

        // crumbs infos
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum);

        if (! $forum) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        if (jUrl::escape($forum->forum_name,true) != $ftitle or
            jUrl::escape($hfnuposts->getPost($id_post)->subject,true) != $ptitle)
        {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be different] $forum->forum_name and $ftitle or $hfnuposts->getPost($id_post)->subject and $ptitle','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $day_in_secondes = 24 * 60 * 60;
        $dateDiff =  ($post->date_modified == '') ? floor( (time() - $post->date_created ) / $day_in_secondes) : floor( (time() - $post->date_modified ) / $day_in_secondes) ;

        if ( $forum->post_expire > 0 and $dateDiff >= $forum->post_expire ) {
            $status = 4; //closed
        }
        else {
            $status = $post->status;
        }

        $rep = $this->getResponse('html');

        $page = 0;
        if ( $this->param('page') )
            $page = (integer) $this->param('page');

        //if ($goto > 0 ) $page = $goto;

        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => '',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("havefnubb~main.common.pagelinks.end"),
                      'area-size'   => 5);
        // 1- get the nb of replies per page
        $nbRepliesPerPage = 0;
        $nbRepliesPerPage = (int) jApp::config()->havefnubb['replies_per_page'];

        // 2- get the post
        list($page,$posts) = jClasses::getService("havefnubb~hfnuposts")->findByThreadId($thread_id,$page,$nbRepliesPerPage);

        // 3- total number of posts
        $nbReplies = jDao::get('havefnubb~threads_alone')->get($thread_id)->nb_replies + 1; // add 1 because nb_replies does not count the "parent" post

        $srvTags = jClasses::getService("jtags~tags");
        $tags = implode(',',$srvTags->getTagsBySubject('forumscope',$id_post));

        $tpl = new jTpl();

        if (jAuth::isConnected() and            
            jDao::get('havefnubb~forum_sub')->get(jAuth::getUserSession()->id,$forum->id_forum)
            ) {
            $tpl->assign('current_user',jAuth::getUserSession ()->login);
            $tpl->assign('subcribedToThisForum',true);
        }
        else {
            $tpl->assign('current_user','');
            $tpl->assign('subcribedToThisForum',false);
        }
        
        
        $tpl->assign('lastMarkThreadAsRead',
                     jClasses::getService('havefnubb~hfnuread')->getLastDateRead($forum->id_forum,$thread_id));


        if ( jAcl2::check('hfnu.admin.post') ) {
            $formStatus = jForms::create('havefnubb~posts_status');
            $formStatus->setData('id_post',$post->id_post);
            $formStatus->setData('thread_id',$post->thread_id);
            $formMove = jForms::create('havefnubb~posts_move');
            $tpl->assign('formStatus',$formStatus);
            $tpl->assign('formMove',$formMove);
        }

        $tpl->assign('id_post',$post->id_post);
        $tpl->assign('forum',$forum);
        $tpl->assign('posts',$posts);
        $tpl->assign('tags',$tags);
        $tpl->assign('page',$page);

        $tpl->assign('nbRepliesPerPage',$nbRepliesPerPage);
        $tpl->assign('nbReplies',$nbReplies);
        $tpl->assign('properties',$properties);
        $tpl->assign('ptitle',$post->subject);
        $tpl->assign('thread_id',$post->thread_id);
        $tpl->assign('forum_name',$post->forum_name);
        $tpl->assign('subscribed',
                     jClasses::getService('havefnubb~hfnusub')->getSubscribed(
                                            //$parentPost->thread_id
                                            $post->thread_id
                                            )
                    );

        $tpl->assign('subject'  ,$post->subject);
        $tpl->assign('status'   ,self::$statusAvailable[$status -1]);
        $tpl->assign('statusAvailable',self::$statusAvailable);
        $tpl->assign('currentIdForum',$forum->id_forum);
        $rep->title = '['.jLocale::get('havefnubb~post.status.'.self::$statusAvailable[$status -1]).'] '.$post->subject;
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.view'));
        return $rep;
    }
    /**
    * display the add 'blank' form to add a new post
    */
    function add () {
        $id_forum = (int) $this->param('id_forum');
        $id_post = 0;

        // invalid forum id
        if ($id_forum == 0) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $id_forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }
        if (jAuth::isConnected()) {
            if ( ! jAcl2::check('hfnu.posts.create','forum'.$id_forum) ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
            $form = jForms::create('havefnubb~posts',$id_post);
            $form->setData('id_user',jAuth::getUserSession ()->id);
        } else {
            if ( ! jAcl2::check('hfnu.posts.create','forum'.$id_forum) and
                 ! jAcl2::check('hfnu.posts.create') ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
            $form = jForms::create('havefnubb~posts_anonym',$id_post);
            $form->setData('id_user',0);
        }

        $srvTags = jClasses::getService("jtags~tags");
        // crumbs infos
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);
        if (! $forum) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $form->setData('id_forum',  $id_forum);
        $form->setData('id_post',   $id_post);

        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.new.message");
        $srvTags->setResponsesHeaders($rep);
        //set the needed parameters to the template
        $tpl = new jTpl();
        $tpl->assign('id_post',     $id_post);
        $tpl->assign('id_forum',    $id_forum);
        $tpl->assign('previewtext', null);
        $tpl->assign('previewsubject',null);
        $tpl->assign('form',    $form);
        $tpl->assign('forum',   $forum);
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
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $id_post','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $post = jClasses::getService('havefnubb~hfnuposts')->getPost($id_post);
        if (jAuth::getUserSession ()->id == $post->id_user) {
            if ( ! jAcl2::check('hfnu.posts.edit.own','forum'.$post->id_forum)  ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
        }
        else
        if ( ! jAcl2::check('hfnu.posts.edit','forum'.$post->id_forum) ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $srvTags = jClasses::getService("jtags~tags");
        $tags = implode(',',$srvTags->getTagsBySubject('forumscope',$id_post));

        // crumbs infos
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum);
        if (! $forum) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $form = jForms::create('havefnubb~posts',$id_post);
        $form->initFromDao("havefnubb~posts");

        $form->setData('id_forum',$post->id_forum);
        $form->setData('id_post',$id_post);
        $form->setData('tags', $tags);

        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.edit.message");

        $srvTags->setResponsesHeaders($rep);

        //set the needed parameters to the template
        $tpl = new jTpl();
        $tpl->assign('id_post', $id_post);
        $tpl->assign('id_forum',$post->id_forum);
        $tpl->assign('previewtext', null);
        $tpl->assign('previewsubject', null);
        $tpl->assign('form',    $form);
        $tpl->assign('forum',   $forum);
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

        if (jAuth::isConnected()) {
            if ( ! jAcl2::check('hfnu.posts.create','forum'.$id_forum) ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
        } else {
            if ( ! jAcl2::check('hfnu.posts.create','forum'.$id_forum)
                and
                 ! jAcl2::check('hfnu.posts.create')
                ) {
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
        }

        //edit a post
        if ($id_post > 0) {
            $daoPost = jDao::get('havefnubb~posts');
            $post = $daoPost->get($id_post);
            //it's my post
            if (jAuth::getUserSession ()->id == $post->id_user) {
                if ( ! jAcl2::check('hfnu.posts.edit.own','forum'.$id_forum)  ) {
                    jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                    $rep = $this->getResponse('html');
                    $tpl = new jTpl();
                    $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                    $rep->setHttpStatus('403', 'Permission denied');
                    return $rep;
                }
            }
            // am i an admin or modo to be able to edit this post which is not mine ?
            else {
                if (! jAcl2::check('hfnu.posts.edit','forum'.$id_forum) ) {
                    jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                    $rep = $this->getResponse('html');
                    $tpl = new jTpl();
                    $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                    $rep->setHttpStatus('403', 'Permission denied');
                    return $rep;
                }
            }
        }
        //add a post

        $thread_id = (int) $this->param('thread_id');

        $submit = $this->param('validate');
        // preview ?
        if ($submit == jLocale::get('havefnubb~post.form.previewBt') ) {
            $daoUser = jDao::get('havefnubb~member');

            //crumbs info
            $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);

            if (jAuth::isConnected()) {
                $form = jForms::fill('havefnubb~posts',$id_post);
            }
            else {
                $form = jForms::fill('havefnubb~posts_anonym',$id_post);
            }

            if (!$form) {
                $rep = $this->getResponse('redirect');
                $rep->action = 'havefnubb~posts:lists';
                $rep->params = array('id_forum'=>$id_forum,
                                     'ftitle'=>$forum->forum_name);
                return $rep;
            }

            $user = $daoUser->getById( (int) $form->getData('id_user'));

            $form->setData('id_forum',  $id_forum);
            $form->setData('id_user',   $form->getData('id_user'));
            $form->setData('id_post',   $id_post);
            $form->setData('thread_id', $thread_id);
            $form->setData('subject',   $form->getData('subject'));
            $form->setData('message',   $form->getData('message'));

            //set the needed parameters to the template
            $tpl = new jTpl();
            $tpl->assign('id_post',     $id_post);
            $tpl->assign('id_forum',    $id_forum);
            $tpl->assign('signature',   $user->member_comment);
            $tpl->assign('previewsubject', $form->getData('subject'));
            $tpl->assign('previewtext', $form->getData('message'));
            $tpl->assign('form',    $form);
            $tpl->assign('forum',   $forum);

            $rep = $this->getResponse('html');
            $rep->title = jLocale::get('havefnubb~post.form.new.message');

            $tpl->assign('heading',jLocale::get('havefnubb~post.form.new.message'));
            $tpl->assign('submitAction','havefnubb~posts:save');

            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
            return $rep;
        }
        // save ?
        elseif ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {


            if ($id_forum == 0  ) {
                jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $id_forum','DEBUG');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
                $rep->setHttpStatus('404', 'Not found');
                return $rep;
            }

            $rep = $this->getResponse('redirect');
            //let's save the post
            $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
            $post = $hfnuposts->save($id_forum,$id_post);

            if ($post === false) {
                $rep->action = 'havefnubb~posts:lists';
                $rep->params = array('id_forum'=>$id_forum,
                                     'ftitle'=>jDao::get('havefnubb~forum')->get($id_forum)->forum_name);
                return $rep;
            }
            else {
                $id_post = $post->id_post;
                $thread_id = $post->thread_id;
            }

            jMessage::add(jLocale::get('havefnubb~main.common.posts.saved'),'ok');
            //after editing, returning to the thread_id post !
            $daoPost = jDao::get('havefnubb~posts');
            $post = $daoPost->get($id_post);

            $rep->params = array('id_post'=>$id_post,
                                'thread_id'=>$thread_id,
                                'id_forum'=>$post->id_forum,
                                'ftitle'=>$post->forum_name,
                                'ptitle'=>$post->subject,
                                'go'=>$id_post);
            $rep->anchor = 'p'.$id_post;
            $rep->action ='havefnubb~posts:viewtogo';
            return $rep;
        }
        else {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this button that submit the form is not the expected one] the submit button is not save nor preview','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }
    }
    /**
     * reply to a given post (from the thread_id)
     */
    function reply() {
        $thread_id = (int) $this->param('thread_id');
        $id_post = (int) $this->param('id_post');

        if ($thread_id == 0 ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $thread_id','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $post = jClasses::getService('havefnubb~hfnuposts')->getPost($id_post);

        //check if this message is close and if i am an admin/mod
        if ( in_array($post->status,self::$statusClosed) ) {
            jMessage::add(jLocale::get('havefnubb~post.status.you.cant.reply.to.this.message'));
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        if (!$post) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $post','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }
        // check if the post is expired
        $day_in_secondes = 24 * 60 * 60;
        $dateDiff =  ($post->date_modified == 0) ? floor( (time() - $post->date_created ) / $day_in_secondes) : floor( (time() - $post->date_modified ) / $day_in_secondes) ;

        // crumbs infos
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum);
        if (! $forum) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        if ( $forum->post_expire > 0 and $dateDiff >= $forum->post_expire ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should be] $forum->post_expire > 0 and $dateDiff >= $forum->post_expire','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        if (jAuth::isConnected()) {
            if ( ! jAcl2::check('hfnu.posts.reply','forum'.$post->id_forum) ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }

            $form = jForms::create('havefnubb~posts',$thread_id);
            $form->removeControl('tags');
            $id_user = jAuth::getUserSession ()->id;
        }
        else {
            if ( ! jAcl2::check('hfnu.posts.reply','forum'.$post->id_forum)
                and
                 ! jAcl2::check('hfnu.posts.reply')
                ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
            $form = jForms::create('havefnubb~posts_anonym',$thread_id);
            $form->removeControl('tags');
            $id_user = 0;
        }

        $form->setData('id_user',$id_user);
        $form->setData('id_post',$id_post);
        $form->setData('id_forum',$post->id_forum);
        $form->setData('thread_id',$post->thread_id);
        $form->setData('subject',jLocale::get('havefnubb~post.subject.reply').' ' .jClasses::getService('havefnubb~hfnuposts')->getPost($id_post)->subject);
        $form->setData('message','');

        //set the needed parameters to the template
        $tpl = new jTpl();
        $tpl->assign('forum',$forum);
        $tpl->assign('id_post',$id_post);
        $tpl->assign('thread_id',$post->thread_id);
        $tpl->assign('id_forum', $forum->id_forum);
        $tpl->assign('previewtext', null);
        $tpl->assign('previewsubject',null);
        $tpl->assign('form', $form);
        $tpl->assign('forum', $forum);
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
        $thread_id  = (int) $this->param('thread_id');
        $id_post    = (int) $this->param('id_post');

        if ($thread_id == 0 ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should be not be 0 ] $thread_id','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $daoPost = jDao::get('havefnubb~posts');
        $post = $daoPost->get($id_post);

        if (!$post) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $post','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        if (jAuth::isConnected()) {
            if ( ! jAcl2::check('hfnu.posts.quote','forum'.$post->id_forum) ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
            $form = jForms::create('havefnubb~posts',$thread_id);
            $form->removeControl('tags');
            $id_user = jAuth::getUserSession ()->id;
        }
        else {
            if ( ! jAcl2::check('hfnu.posts.quote','forum'.$post->id_forum)
                and
                 ! jAcl2::check('hfnu.posts.quote')
                ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
            $form = jForms::create('havefnubb~posts_anonym',$thread_id);
            $form->removeControl('tags');
            $id_user = 0;
        }

        // crumbs infos
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum);
        if (! $forum) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }
        // check if the post is expired
        $day_in_secondes = 24 * 60 * 60;
        $dateDiff =  ($post->date_modified == '') ? floor( (time() - $post->date_created ) / $day_in_secondes) : floor( (time() - $post->date_modified ) / $day_in_secondes) ;

        if ( $forum->post_expire > 0 and $dateDiff >= $forum->post_expire ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should be] $forum->post_expire > 0 and $dateDiff >= $forum->post_expire','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $form->setData('subject',$post->subject);
        $form->setData('id_forum',$post->id_forum);
        $form->setData('id_user',$id_user);
        $form->setData('id_post',0);
        $form->setData('thread_id',$thread_id);

        $newMessage = ">".preg_replace("/\\n/","\n>",$post->message);

        if ( $post->login !== null)
            $quoteMessage = ">".$post->login.' ' .
                        jLocale::get('havefnubb~post.form.author.said') . "\n".
                        $newMessage;
        else
            $quoteMessage = ">".jLocale::get('havefnubb~member.guest').' ' .
                        jLocale::get('havefnubb~post.form.author.said') . "\n".
                        $newMessage;

        $form->setData('message',$quoteMessage);

        //set the needed parameters to the template
        $tpl = new jTpl();
        $tpl->assign('forum',       $forum);
        $tpl->assign('id_post',     0);
        $tpl->assign('thread_id',   $thread_id);
        $tpl->assign('id_forum',    $forum->id_forum);
        $tpl->assign('previewtext', null);
        $tpl->assign('previewsubject',null);
        $tpl->assign('form',        $form);
        $tpl->assign('forum',       $forum);
        $tpl->assign('heading',     jLocale::get("havefnubb~post.form.quote.message") . ' ' . $post->subject);
        $tpl->assign('submitAction','havefnubb~posts:savereply');

        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.quote.message") . ' ' . $post->subject;
        $tpl->assign('reply',1);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
        return $rep;
    }
    /**
    * save the datas posted from the reply form
    */
    function savereply() {
        $id_forum   = (int) $this->param('id_forum');

        if (jAuth::isConnected()) {
            if ( ! jAcl2::check('hfnu.posts.quote','forum'.$id_forum) and
                ! jAcl2::check('hfnu.posts.reply','forum'.$id_forum) ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
        } else {
            if ( ! jAcl2::check('hfnu.posts.quote','forum'.$id_forum) and
                ! jAcl2::check('hfnu.posts.quote') and
                ! jAcl2::check('hfnu.posts.reply','forum'.$id_forum) and
                ! jAcl2::check('hfnu.posts.reply')
                ) {
                jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
                $rep->setHttpStatus('403', 'Permission denied');
                return $rep;
            }
        }

        $id_user = jAuth::isConnected() ? jAuth::getUserSession ()->id : 0;

        $id_post    = (int) $this->param('id_post');
        $thread_id  = (int) $this->param('thread_id');

        $submit = $this->param('validate');
        // preview ?
        if ($submit == jLocale::get('havefnubb~post.form.previewBt') ) {
            if (jAuth::isConnected()) {
                $daoUser = jDao::get('havefnubb~member');
                $user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
            } else {
                $user = new StdClass;
                $user->member_comment = '';
            }
            //crumbs infos
            $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);

            if (jAuth::isConnected()) {
                $form = jForms::fill('havefnubb~posts',$thread_id);
                $id_user = jAuth::getUserSession ()->id;
            }
            else {
                $form = jForms::fill('havefnubb~posts_anonym',$thread_id);
                $id_user = 0;
            }

            $rep = $this->getResponse('redirect');

            if (!$form or !$form->check()) {
                jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
                $rep->action = 'default:index';
                return $rep;
            }

            $form->setData('id_forum',  $id_forum);
            $form->setData('id_user',   $id_user);
            $form->setData('id_post',   $id_post);
            $form->setData('thread_id', $thread_id);
            $form->setData('subject',   $form->getData('subject'));
            $form->setData('message',   $form->getData('message'));

            //set the needed parameters to the template
            $tpl = new jTpl();
            $tpl->assign('id_post',     0);
            $tpl->assign('thread_id',   $thread_id);
            $tpl->assign('id_forum',    $id_forum);
            $tpl->assign('previewsubject', $form->getData('subject'));
            $tpl->assign('previewtext', $form->getData('message'));
            $tpl->assign('form',        $form);
            $tpl->assign('forum',       $forum);
            $tpl->assign('signature',   $user->member_comment);

            $rep = $this->getResponse('html');
            $rep->title = jLocale::get('havefnubb~post.form.reply.message') . ' ' . $form->getData('subject');

            $tpl->assign('heading',jLocale::get('havefnubb~post.form.reply.message') . ' ' . $form->getData('subject'));
            $tpl->assign('reply', 1);
            $tpl->assign('submitAction','havefnubb~posts:savereply');

            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.edit'));
            return $rep;
        }
        // save ?
        elseif ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {
            $rep = $this->getResponse('redirect');

            if ($id_forum == 0 ) {
                jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
                $rep->action = 'havefnubb~default:index';
                return $rep;
            }

            //let's save the reply
            $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
            $record = $hfnuposts->savereply($thread_id,$id_post);

            if ($record === false ) {
                jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
                $record = $hfnuposts->getPost($thread_id);
                $forum = jDao::get('havefnubb~forum')->get($id_forum);
                $rep->action = 'havefnubb~posts:view';
                $rep->anchor = 'p'.$record->id_post;
                $rep->params = array('id_forum'  =>$id_forum,
                                     'ftitle'    =>$forum->forum_name,
                                     'id_post'   =>$record->id_post,
                                     'ptitle'    =>$record->subject,
                                     'thread_id' =>$record->thread_id);

            } else {
                jMessage::add(jLocale::get('havefnubb~main.common.reply.added'),'ok');
                $forum = jDao::get('havefnubb~forum')->get($id_forum);
                $rep->action ='havefnubb~posts:viewtogo';
                $rep->anchor = 'p'.$record->id_post;
                $rep->params = array('id_forum' =>$id_forum,
                                    'ftitle'    =>$forum->forum_name,
                                    'id_post'   =>$record->id_first_msg,
                                    'ptitle'    =>$hfnuposts->getPost(jDao::get('havefnubb~threads_alone')->get($record->thread_id)->id_first_msg)->subject,
                                    'thread_id' =>$record->thread_id,
                                    'go'        =>$record->id_post
                                    );
            }
            return $rep;
        }
        else {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this button that submit the form is not the expected one] the submit button is not save nor preview','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
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
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }
        //crumbs infos
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
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $id_forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }
        $dao = jDao::get('havefnubb~forum');
        $forum = $dao->get($id_forum);
        $rep = $this->getResponse('redirect');
        $rep->action='havefnubb~posts:lists';
        $rep->params=array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name);
        return $rep;
    }
    /**
     * provide a rss feeds for each forum
     */
    function rss() {

        $ftitle = jUrl::escape($this->param('ftitle'),true);
        $id_forum = (int) $this->param('id_forum');

        // if the forum is accessible by anonymous then the rss will be available
        // otherwise NO RSS will be available
        if ( ! jAcl2::check('hfnu.posts.rss','forum'.$id_forum) ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        if ($id_forum == 0 ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $id_forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $rep = $this->getResponse('rss2.0');

        $gJConfig = jApp::config();
        // entete du flux rss
        $rep->infos->title = $gJConfig->havefnubb['title'];
        $rep->infos->webSiteUrl= jApp::coord()->request->getServerURI();
        $rep->infos->copyright = $gJConfig->havefnubb['title'];
        $rep->infos->description = $gJConfig->havefnubb['description'];
        $rep->infos->updated = date('Y-m-d H:i:s');
        $rep->infos->published = date('Y-m-d H:i:s');
        $rep->infos->ttl=60;

        $dao = jDao::get('havefnubb~forum');
        $forum = $dao->get($id_forum);

        if (jUrl::escape($forum->forum_name,true) != $ftitle )
        {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be different] $forum->forum_name and $ftitle','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        // 1- limit of posts
        $nbPostPerPage = 0;
        $nbPostPerPage = (int)  $gJConfig->havefnubb['posts_per_page'];

        // 2- get the posts of the current forum, limited by point 1
        // get all the posts of the current Forum by its Id
        list($page,$nbPosts,$posts) = jClasses::getService('havefnubb~hfnuposts')->getThreads($id_forum,0,$nbPostPerPage);
        $first = true;
        foreach($posts as $post){

          if($first){
            // le premier enregistrement permet de connaitre
            // la date du channel
            $rep->infos->updated = date('Y-m-d H:i:s',$post->date_created);
            $rep->infos->published = date('Y-m-d H:i:s',$post->date_created);
            $first=false;
          }

          $url =jUrl::getFull('havefnubb~posts:view',
                        array('id_post'=>$post->id_post,
                            'thread_id'=>$post->thread_id,
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
     * provide a atom feeds for each forum
     */
    function atom() {

        $ftitle = jUrl::unescape($this->param('ftitle'),true);
        $id_forum = $this->intParam('id_forum');

        // if the forum is accessible by anonymous then the Atom will be available
        // otherwise NO Atom will be available
        if ( ! jAcl2::check('hfnu.posts.rss','forum'.$id_forum) ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        if ($id_forum == 0 ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $id_forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $rep = $this->getResponse('atom1.0');

        $gJConfig = jApp::config();
        // entete du flux atom
        $rep->infos->title = $gJConfig->havefnubb['title'];
        $rep->infos->webSiteUrl= jApp::coord()->request->getServerURI();
        $rep->infos->copyright = $gJConfig->havefnubb['title'];
        $rep->infos->description = $gJConfig->havefnubb['description'];
        $rep->infos->updated = date('Y-m-d H:i:s');
        $rep->infos->published = date('Y-m-d H:i:s');
        $rep->infos->selfLink= jUrl::get('havefnubb~posts:atom', array('ftitle'=>$ftitle,
                                                    'id_forum'=>$id_forum));
        $rep->infos->ttl=60;

        $dao = jDao::get('havefnubb~forum');
        $forum = $dao->get($id_forum);

        if (jUrl::escape($forum->forum_name,true) != $ftitle )
        {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be different] $forum->forum_name and $ftitle','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        // 1- limit of posts
        $nbPostPerPage = 0;
        $nbPostPerPage = (int)  $gJConfig->havefnubb['posts_per_page'];

        // 2- get the posts of the current forum, limited by point 1
        // get all the posts of the current Forum by its Id
        list($page,$nbPosts,$posts) = jClasses::getService('havefnubb~hfnuposts')->getThreads($id_forum,0,$nbPostPerPage);
        $first = true;
        foreach($posts as $post){

          if($first){
            // le premier enregistrement permet de connaitre
            // la date du channel
            $rep->infos->updated = date('Y-m-d H:i:s',$post->date_created);
            $rep->infos->published = date('Y-m-d H:i:s',$post->date_created);
            $first=false;
          }

          $url =jUrl::getFull('havefnubb~posts:view',
                        array('id_post'=>$post->id_post,
                            'thread_id'=>$post->thread_id,
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

          // on ajoute l'item dans le fil atom
          $rep->addItem($item);

        }
        return $rep;
    }
    /**
     * Show all new posts not read by the current user
     */
    public function shownew() {

        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => '',
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
        $nbPostPerPage = (int) jApp::config()->havefnubb['posts_per_page'];
        list($posts, $nbPosts) = jClasses::getService('havefnubb~hfnuposts')->findUnreadThread($page,$nbPostPerPage);

        $tpl = new jTpl();
        $rep = $this->getResponse('html');
        $tpl->assign('posts', $posts);
        $tpl->assign('nbPosts', $nbPosts);
        $tpl->assign('page',$page);
        $tpl->assign('nbPostPerPage',$nbPostPerPage);
        $tpl->assign('properties',$properties);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.shownew'));
        return $rep;
    }
}
