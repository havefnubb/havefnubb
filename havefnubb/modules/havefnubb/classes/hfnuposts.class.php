<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2011 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * main UI to manage the statement of the posts  of the forum HaveFnuBB!
 */
class hfnuposts {
    /**
     * the posts
     * @var array $posts
     */
    public $posts = array();
    /**
     * status of the newest post
     * @var array $postStatus
     */
    public $postStatus = array();
    /**
     * the authorized status of the post
     * 1 = 'pined'
     * 2 = 'pinedclosed'
     * 3 = 'opened' 
     * 4 = 'closed'
     * 5 = 'censored'
     * 6 = 'uncensored'
     * 7 = 'hidden');
     * @var array $statusAvailable
     */
    private $statusAvailable = array(1,2,3,4,5,6,7);
    /**
     * @var integer $hfAdmin the ID that defines the Admin
     */
    private $hfAdmin = 1;
    /**
     * @var integer $hfAdmin the ID that defines the Moderator
     */
    private $hfModerator = 3;

    /*********************************************************
     * This part handles the "add/delete/get" data of  posts *
     *********************************************************/


    /**
     * add the current post
     * @param  integer $id of the current post
     * @param  recordset $record of the current post to add
     */
    public function addPost($id,$record) {
        if (!isset($this->posts[$id]) and $id > 0)
            $this->posts[$id] = $record;
    }
    /**
     * get info of the current post
     * @param  integer $id of the current post
     * @return array composed by the post datas of the current post
     */
    public function getPost($id) {
        if (!isset($this->posts[$id]) and $id > 0)
            $this->posts[$id] = jDao::get('havefnubb~posts')->get($id);

        if ($id > 0)
            return $this->posts[$id];
        else {
            $this->posts[0] = array('id'=>0,'subject'=>'n/a');
        }
    }
    /**
     * get info of the current post that is not "hidden"
     * @param  integer $id of the current post
     * @return array composed by the post datas of the current post
     */
    public function getPostVisible($id) {
        if (!isset($this->posts[$id]) and $id > 0)
            $this->posts[$id] = jDao::get('havefnubb~posts')->getPostVisible($id);

        return $this->posts[$id];
    }

    /**
     * remove one post or complet thread from the database
     * if we drop a complet thread we update the forum table
     * with another "last" thread if any or to 0
     * @param integer $id_post  id post to remove
     * @return boolean of the success or not
     */
    public function delete($id_post) {
        if ($id_post == 0 ) return false;

        $this->deletePost($id_post);


        // A) Get the post i'm dropping
        $daoPost = jDao::get('havefnubb~posts');
        $post = $daoPost->get($id_post);

        if ($post !== false) {
            //whatever happened to this thread, we will update the forum table
            //sooner or later so keep it's id in a corner first
            $id_forum = $post->id_forum;

            //B) get the Thread which owns that post

            //search if it's first post of the thread
            $daoThreads = jDao::get('havefnubb~threads_alone');
            $daoThreadsRec = $daoThreads->getFirstIdPost($post->id_post);
            
            $nb_msg_to_remove_from_forum = 0;
            
            //if so we remove the entire thread
            if ($daoThreadsRec !== false) {
                // B1)  need to remove the count of posts for each user
                // so get the first and last id post
                // then get the user id of each post between the first and last
                // then send an Event HfnuPostBeforeDelete
                $start = $daoThreadsRec->id_first_msg;
                $end = $daoThreadsRec->id_last_msg;                
                for ($i=$start ; $i <= $end ; $i++ ) {
                    //get the user of this post
                    //the current cursor may not exist so we have to test
                    $user = jDao::get('havefnubb~posts')->getByIdAndIdThread($i,$post->thread_id);
                    //if the id_user is not false
                    //then "notify" to remove one post of his total
                    if ($user !== false) {
                        $nb_msg_to_remove_from_forum++;
                        // get the user record
                        $userRec = jDao::get('havefnubb~member')->getById($user->id_user);
                        // found one
                        if ($userRec !== false)
                            //remove one post
                            if ($userRec->nb_msg > 0)
                                jDao::get('havefnubb~member')->removeOneMsg($user->id_user);
                        // send an event
                        jEvent::notify('HfnuPostBeforeDelete',array('id_post'=>$i,'id_user'=>$user->id_user));
                    }
                }
                // B2) finally delete all the posts of the thread ...
                $daoPost->deleteSonsPost($post->thread_id);
                // B3) ...and delete the thread
                $daoThreads->delete($post->thread_id);

                // B4) Now get the "new" last thread id for the current forum
                $newThreadRec = $daoThreads->getLastThreadByIdForum($id_forum);

                // B4.a) no more thread in this forum, reset everything
                if ($newThreadRec === false ) {
                    $id_last_msg = 0;
                    $date_last_msg = 0;
                } else {
                // B4.b) we found one let's get those values
                    $id_last_msg = $newThreadRec->id_last_msg;
                    $date_last_msg = $newThreadRec->date_last_post;
                }
                // B5) update the the forum table
                $daoForum = jDao::get('havefnubb~forum');
                $forumRec = $daoForum->get($id_forum);
                $forumRec->id_last_msg = $id_last_msg;
                $forumRec->date_last_msg = $date_last_msg;
                $forumRec->nb_msg       = $forumRec->nb_msg - $nb_msg_to_remove_from_forum;
                $forumRec->nb_thread    = $forumRec->nb_thread -1;
                
                $daoForum->update($forumRec);

            // C) otherwise drop the one inside the thread
            } else {
                // send an event
                jEvent::notify('HfnuPostBeforeDelete',array('id_post'=>$id_post,'id_user'=>$post->id_user));
                //delete the post
                jDao::get('havefnubb~posts')->delete($id_post);
            }

        } else {
            return false;
        }
        return true;
    }
    /**
     * delete a post from the array $posts
     * @param  integer $id of the current post
     * @return boolean
     */
    public function deletePost($id) {
        if (isset($this->posts[$id]) and $id > 0) {
            $this->posts = array_pop($this->posts);
        }
    }
    /**
     * get the threads list of the given forum
     * @param integer $id_forum the current forum
     * @param integer $page the current page
     * @param integer $nbPostPerPage the number of posts per page
     * @return array $page,$nbPosts,$posts if no record have been found, return page = 0 otherwise return the posts
     */
    public function getThreads($id_forum,$page,$nbPostPerPage) {
        $daoThreads = jDao::get('havefnubb~threads_alone');

        $nbPosts = $daoThreads->countThreadsByIdForum($id_forum);
        // get the posts of the current forum
        list($page,$posts) = $this->getThreadsList($id_forum,$page,$nbPostPerPage);

        return array($page,$nbPosts,$posts);
    }
    /**
     * get the thread
     * @param integer $thread_id current thread
     * @return integer $thread_id return the current thread
     */
    public function getThread($thread_id) {
        if (!isset($this->threads[$thread_id]) and $thread_id > 0)
            $this->threads[$thread_id] = jDao::get('havefnubb~threads_alone')->get($thread_id);

        return $this->threads[$thread_id];
    }

    /****************************************************
     * This part handles the "view" statement of a post *
     ****************************************************/


    /**
     * view a given thread !
     * business check :
     * 1) do those id exist ?
     * 2) permission ok ?
     * 3) if thread_id > 0 then calculate the page + assign id_post with thread_id
     * business update :
     * 1) update the count of view of this thread
     * @param integer $id_post id of the current post
     * @param integer $thread_id thread id of the current post
     * @return array of id_post, DaoRecord of Post, Paginator
     */
    public function view($id_post,$thread_id) {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $post = $this->getPostVisible($id_post);
        }
        else
            $post = $this->getPost($id_post);

        if ($id_post == 0 or $post === false) {
            return array(null,null,null);
        }

        if ( ! $this->checkPerm('hfnu.posts.view','forum'.$post->id_forum) ) {
            return array(null,null,null);
        }

        $goto = 0;
        if ($thread_id > 0) {
            // the number of post between the current post_id and the thread_id
            $nbReplies = (int) jDao::get('havefnubb~threads_alone')->get($thread_id)->nb_replies + 1; // add 1 because nb_replies does not count the "parent" post

            $nbRepliesPerPage = (int) jApp::config()->havefnubb['replies_per_page'];
            // calculate the offset of this id_post
            $goto = (ceil ($nbReplies/$nbRepliesPerPage) * $nbRepliesPerPage) - $nbRepliesPerPage;

            if ($goto < 0 ) $goto = 0;
        }

        // let's update the viewed counter
        $this->updateViewing($id_post,$thread_id);
        // let's update the 'read by mod'
        $this->readByMod($thread_id);
        // let's add the user to the post_read table
        jClasses::getService('havefnubb~hfnuread')->insertReadPost($post,time());

        return array($id_post,$post,$goto,$nbReplies);
    }
    /**
     * updateViewing : update the counter of the views of a given post
     * @param integer $id_post post id of the current post
     * @param integer $thread_id the thread id
     */
    public function updateViewing($id_post,$thread_id) {
        if ($id_post == 0 ) return;
        $dao = jDao::get('havefnubb~posts');
        $post = $dao->get($id_post);
        if ($post !== false)  {
            $post->viewed = $post->viewed +1;
            $dao->update($post);
        }
        $dao = jDao::get('havefnubb~threads');
        $thread = $dao->get($thread_id);
        if ($thread !== false)  {
            $thread->nb_viewed = $thread->nb_viewed +1;
            $dao->update($thread);
        }
    }


    /**
     * readByMod : update the 'read by mod' flag
     * @param integer $thread_id thread id of the post that will by marked as read by a moderator
     */
    public function readByMod($thread_id) {
        if ($thread_id == 0 ) return;
        if (jAcl2DbUserGroup::isMemberOfGroup($this->hfModerator) or
            jAcl2DbUserGroup::isMemberOfGroup($this->hfAdmin) ) {
            jDao::get('havefnubb~posts')->updateReadByMod($thread_id);
        }
    }


    /****************************************************
     * This part handles the "save" statement of a post *
     ****************************************************/


    /**
     * save one post
     * @param integer $id_forum id forum of the post
     * @param integer $id_post  id post of the current post if editing of 0 if adding
     * @return mixed boolean or $id_post id post of the editing post or the id of the post created
     */
    public function save($id_forum,$id_post=0) {
        if (jAuth::isConnected()) {
            $form = jForms::fill('havefnubb~posts',$id_post);
            $id_user= jAuth::getUserSession ()->id;
        }
        elseif (jApp::config()->havefnubb['anonymous_post_authorized'] == 1) {
            $form = jForms::fill('havefnubb~posts_anonym',$id_post);
            $id_user = 0;
        }
        if (!$form or !$form->check()) {
            return false;
        }

        //.. if the data are ok ; we get them !
        $subject = $form->getData('subject');
        $message = $form->getData('message');

        if (count($message) > jApp::config()->havefnubb['post_max_size'] and
                jApp::config()->havefnubb['post_max_size'] > 0) {
            jMessage::add(jLocale::get('havefnubb~main.message.exceed.maximum.size',
                        array(jApp::config()->havefnubb['post_max_size'])),'error');
            return false;
        }

        //CreateRecord object
        $dao = jDao::get('havefnubb~posts');
        $datePost = time();
        // create a post
        if ($id_post == 0) {
            jEvent::notify('HfnuPostBeforeSave',array('id'=>$id_post));
            $record = jDao::createRecord('havefnubb~posts');
            $record->subject        = $subject;
            $record->message        = $message;
            $record->id_post        = $id_post;
            $record->id_user        = $id_user;
            $record->id_forum       = $id_forum;
            $record->thread_id      = 0;
            $record->status         = 3; //'opened'
            $record->date_created   = $datePost;
            $record->date_modified  = $datePost;
            $record->viewed         = 0;
            $record->ispined        = 0;
            $record->iscensored     = 0;
            $record->poster_ip      = $_SERVER['REMOTE_ADDR'];
            //if the current user is a member of a moderator group
            // we set this post as 'read by moderator'
            if (jAcl2DbUserGroup::isMemberOfGroup($this->hfAdmin) or
                jAcl2DbUserGroup::isMemberOfGroup($this->hfModerator) ) {
                $record->read_by_mod = 1;
            }
            else {
                $record->read_by_mod = 0;
            }

            $dao->insert($record);

            $threadDao = jDao::get('havefnubb~threads');
            $threadRec = jDao::createRecord('havefnubb~threads');
            $threadRec->id_user_thread  = $id_user;
            $threadRec->status_thread   = 3; //'opened'
            $threadRec->id_forum_thread = $id_forum;
            $threadRec->nb_replies      = 0;
            $threadRec->nb_viewed       = 0;
            $threadRec->id_first_msg    = $record->id_post;
            $threadRec->id_last_msg     = $record->id_post;
            $threadRec->date_created    = $datePost;
            $threadRec->date_last_post  = $datePost;
            $threadRec->ispined_thread  = 0;
            $threadRec->iscensored_thread= 0;
            $threadDao->insert($threadRec);

            // now let's get the inserted id to put this one in thread_id column !
            $record->thread_id = $threadRec->id_thread;
            $dao->update($record);
            $id_post = $record->id_post;
            $thread_id = $record->thread_id;

            //update Forum record
            $forum = jDao::get('havefnubb~forum');
            $forumRec               = $forum->get($id_forum);
            $forumRec->id_last_msg  = $id_post;
            $forumRec->date_last_msg = $datePost;
            $forumRec->nb_msg       = $forumRec->nb_msg+1;
            $forumRec->nb_thread    = $forumRec->nb_thread+1;
            $forum->update($forumRec);

            $this->addPost($id_post,$record);

            jEvent::notify('HfnuPostAfterInsert',array('id'=>$threadRec->id_thread,'id_forum'=>$id_forum));

        }
        // edit a post
        else {
            jEvent::notify('HfnuPostBeforeUpdate',array('id'=>$id_post,'id_forum'=>$id_forum));

            //remove the id_post of the array
            $this->deletePost($id_post);

            $record = $dao->get($id_post);
            $record->subject        = $subject;
            $record->message        = $message;
            $record->date_modified  = time();
            $thread_id = $record->thread_id;
            jEvent::notify('HfnuPostAfterUpdate',array('id'=>$id_post,'id_forum'=>$id_forum));

            // add the new record to the array
            $this->addPost($id_post,$record);
        }

        // in all cases (id_post = 0 or not )
        // we have to update as we store the last insert id in the thread_id column

        $dao->update($record);

        jEvent::notify('HfnuPostAfterSave',array('id'=>$id_post,'id_forum'=>$id_forum));

        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));

        $tagStr ='';
        $tagStr = str_replace('.',' ',$form->getData("tags"));
        $tags = explode(",", $tagStr);

        //add this post as already been read
        jClasses::getService('havefnubb~hfnuread')->insertReadPost($record,$datePost);

        jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'forumscope', $id_post);

        //subscription management
        if ($form->getData('subscribe') == 1) {
            jClasses::getService('havefnubb~hfnusub')->subscribe($thread_id);
        }
        else {
            jClasses::getService('havefnubb~hfnusub')->unsubscribe($thread_id);
        }

        jForms::destroy('havefnubb~posts', $id_post);

        return $record;
    }

    /**
     * save a reply to one post
     * @param integer $thread_id thread id of the current post if editing of 0 if adding
     * @return mixed boolean / DaoRecord $record of the reply
     */
    public function savereply($thread_id,$id_post) {
        $form = false;
        if (jAuth::isConnected()) {
            $form = jForms::fill('havefnubb~posts',$thread_id);
            $id_user = jAuth::getUserSession ()->id;
        }
        else {
            $form = jForms::fill('havefnubb~posts_anonym',$thread_id);
            $id_user = 0;
        }

        if (!$form or !$form->check()) {
            return false;
        }

        $message = $form->getData('message');
        //is the size of the message limited ?
        if ( strlen($message) > jApp::config()->havefnubb['post_max_size']
                and  jApp::config()->havefnubb['post_max_size'] > 0)
            {
            jMessage::add(jLocale::get('havefnubb~main.message.exceed.maximum.size',
                                array(jApp::config()->havefnubb['post_max_size'])),'error');
            return false;
        }

        jEvent::notify('HfnuPostBeforeSaveReply',array('id'=>$thread_id));

        //get the thread record to keep the status of the thread and apply it
        //to this new reply.
        $threadRec = jDao::get('havefnubb~threads')->get($thread_id);
        if ($threadRec === false) {
            return false;
        }

        $dateReply = time();
        $result = $form->prepareDaoFromControls('havefnubb~posts');
        $result['daorec']->thread_id    = $thread_id;
        $result['daorec']->date_created = $dateReply;
        $result['daorec']->date_modified= $dateReply;
        $result['daorec']->status       = $threadRec->status;//'opened'
        $result['daorec']->poster_ip    = $_SERVER['REMOTE_ADDR'];
        $result['daorec']->viewed       = 0;
        $result['daorec']->id_post      = 0;
        $result['daorec']->id_user      = $id_user;
        $result['daorec']->iscensored   = $threadRec->iscensored;
        $result['daorec']->ispined      = $threadRec->ispined;
        $result['dao']->insert($result['daorec']);
        $id_post = $result['daorec']->getPk();

        $this->addPost($id_post,$result['daorec']);

        //update Threads record
        $threads = jDao::get('havefnubb~threads');
        $threadRec = $threads->get($thread_id);
        $threadRec->nb_replies      = $threadRec->nb_replies +1;
        $threadRec->nb_viewed       = $threadRec->nb_viewed +1;
        $threadRec->id_last_msg     = $id_post;
        $threadRec->date_last_post  = $dateReply;
        $threads->update($threadRec);

        //update Forum record
        $forum = jDao::get('havefnubb~forum');
        $forumRec = $forum->get($threadRec->id_forum);
        $forumRec->id_last_msg  = $id_post;
        $forumRec->date_last_msg = $dateReply;
        $forumRec->nb_msg       = $forumRec->nb_msg+1;
        $forumRec->nb_thread    = $forumRec->nb_thread+1;        
        $forum->update($forumRec);

        //add a "fake" column just to return it to the posts controller
        // and then being able to redirect to the correct page where this
        // post has been added
        $result['daorec']->id_first_msg = $threadRec->id_first_msg;

        jEvent::notify('HfnuPostAfterSaveReply',array('id_post'=>$id_post));

        //add this post as already been read
        jClasses::getService('havefnubb~hfnuread')->insertReadPost($result['daorec'],$dateReply);

        if ( $form->getData('subscribe') == 1 ) {
            //subscribe to a post
            jClasses::getService('havefnubb~hfnusub')->subscribe($thread_id);
            //send message to anyone who subscribes to this thread
        }
        jClasses::getService('havefnubb~hfnusub')->sendMail( $thread_id );

        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));

        jForms::destroy('havefnubb~posts', $thread_id);

        return $result['daorec'];
    }

    /**
     * save a notification posted by a user
     * @param integer $id_post id post of the current post if editing of 0 if adding
     * @return boolean status of success of this submit
     */
    public function savenotify($id_post,$thread_id) {

        $form = jForms::fill('havefnubb~notify',$id_post);
        if (!$form) {
            return false;
        }
        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }

        jEvent::notify('HfnuPostBeforeSaveNotify',array('id'=>$id_post));
        $dao = jDao::get('havefnubb~notify')->getNotifByUserId($id_post,$form->getData('id_user'));

        if ($dao > 0) {
            jMessage::add(jLocale::get('havefnubb~post.notification.already.done'),'error');
            return false;
        }

        $result = $form->prepareDaoFromControls('havefnubb~notify');
        $result['daorec']->thread_id    = $thread_id;
        $result['daorec']->subject      = $this->getPost(jDao::get('havefnubb~threads_alone')->get($thread_id)->id_last_msg)->subject;
        $result['daorec']->message      = '['.$form->getData('reason').'] ' .$form->getData('message');
        $result['daorec']->date_created	= time();
        $result['daorec']->date_modified= time();
        $result['dao']->insert($result['daorec']);

        jEvent::notify('HfnuPostAfterSaveNotify',array('id'=>$id_post));

        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));

        jForms::destroy('havefnubb~notify', $id_post);

        return true;
    }


    /************************************************
     * This part handles the status a post can have *
     ************************************************/

    /**
     * change the status of the current THREAD (not just one post) !
     * @param integer $thread_id id of the thread
     * @param string $status the status to switch to
     * @return DaoRecord $record
     */
    public function switchStatus($thread_id,$id_post,$status) {

        if (! in_array($status,$this->statusAvailable)) {
            jMessage::add(jLocale::get('havefnubb~post.invalid.status'),'error');
            return false;
        }

        if ( $thread_id < 0 ) return false;

        if ( jDao::get('havefnubb~posts')->updateStatusByThreadId($thread_id,$status) )
            jEvent::notify('HfnuPostAfterStatusChanged',array('id'=>$thread_id,'status'=>$status));

        $daoThread = jDao::get('havefnubb~threads');
        $rec = $daoThread->get($thread_id);
        $rec->status_thread = $status;
        if ($status == 1 or $status == 2)
            $rec->ispined_thread = 1;
        else
            $rec->ispined_thread = 0;
        $daoThread->update($rec);

        return $this->getPost($id_post);
    }
    /**
     * this function permits to get the status of the posts
     * @param integer $id_post id post
     * @return array $postStatus return the status of the given post
     */
    public function getPostStatus($id_post) {
        if (!isset($this->poststatus[$id_post]))
            $this->poststatus[$id_post] = jDao::get('havefnubb~newest_posts')->getPostStatus($id_post);
        return $this->poststatus[$id_post];
    }
    /**
     * censor the current POST
     * @param integer $thread_id parent id of the thread
     * @param integer $id_post post id of the thread
     * @param string $status the status to switch to
     * @param string $censor_msg the censored message
     */
    public function censor($thread_id,$id_post,$censor_msg) {
        if ( $thread_id < 0 or $id_post < 1) return false;
        $return = false;
        $dao = jDao::get('havefnubb~posts');
        if ( $dao->censorIt($id_post,$censor_msg,jAuth::getUserSession ()->id) ) {
            jEvent::notify('HfnuPostAfterStatusChanged',
                           array('id'=>$id_post,
                                 'status'=>5)
                           );
            $daoThread = jDao::get('havefnubb~threads');
            $rec = $daoThread->get($thread_id);
            $rec->iscensored = 1;
            $daoThread->update($rec);

            $return = $this->getPost($id_post);
        }
        return $return;
    }
    /**
     * remove the censor of current POST
     * To uncensor :
     * 1) get the status of the Father Post
     * 2) apply the Father's status to the Son Post
     * @param integer $thread_id id of the thread
     * @param integer $id_post integer parent id of the thread
     * @param string $status string the status to switch to
     * @param string $censor_msg string of the censored message
     */
    public function uncensor($thread_id,$id_post) {
        if ( $thread_id < 0 or $id_post < 1) return false;
        $return = false;
        $dao = jDao::get('havefnubb~posts');
        $status = ( $id_post == $thread_id ) ? 3 : 5 ;
        if ( $dao->unCensorIt($id_post) ) {
            jEvent::notify('HfnuPostAfterStatusChanged',
                           array('id'       =>$id_post,
                                 'status'   =>$status)
                           );

            $daoThread = jDao::get('havefnubb~threads');
            $rec = $daoThread->get($thread_id);
            $rec->iscensored = 0;
            $daoThread->update($rec);

            $return = $this->getPost($id_post);
        }
        return $return;
    }


    /*************************************************
     * This part Handle the "movement" a post can do *
     *  from forum to forum and thread to thread     *
     *************************************************/


    /**
     * this function permits to move a complet thread to another forum
     * @param integer $thread_id thread id  to move
     * @param integer $id_forum id forum to move to
     * @return boolean
     */
    public function moveToForum($thread_id,$id_forum) {
        if ($thread_id == 0 or $id_forum == 0) return false;

        jDao::get('havefnubb~posts')->moveToForum($thread_id,$id_forum);

        $daoThreads = jDao::get('havefnubb~threads_alone');
        $threadRec = $daoThreads->get($thread_id);
        $threadRec->id_forum_thread=$id_forum;
        $daoThreads->update($threadRec);

        return true;
    }

    /**
     * this function permits to split the thread to a forum
     * 1) we create the first post and then the thread
     * 2) we remove all the posts of the thread from the choosen id
     * 3) we delete the number of posts + thread from the thread table
     * 4) we delete the number of posts + thread from the forum table
     * 5) we add the number of posts + thread to the thread table in the new forum
     * 6) we add the number of posts + thread to the forum table in the new forum
     * @param integer $thread_id thread
     * @param integer $id_post id post
     * @param integer $id_forum id forum
     * @return integer $id_post_new the new Id
     */
    
    //@TODO : update the id_last_msg in the forum table to avoid "no msg" sentance
    
    public function splitToForum($thread_id,$id_post,$id_forum) {
        if ($id_post == 0 or $id_forum == 0 or $thread_id == 0) return false;
        $dao = jDao::get('havefnubb~posts');

        $datas = $dao->findAllFromCurrentIdPostWithThreadId($thread_id,$id_post);

        $recCount = $datas->rowCount();
        
        $i                  = 0;
        $id_post_new        = 0;
        $id_thread_inserted = 0;
        $id_forum_old       = 0;
        //1) we create the first post and then the thread
        foreach($datas as $data) {
            //the id forum where the post comes from
            $id_forum_old = $data->id_forum;

            $record = jDao::createRecord('havefnubb~posts');
            $record = $data;
            $record->id_post = 0;//to create a new record !
            $record->id_forum = $id_forum; // the id forum where we want to move this post
            // we only set thread_id to id_post for the first post which becomes the parent !

            if ($i == 0 ) {
                // create a new thread
                $threadDao = jDao::get('havefnubb~threads');
                $threadRec = jDao::createRecord('havefnubb~threads');
                $threadRec->id_user_thread  = $record->id_user;
                $threadRec->status_thread   = $record->status;
                $threadRec->id_forum_thread = $id_forum;
                $threadRec->nb_replies      = 0;
                $threadRec->nb_viewed       = 0;
                $threadRec->date_created    = $record->date_created;
                $threadRec->date_last_post  = $record->date_created;
                $threadRec->ispined_thread  = $record->ispined;
                $threadRec->iscensored_thread= $record->iscensored;
                $threadRec->id_first_msg    = 0;
                $threadRec->id_last_msg     = 0;
                $threadDao->insert($threadRec);

                // now let's get the inserted id to put this one in thread_id column !

                $id_thread_inserted = $threadRec->id_thread;
                $record->thread_id  = $id_thread_inserted;

                $dao->insert($record); // create the new record

                $id_post_new        = $record->id_post;
                $id_last_msg        = $record->id_post;
                $date_created       = $record->date_created;

            }
            elseif ($i > 0 ) {
                $record->thread_id = $id_post_new;
                $dao->insert($record); // create the new record
                $id_last_msg  = $record->id_post;
                $date_created = $record->date_created;
            }

            $i++;
        }
        // 2) we remove all the posts of the thread from the choosen id
        // delete the old records
        $dao->deleteAllFromCurrentIdPostWithThreadId($thread_id,$id_post);

        // 3) we delete the number of posts + thread from the thread table
        $threadDao = jDao::get('havefnubb~threads_alone');
        $threadRec = $threadDao->get($thread_id);
        
        // info needed for forum table
        $nb_posts = 0;
        $date_last_msg = $threadRec->date_last_post;
        // info needed for forum table
        
        if ($threadRec->nb_replies > 0 ) { 
            $nb_posts = $threadRec->nb_replies - $i;
            $threadRec->nb_replies -= $i;            
        }
        else $nb_posts = 1;
        //need to get the last comment
        if ($dao->getUserLastCommentOnForums($id_forum_old) !== false)
            $threadRec->id_last_msg = $dao->getUserLastCommentOnForums($id_forum_old)->id_post;
        else 
            $threadRec->id_last_msg = 0;
        
        $threadDao->update($threadRec);
        // 4) we delete the number of posts + thread from the forum table
        $forumDao = jDao::get('havefnubb~forum');
        $forumRec = $forumDao->get($id_forum_old);
        $forumRec->nb_msg       -= $nb_posts;
        $forumRec->nb_thread    -= 1;
        $forumRec->date_last_msg = $date_last_msg;
        $forumDao->update($forumRec);

        // 5) we add the number of posts + thread to the thread table in the new forum
        $threadDao = jDao::get('havefnubb~threads');
        $threadRec = $threadDao->get($id_thread_inserted);
        $threadRec->id_first_msg = $id_post_new;
        $threadRec->id_last_msg  = $id_last_msg;
        $threadDao->update($threadRec);

        
        // 6) we add the number of posts + thread to the forum table in the new forum
        $forumDao                   = jDao::get('havefnubb~forum');
        $forumRec                   = $forumDao->get($id_forum);
        // we only update the date if the new thread we move is newer 
        // than the last date of the existing msg we already have in 
        // the forum table
        if ($forumRec->date_last_msg    < $date_created) {
            $forumRec->id_last_msg      = $id_last_msg;        
            $forumRec->date_last_msg    = $date_created;
        }
        $forumRec->nb_msg           += $nb_posts;
        $forumRec->nb_thread        += 1;
        $forumDao->update($forumRec);
        // get the id_post of the previous post
        // then update the thread table with its info (last_msg id + date)
        return $id_post_new;
    }

    /**
     * this function permits to split the thread to another thread
     * @param integer $id_post id post to split
     * @param integer $thread_id thread  of the current id post
     * @param integer  $new_thread_id parent id to attach to
     * @return boolean
     */
    public function splitToThread($id_post,$thread_id,$new_thread_id) {
        if ($id_post == 0 or $thread_id == 0 or $new_thread_id == 0) return false;

        $dao = jDao::get('havefnubb~posts');
        $datas = $dao->findAllFromCurrentIdPostWithThreadId($thread_id,$id_post);
        $i = 0;
        foreach($datas as $data) {
            $i++;
            $record = jDao::createRecord('havefnubb~posts');
            $record = $data;
            $record->id_post = null;//to create a new record !
            $record->id_forum = $data->id_forum; // the id forum of the same forum
            $record->thread_id = $new_thread_id; // the id of the parent id on which we link the thread

            $dao->insert($record);
        }

        $dao->deleteAllFromCurrentIdPostWithThreadId($thread_id,$id_post); // delete the old records

        //Thread Update process :
        $daoThreads = jDao::get('havefnubb~threads_alone');

        // 1) add to the "target" Thread all the infos
        $threadRec = $daoThreads->get($new_thread_id);
        $threadRec->nb_replies += $i;
        $threadRec->id_last_msg = $dao->getLastCreatedPostByThreadId($new_thread_id)->id_post;
        $id_first_msg = $threadRec->id_first_msg;
        $daoThreads->update($threadRec);

        // 2) remove from the "source" Thread all the infos
        $threadRec = $daoThreads->get($thread_id);
        if ($threadRec->nb_replies > 0 )
            $threadRec->nb_replies -= $i;
        if ($dao->getLastCreatedPostByThreadId($thread_id) === false )
            $daoThreads->delete($thread_id);
        else {
            $threadRec->id_last_msg = $dao->getLastCreatedPostByThreadId($thread_id)->id_post;
            $daoThreads->update($threadRec);
        }
        //return the id of the first msg of the thread
        // where we have to go to after we moved all the data
        // of the old post
        return $id_first_msg;
    }

    /******************************************************************
     * This part handles others behaviors of post/information of post *
     ******************************************************************/

    /**
     * check the permissions/rights to the resources
     * @param string $rights the rights to check to the resource
     * @param string $resources the resource to check
     * @return boolean
     */
    public function checkPerm($rights,$ressources) {
        return jAcl2::check($rights,$ressources) ? true : false;
    }

    /**
     * get the list of the unread post by any moderator
     * @return data DaoData
     */
    public function findUnreadThreadByMod() {
        return jDao::get('havefnubb~threads')->findUnreadThreadByMod();
    }


    const selectPosts = "SELECT threads.id_thread,
                    threads.id_user as id_user_thread,
                    threads.id_forum as id_forum_thread,
                    threads.status as status_thread,
                    threads.nb_viewed,
                    threads.nb_replies,
                    threads.id_first_msg,
                    threads.id_last_msg,
                    threads.date_created,
                    threads.date_last_post,
                    threads.ispined as ispined_thread,
                    threads.iscensored as iscensored_thread,
                    posts.id_post,
                    posts.id_user,
                    posts.id_forum,
                    posts.thread_id,
                    posts.status,
                    posts.ispined,
                    posts.iscensored,
                    posts.subject,
                    posts.message,
                    posts.date_created as p_date_created,
                    posts.date_modified,
                    posts.viewed,
                    posts.poster_ip,
                    posts.censored_msg,
                    posts.read_by_mod,
                    usr.id,
                    usr.email,
                    usr.login,
                    usr.nickname,
                    usr.comment as member_comment,
                    usr.town as member_town,
                    usr.country as member_country,
                    usr.avatar as member_avatar,
                    usr.gravatar as member_gravatar,
                    usr.website as member_website,
                    usr.show_email as member_show_email,
                    usr.nb_msg,
                    usr.last_post as member_last_post,
                    forum.parent_id as forum_parent_id,
                    forum.forum_name";

    public function getThreadsList($id_forum,$page,$nbPostPerPage) {
        $c = jDb::getConnection();
        $from = " FROM ".$c->prefixTable('hfnu_threads')." AS threads
                    LEFT JOIN ".$c->prefixTable('community_users')." AS usr ON ( threads.id_user=usr.id)
                    LEFT JOIN ".$c->prefixTable('hfnu_forum')." AS forum ON ( threads.id_forum=forum.id_forum)";
        $where = ", ".$c->prefixTable('hfnu_posts')." AS posts
                WHERE threads.id_first_msg = posts.id_post AND
                    posts.id_forum = '".$id_forum."'";

        if ( ! jAuth::isConnected())
            $sql = self::selectPosts.$from.$where;
        else
            $sql = self::selectPosts.", rp.date_read as date_read_post ".$from."
                    LEFT JOIN ".$c->prefixTable('hfnu_read_posts')." as rp
                        ON ( threads.id_forum=rp.id_forum AND
                            threads.id_thread=rp.thread_id AND
                            rp.id_user = '".jAuth::getUserSession ()->id."')".$where;

        // if the user is not an admin then we hide the "hidden" posts
        if ( ! jAcl2::check('hfnu.admin.post') )
            $sql .= "AND posts.status <> 7 ";

        $sql .= " ORDER BY threads.ispined desc, threads.date_last_post desc ";

        $posts = $c->limitQuery($sql, $page,$nbPostPerPage);
        if ($posts->rowCount() == 0) {
            $posts = $c->limitQuery($sql, 0,$nbPostPerPage);
            $page = 0;
        }

        return array($page,$posts);
    }

    public function findByThreadId($thread_id,$page,$nbRepliesPerPage) {
        $c = jDb::getConnection();

        $from = " FROM ".$c->prefixTable('hfnu_threads')." AS threads
                    LEFT JOIN ".$c->prefixTable('hfnu_forum')." AS forum ON ( threads.id_forum=forum.id_forum)";
        $where = ", ".$c->prefixTable('hfnu_posts')." AS posts
                    LEFT JOIN ".$c->prefixTable('community_users')." AS usr ON ( posts.id_user=usr.id)
                WHERE threads.id_thread = posts.thread_id AND
                      posts.thread_id = '".$thread_id."'";

        $sql = self::selectPosts.$from.$where;

        // if the user is not an admin then we hide the "hidden" posts
        if ( ! jAcl2::check('hfnu.admin.post') )
            $sql .= "AND posts.status <> 7 ";

        $sql .= " ORDER BY p_date_created asc";


        $posts = $c->limitQuery($sql, $page,$nbRepliesPerPage);
        if ($posts->rowCount() == 0) {
            $posts = $c->limitQuery($sql, 0,$nbRepliesPerPage);
            $page = 0;
        }

        return array($page,$posts);
    }

    /**
     * this function get the messages that the current user does not read yet between his last connection and last 15min
     * then return the given record
     * @return array : the limited records + total of records
     */
    public function findUnreadThread($page=0,$nbPostPerPage=25) {
        if ( !jAuth::isConnected() )
            return array(0,0);

        // let's find the threads we have not read yet
        $c = jDb::getConnection();
        $sql = " FROM ".$c->prefixTable('hfnu_threads')." AS threads
                LEFT JOIN ".$c->prefixTable('community_users')." AS usr ON ( threads.id_user =usr.id)
                LEFT JOIN ".$c->prefixTable('hfnu_forum')." AS forum ON ( threads.id_forum=forum.id_forum)
                LEFT JOIN ".$c->prefixTable('hfnu_read_posts')." as rp ON ( threads.id_forum=rp.id_forum AND
                                                                threads.id_thread=rp.thread_id AND
                                                                rp.id_user = '".jAuth::getUserSession ()->id."')
                LEFT JOIN ".$c->prefixTable('hfnu_read_forum')." as rf ON ( threads.id_forum=rf.id_forum AND
                                                                rf.id_user = '".jAuth::getUserSession ()->id."')
            , ".$c->prefixTable('hfnu_posts')." AS posts
            WHERE threads.id_last_msg = posts.id_post
                AND (
                    (
                            rp.date_read IS NOT NULL
                        AND rf.date_read IS NOT NULL
                        AND (
                                (date_last_post > rp.date_read AND  rp.date_read > rf.date_read)
                            OR  (date_last_post > rf.date_read AND  rf.date_read >= rp.date_read)
                        )
                    )
                    OR (
                            rp.date_read IS NULL
                        AND rf.date_read IS NOT NULL
                        AND date_last_post > rf.date_read
                    )
                    OR (
                            rp.date_read IS NOT NULL
                        AND rf.date_read IS NULL
                        AND date_last_post > rp.date_read
                    )
                    OR (rp.date_read IS NULL AND rf.date_read IS NULL)
                    )";

        if ( ! jAcl2::check('hfnu.admin.post') )
            $sql .= " AND posts.status <> 7 ";

        $order = " ORDER BY threads.date_last_post desc";

        $count = $c->query("SELECT count(threads.id_thread) as c ".$sql);
        $nbPosts = $count->fetch()->c;

        $posts = $c->limitQuery(self::selectPosts.$sql.$order, $page,$nbPostPerPage);
        if ($posts->rowCount() == 0) {
            $posts = $c->limitQuery(self::selectPosts.$sql.$order, 0,$nbPostPerPage);
            $page = 0;
        }
        return array($posts,$nbPosts);
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param integer $id_forum the current id forum
     * @return boolean
     */
    public function getCountUnreadThreadbyForumId($id_forum) {
        if ( jAuth::isConnected() and $id_forum > 0) {
            // let's find the threads we have not read yet
            $c = jDb::getConnection();
            $sql = "SELECT count(threads.id_thread) as c
            FROM ".$c->prefixTable('hfnu_threads')." AS threads
                    LEFT JOIN ".$c->prefixTable('hfnu_read_posts')." as rp ON ( threads.id_forum=rp.id_forum AND
                                                                    threads.id_thread=rp.thread_id AND
                                                                    rp.id_user = '".jAuth::getUserSession ()->id."')
                    LEFT JOIN ".$c->prefixTable('hfnu_read_forum')." as rf ON ( threads.id_forum=rf.id_forum AND
                                                                    rf.id_user = '".jAuth::getUserSession ()->id."')
                WHERE threads.id_forum = '".$id_forum."'
                    AND (
                    (
                            rp.date_read IS NOT NULL
                        AND rf.date_read IS NOT NULL
                        AND (
                                (date_last_post > rp.date_read AND  rp.date_read > rf.date_read)
                            OR  (date_last_post > rf.date_read AND  rf.date_read >= rp.date_read)
                        )
                    )
                    OR (
                            rp.date_read IS NULL
                        AND rf.date_read IS NOT NULL
                        AND date_last_post > rf.date_read
                    )
                    OR (
                            rp.date_read IS NOT NULL
                        AND rf.date_read IS NULL
                        AND date_last_post > rp.date_read
                    )
                    OR (rp.date_read IS NULL AND rf.date_read IS NULL)
                    )";

            //if the user does not have the admin right
            if ( ! jAcl2::check('hfnu.admin.post') )
                // we do not display the "hidden" thread
                $sql .= " AND status <> 7 ";

            $count = $c->query($sql);
            $nbPosts = $count->fetch()->c;
            return $nbPosts;
        }
        else {
            return 0;
        }
    }
}
