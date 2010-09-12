<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008 FoxMaSk
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
    public static $posts = array();
    /**
     * status of the newest post
     * @var array $postStatus
     */
    public static $postStatus = array();
    /**
     * the authorized status of the post
     * @var array $statusAvailable
     */
    //private static $statusAvailable = array('pined','pinedclosed','opened','closed','censored','uncensored','hidden');
    private static $statusAvailable = array(1,2,3,4,5,6,7);
    /**
     * @var integer $hfAdmin the ID that defines the Admin
     */
    private static $hfAdmin = 1;
    /**
     * @var integer $hfAdmin the ID that defines the Moderator
     */
    private static $hfModerator = 3;

    /*********************************************************
     * This part handles the "add/delete/get" data of  posts *
     *********************************************************/


    /**
     * add the current post
     * @param  integer $id of the current post
     * @param  recordset $record of the current post to add
     */
    public static function addPost($id,$record) {
        if (!isset(self::$posts[$id]) and $id > 0)
            self::$posts[$id] = $record;
    }
    /**
     * get info of the current post
     * @param  integer $id of the current post
     * @return array composed by the post datas of the current post
     */
    public static function getPost($id) {
        if (!isset(self::$posts[$id]) and $id > 0)
            self::$posts[$id] = jDao::get('havefnubb~posts')->get($id);

        if ($id > 0)
            return self::$posts[$id];
        else {
            self::$posts[0] = array('id'=>0,'subject'=>'n/a');
        }
    }
    /**
     * get info of the current post that is not "hidden"
     * @param  integer $id of the current post
     * @return array composed by the post datas of the current post
     */
    public static function getPostVisible($id) {
        if (!isset(self::$posts[$id]) and $id > 0)
            self::$posts[$id] = jDao::get('havefnubb~posts')->getPostVisible($id);

        return self::$posts[$id];
    }

    /**
     * remove one post or complet thread from the database
     * @param integer $id_post  id post to remove
     * @param integer $parent_id parent id to remove
     * @return boolean of the success or not
     */
    public static function delete($id_post) {
        if ($id_post == 0 ) return false;

        self::deletePost($id_post);

        $dao = jDao::get('havefnubb~posts');
        $post = $dao->get($id_post);

        if ($post !== false) {
            //search if it's first post of the thread
            $daoThreads = jDao::get('havefnubb~threads_alone');
            $daoThreadsRec = $daoThreads->getFirstIdPost($post->id_post);

            if ($daoThreadsRec !== false) {
                $dao->deleteSonsPost($post->parent_id);
                $daoThreads->delete($post->parent_id);
            } else {
                jDao::get('havefnubb~posts')->delete($id_post);
                //remove one post + get the last post id
                $threadRec = jDao::get('havefnubb~threads_alone')->get($post->parent_id);
                $threadRec->nb_replies=$threadRec->nb_replies - 1;
                $threadRec->id_last_msg = $dao->getLastCreatedPostByThreadId($post->parent_id)->id_post;
                $daoThreads->update($threadRec);

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
    public static function deletePost($id) {
        if (isset(self::$posts[$id]) and $id > 0) {
            self::$posts = array_pop(self::$posts);
        }
    }
    /**
     * get the posts of the given forum
     * @param integer $id_forum the current forum
     * @param integer $page the current page
     * @param integer $nbPostPerPage the number of posts per page
     * @return array $page,$nbPosts,$posts if no record have been found, return page = 0 otherwise return the posts
     */
    public static function getPostsByIdForum($id_forum,$page,$nbPostPerPage) {
        $daoThreads = jDao::get('havefnubb~threads_alone');

        $nbPosts = $daoThreads->countPostsByForumId($id_forum);
        // get the posts of the current forum
        $c = jDb::getConnection();
        if ( ! jAuth::isConnected())
            $sql = "SELECT threads.id_thread,
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
                    posts.parent_id,
                    posts.status,
                    posts.ispined,
                    posts.iscensored,
                    posts.subject,
                    posts.message,
                    posts.date_created as p_date_created,
                    posts.date_modified, posts.viewed,
                    posts.poster_ip,
                    posts.censored_msg,
                    posts.read_by_mod,
                    usr.id,
                    usr.email,
                    usr.login,
                    usr.nickname,
                    usr.comment as member_comment,
                    usr.town as member_town,
                    usr.avatar as member_avatar,
                    usr.website as member_website,
                    usr.nb_msg,
                    usr.last_post as member_last_post,
                    usr.last_connect as member_last_connect,
                    forum.parent_id as forum_parent_id,
                    forum.forum_name
                FROM ".$c->prefixTable('threads')." AS threads
                    LEFT JOIN ".$c->prefixTable('community_users')." AS usr ON ( threads.id_user=usr.id)
                    LEFT JOIN ".$c->prefixTable('forum')." AS forum ON ( threads.id_forum=forum.id_forum)
                , ".$c->prefixTable('posts')." AS posts
                WHERE
                    threads.id_thread=posts.parent_id AND
                    posts.id_forum = '".$id_forum."'";
        else
            $sql = "SELECT threads.id_thread,
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
                        posts.parent_id,
                        posts.status,
                        posts.ispined,
                        posts.iscensored,
                        posts.subject,
                        posts.message,
                        posts.date_created as p_date_created,
                        posts.date_modified, posts.viewed,
                        posts.poster_ip,
                        posts.censored_msg,
                        posts.read_by_mod,
                        usr.id,
                        usr.email,
                        usr.login,
                        usr.nickname,
                        usr.comment as member_comment,
                        usr.town as member_town,
                        usr.avatar as member_avatar,
                        usr.website as member_website,
                        usr.nb_msg,
                        usr.last_post as member_last_post,
                        usr.last_connect as member_last_connect,
                        forum.parent_id as forum_parent_id,
                        forum.forum_name,
                        rp.date_read as date_read_post
                FROM ".$c->prefixTable('threads')." AS threads
                    LEFT JOIN ".$c->prefixTable('community_users')." AS usr ON ( threads.id_user=usr.id)
                    LEFT JOIN ".$c->prefixTable('forum')." AS forum ON ( threads.id_forum=forum.id_forum)
                    LEFT JOIN ".$c->prefixTable('read_posts')." as rp ON ( threads.id_forum=rp.id_forum AND
                                                                    threads.id_last_msg=rp.id_post AND
                                                                    rp.id_user = '".jAuth::getUserSession ()->id."')
                ,".$c->prefixTable('posts')." AS posts
                WHERE
                    threads.id_thread=posts.parent_id AND
                    posts.id_forum = '".$id_forum."'";

        // if the user is not an admin then we hide the "hidden" posts
        if ( ! jAcl2::check('hfnu.admin.post') )
            $sql .= "AND posts.status <> 7 ";

        $sql .= "GROUP BY posts.parent_id ORDER BY threads.ispined desc, threads.date_last_post desc ";

        $posts = $c->limitQuery($sql, $page,$nbPostPerPage);
        if ($posts->rowCount() == 0) {
            $posts = $c->limitQuery($sql, 0,$nbPostPerPage);
            $page = 0;
        }

        return array($page,$nbPosts,$posts);
    }


    /****************************************************
     * This part handles the "view" statement of a post *
     ****************************************************/


    /**
     * view a given thread !
     * business check :
     * 1) do those id exist ?
     * 2) permission ok ?
     * 3) if parent_id > 0 then calculate the page + assign id_post with parent_id
     * business update :
     * 1) update the count of view of this thread
     * @param integer $id_post id of the current post
     * @param integer $parent_id parent if of the current post
     * @return array of id_post, DaoRecord of Post, Paginator
     */
    public static function view($id_post,$parent_id) {
        global $gJConfig;
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $post = self::getPostVisible($id_post);
        }
        else
            $post = self::getPost($id_post);

        if ($id_post == 0 or $post === false) {
            return array(null,null,null);
        }

        if ( ! self::checkPerm('hfnu.posts.view','forum'.$post->id_forum) ) {
            return array(null,null,null);
        }

        $goto = 0;
        if ($parent_id > 0) {
            // the number of post between the current post_id and the parent_id
            $nbReplies = (int) jDao::get('havefnubb~threads_alone')->get($parent_id)->nb_replies + 1; // add 1 because nb_replies does not count the "parent" post

            $nbRepliesPerPage = (int) $gJConfig->havefnubb['replies_per_page'];
            // calculate the offset of this id_post
            $goto = (ceil ($nbReplies/$nbRepliesPerPage) * $nbRepliesPerPage) - $nbRepliesPerPage;

            if ($goto < 0 ) $goto = 0;
        }

        // let's update the viewed counter
        self::updateViewing($id_post,$parent_id);
        // let's update the 'read by mod'
        self::readByMod($parent_id);
        // let's add the user to the post_read table
        jClasses::getService('havefnubb~hfnuread')->insertReadPost($post);

        return array($id_post,$post,$goto,$nbReplies);
    }
    /**
     * updateViewing : update the counter of the views of a given post
     * @param integer $id_post post id of the current post
     */
    public static function updateViewing($id_post,$parent_id) {
        if ($id_post == 0 ) return;
        $dao = jDao::get('havefnubb~posts');
        $post = $dao->get($id_post);
        if ($post !== false)  {
            $post->viewed = $post->viewed +1;
            $dao->update($post);
        }
        $dao = jDao::get('havefnubb~threads');
        $thread = $dao->get($parent_id);
        if ($thread !== false)  {
            $thread->nb_viewed = $thread->nb_viewed +1;
            $dao->update($thread);
        }

    }


    /**
     * readByMod : update the 'read by mod' flag
     * @param integer $parent_id parent id of the thread that will by marked as read by a moderator
     */
    public static function readByMod($parent_id) {
        if ($parent_id == 0 ) return;
        if (jAcl2DbUserGroup::isMemberOfGroup(self::$hfModerator) or
            jAcl2DbUserGroup::isMemberOfGroup(self::$hfAdmin) ) {
            jDao::get('havefnubb~posts')->updateReadByMod($parent_id);
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
    public static function save($id_forum,$id_post=0) {
        global $gJConfig;
        $form = jForms::fill('havefnubb~posts',$id_post);

        if (!$form) {
            return false;
        }
        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }

        //.. if the data are ok ; we get them !
        $subject = $form->getData('subject');
        $message = $form->getData('message');

        if ( count($message) > $gJConfig->havefnubb['post_max_size'] and
                $gJConfig->havefnubb['post_max_size'] > 0) {
            jMessage::add(jLocale::get('havefnubb~main.message.exceed.maximum.size',
                        array($gJConfig->havefnubb['post_max_size'])),'error');
            return false;
        }

        //CreateRecord object
        $dao = jDao::get('havefnubb~posts');

        // create a post
        if ($id_post == 0) {
            jEvent::notify('HfnuPostBeforeSave',array('id'=>$id_post));
            $record = jDao::createRecord('havefnubb~posts');
            $datePost = time();
            $record->subject        = $subject;
            $record->message        = $message;
            $record->id_post        = $id_post;
            $record->id_user        = jAuth::getUserSession ()->id;
            $record->id_forum       = $id_forum;
            $record->parent_id      = 0;
            $record->status         = 3; //'opened'
            $record->date_created   = $datePost;
            $record->date_modified  = $datePost;
            $record->viewed         = 0;
            $record->ispined        = 0;
            $record->iscensored     = 0;
            $record->poster_ip      = $_SERVER['REMOTE_ADDR'];
            //if the current user is a member of a moderator group
            // we set this post as 'read by moderator'
            if (jAcl2DbUserGroup::isMemberOfGroup(self::$hfAdmin) or
                jAcl2DbUserGroup::isMemberOfGroup(self::$hfModerator) ) {
                $record->read_by_mod = 1;
            }
            else {
                $record->read_by_mod = 0;
            }

            $dao->insert($record);

            $threadDao = jDao::get('havefnubb~threads');
            $threadRec = jDao::createRecord('havefnubb~threads');
            $threadRec->id_user_thread  = jAuth::getUserSession ()->id;
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

            // now let's get the inserted id to put this one in parent_id column !
            $record->parent_id = $threadRec->id_thread;

            $id_post = $record->id_post;
            $parent_id = $record->parent_id;


            //update Forum record
            $forum = jDao::get('havefnubb~forum');
            $forumRec = $forum->get($id_forum);
            $forumRec->id_last_msg = $id_post;
            $forumRec->date_last_msg = $datePost;
            $forum->update($forumRec);

            self::addPost($id_post,$record);

            jEvent::notify('HfnuPostAfterInsert',array('id'=>$id_post));

        }
        // edit a post
        else {
            jEvent::notify('HfnuPostBeforeUpdate',array('id'=>$id_post));

            //remove the id_post of the array
            self::deletePost($id_post);

            $record = $dao->get($id_post);
            $record->subject        = $subject;
            $record->message        = $message;
            $record->date_modified  = time();
            $parent_id = $record->parent_id;
            jEvent::notify('HfnuPostAfterUpdate',array('id'=>$id_post));

            // add the new record to the array
             self::addPost($id_post,$record);
        }

        // in all cases (id_post = 0 or not )
        // we have to update as we store the last insert id in the parent_id column

        $dao->update($record);

        jEvent::notify('HfnuPostAfterSave',array('id'=>$id_post));

        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));

        $tags = explode(",", $form->getData("tags"));

        //add this post as already been read
        jClasses::getService('havefnubb~hfnuread')->insertReadPost($record);

        jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'forumscope', $id_post);

        //subscription management
        if ($form->getData('subscribe') == 1) {
            jClasses::getService('havefnubb~hfnusub')->subscribe($parent_id);
        }
        else {
            jClasses::getService('havefnubb~hfnusub')->unsubscribe($parent_id);
        }

        jForms::destroy('havefnubb~posts', $id_post);

        return $record;

    }

    /**
     * save a reply to one post
     * @param integer $parent_id parent id of the current post if editing of 0 if adding
     * @return mixed boolean / DaoRecord $record of the reply
     */
    public static function savereply($parent_id,$id_post) {
        global $gJConfig;
        $form = jForms::fill('havefnubb~posts',$parent_id);
        if (!$form) {
            return false;
        }

        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }

        $message = $form->getData('message');
        //is the size of the message limited ?
        if ( strlen($message) > $gJConfig->havefnubb['post_max_size']
                and  $gJConfig->havefnubb['post_max_size'] > 0)
            {
            jMessage::add(jLocale::get('havefnubb~main.message.exceed.maximum.size',
                                array($gJConfig->havefnubb['post_max_size'])),'error');
            return false;
        }

        jEvent::notify('HfnuPostBeforeSaveReply',array('id'=>$parent_id));

        //get the thread record to keep the status of the thread and apply it
        //to this new reply.
        $threadRec = jDao::get('havefnubb~threads')->get($parent_id);
        if ($threadRec === false) {
            return false;
        }

        $dateReply = time();
        $result = $form->prepareDaoFromControls('havefnubb~posts');
        $result['daorec']->parent_id    = $parent_id;
        $result['daorec']->date_created = $dateReply;
        $result['daorec']->date_modified= $dateReply;
        $result['daorec']->status       = $threadRec->status;//'opened'
        $result['daorec']->poster_ip    = $_SERVER['REMOTE_ADDR'];
        $result['daorec']->viewed       = 0;
        $result['daorec']->id_post      = 0;
        $result['daorec']->id_user      = jAuth::getUserSession ()->id;
        $result['daorec']->iscensored   = $threadRec->iscensored;
        $result['daorec']->ispined      = $threadRec->ispined;
        $result['dao']->insert($result['daorec']);
        $id_post = $result['daorec']->getPk();

        self::addPost($id_post,$result['daorec']);

        //update Threads record
        $threads = jDao::get('havefnubb~threads');
        $threadRec = $threads->get($parent_id);
        $threadRec->nb_replies      = $threadRec->nb_replies +1;
        $threadRec->nb_viewed       = $threadRec->nb_viewed +1;
        $threadRec->id_last_msg     = $id_post;
        $threadRec->date_last_post  = $dateReply;
        $threads->update($threadRec);

        //update Forum record
        $forum = jDao::get('havefnubb~forum');
        $forumRec = $forum->get($threadRec->id_forum);
        $forumRec->id_last_msg = $id_post;
        $forumRec->date_last_msg = $dateReply;
        $forum->update($forumRec);

        //add a "fake" column just to return it to the posts controller
        // and then being able to redirect to the correct page where this
        // post has been added
        $result['daorec']->id_first_msg = $threadRec->id_first_msg;

        jEvent::notify('HfnuPostAfterSaveReply',array('id_post'=>$id_post));

        if ( $form->getData('subscribe') == 1 ) {
            //subscribe to a post
            jClasses::getService('havefnubb~hfnusub')->subscribe($parent_id);
            //send message to anyone who subscribes to this thread
        }
        jClasses::getService('havefnubb~hfnusub')->sendMail( $parent_id );

        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));

        jForms::destroy('havefnubb~posts', $parent_id);

        return $result['daorec'];
    }

    /**
     * save a notification posted by a user
     * @param integer $id_post id post of the current post if editing of 0 if adding
     * @return boolean status of success of this submit
     */
    public static function savenotify($id_post,$parent_id) {

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
        $result['daorec']->parent_id    = $parent_id;
        $result['daorec']->subject      = self::getPost(jDao::get('havefnubb~threads_alone')->get($parent_id)->id_last_msg)->subject;
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
     * @param integer $parent_id parent id of the thread
     * @param string $status the status to switch to
     * @return DaoRecord $record
     */
    public static function switchStatus($parent_id,$id_post,$status) {

        if (! in_array($status,self::$statusAvailable)) {
            jMessage::add(jLocale::get('havefnubb~post.invalid.status'),'error');
            return false;
        }

        if ( $parent_id < 0 ) return false;

        if ( jDao::get('havefnubb~posts')->updateStatusByIdParent($parent_id,$status) )
            jEvent::notify('HfnuPostAfterStatusChanged',array('id'=>$parent_id,'status'=>$status));

        $daoThread = jDao::get('havefnubb~threads');
        $rec = $daoThread->get($parent_id);
        $rec->status_thread = $status;
        if ($status == 1 or $status == 2)
            $rec->ispined_thread = 1;
        else
            $rec->ispined_thread = 0;
        $daoThread->update($rec);

        return self::getPost($id_post);
    }
    /**
     * this function permits to get the status of the posts
     * @param integer $id_post id post
     * @return array $postStatus return the status of the given post
     */
    public static function getPostStatus($id_post) {
        if (!isset(self::$postStatus[$id_post]))
            self::$postStatus[$id_post] = jDao::get('havefnubb~newest_posts')->getPostStatus($id_post);
        return self::$postStatus[$id_post];
    }
    /**
     * censor the current POST
     * @param integer $parent_id parent id of the thread
     * @param integer $id_post post id of the thread
     * @param string $status the status to switch to
     * @param string $censor_msg the censored message
     */
    public static function censor($parent_id,$id_post,$censor_msg) {
        if ( $parent_id < 0 or $id_post < 1) return false;
        $return = false;
        $dao = jDao::get('havefnubb~posts');
        if ( $dao->censorIt($id_post,$censor_msg) ) {
            jEvent::notify('HfnuPostAfterStatusChanged',
                           array('id'=>$id_post,
                                 'status'=>5)
                           );
            $daoThread = jDao::get('havefnubb~threads');
            $rec = $daoThread->get($parent_id);
            $rec->iscensored = 1;
            $daoThread->update($rec);

            $return = self::getPost($id_post);
        }
        return $return;
    }
    /**
     * remove the censor of current POST
     * To uncensor :
     * 1) get the status of the Father Post
     * 2) apply the Father's status to the Son Post
     * @param integer $parent_id parent id of the thread
     * @param integer $id_post integer parent id of the thread
     * @param string $status string the status to switch to
     * @param string $censor_msg string of the censored message
     */
    public static function uncensor($parent_id,$id_post) {
        if ( $parent_id < 0 or $id_post < 1) return false;
        $return = false;
        $dao = jDao::get('havefnubb~posts');
        $status = ( $id_post == $parent_id ) ? 3 : 5 ;
        if ( $dao->unCensorIt($id_post) ) {
            jEvent::notify('HfnuPostAfterStatusChanged',
                           array('id'       =>$id_post,
                                 'status'   =>3)
                           );

            $daoThread = jDao::get('havefnubb~threads');
            $rec = $daoThread->get($parent_id);
            $rec->iscensored = 0;
            $daoThread->update($rec);

            $return = self::getPost($id_post);
        }
        return $return;
    }


    /*************************************************
     * This part Handle the "movement" a post can do *
     *  from forum to forum and thread to thread     *
     *************************************************/


    /**
     * this function permits to move a complet thread to another forum
     * @param integer $id_post id post to move
     * @param integer $id_forum id forum to move to
     * @return boolean
     */
    public static function moveToForum($parent_id,$id_forum) {
        if ($parent_id == 0 or $id_forum == 0) return false;

        jDao::get('havefnubb~posts')->moveToForum($parent_id,$id_forum);

        $daoThreads = jDao::get('havefnubb~threads_alone');
        $threadRec = $daoThreads->get($parent_id);
        $threadRec->id_forum_thread=$id_forum;
        $daoThreads->update($threadRec);

        return true;
    }

    /**
     * this function permits to split the thread to a forum
     * @param integer $parent_id parent_id
     * @param integer $id_post id post
     * @param integer $id_forum id forum
     * @return integer $id_post_new the new Id
     */
    public static function splitToForum($parent_id,$id_post,$id_forum) {
        if ($id_post == 0 or $id_forum == 0 or $parent_id == 0) return false;
        $dao = jDao::get('havefnubb~posts');

        $datas = $dao->findAllFromCurrentIdPostWithParentId($parent_id,$id_post);

        $i = 0;
        $id_post_new = 0;
        $id_thread = 0;

        foreach($datas as $data) {

            $record = jDao::createRecord('havefnubb~posts');
            $record = $data;
            $record->id_post = 0;//to create a new record !
            $record->id_forum = $id_forum; // the id forum where we want to move this post
            // we only set parent_id to id_post for the first post which becomes the parent !

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

                // now let's get the inserted id to put this one in parent_id column !

                $id_thread          = $threadRec->id_thread;
                $record->parent_id  = $id_thread;

                $dao->insert($record); // create the new record

                $id_post_new        = $record->id_post;
                $id_last_msg        = $record->id_post;
                $date_created       = $record->date_created;

            }
            elseif ($i > 0 ) {
                $record->parent_id = $id_post_new;
                $dao->insert($record); // create the new record
                $id_last_msg  = $record->id_post;
                $date_created = $record->date_created;
            }

            $i++;
        }

        // remove the number of replies from the original thread
        $threadDao = jDao::get('havefnubb~threads');
        $threadRec = $threadDao->get($parent_id);
        $threadRec->nb_replies -= $i;
        $threadDao->update($threadRec);

        //update id_first_msg & id_last_msg of the Thread
        $threadDao = jDao::get('havefnubb~threads');
        $threadRec = $threadDao->get($id_thread);
        $threadRec->id_first_msg = $id_post_new;
        $threadRec->id_last_msg  = $id_last_msg;
        $threadDao->update($threadRec);

        $dao->deleteAllFromCurrentIdPostWithParentId($parent_id,$id_post); // delete the old records

        //$record = $dao->get($id_post_new);

        //update Forum record
        $forum                      = jDao::get('havefnubb~forum');
        $forumRec                   = $forum->get($id_forum);
        $forumRec->id_last_msg      = $id_last_msg;
        $forumRec->date_last_msg    = $date_created;
        $forum->update($forumRec);
        // get the id_post of the previous post
        // then update the thread table with its info (last_msg id + date)
        return $id_post_new;
    }

    /**
     * this function permits to split the thread to another thread
     * @param integer $id_post id post to split
     * @param integer $parent_id parent_id  of the current id post
     * @param integer  $new_parent_id parent id to attach to
     * @return boolean
     */
    public static function splitToThread($id_post,$parent_id,$new_parent_id) {
        if ($id_post == 0 or $parent_id == 0 or $new_parent_id == 0) return false;

        $dao = jDao::get('havefnubb~posts');
        $datas = $dao->findAllFromCurrentIdPostWithParentId($parent_id,$id_post);
        $i = 0;
        foreach($datas as $data) {
            $i++;
            $record = jDao::createRecord('havefnubb~posts');
            $record = $data;
            $record->id_post = null;//to create a new record !
            $record->id_forum = $data->id_forum; // the id forum of the same forum
            $record->parent_id = $new_parent_id; // the id of the parent id on which we link the thread

            $result = $dao->insert($record);
        }

        $dao->deleteAllFromCurrentIdPostWithParentId($parent_id,$id_post); // delete the old records

        //Thread Update process :
        $daoThreads = jDao::get('havefnubb~threads_alone');

        // 1) add to the "target" Thread all the infos
        $threadRec = $daoThreads->get($new_parent_id);
        $threadRec->nb_replies += $i;
        $threadRec->id_last_msg = $dao->getLastCreatedPostByThreadId($new_parent_id)->id_post;
        $id_first_msg = $threadRec->id_first_msg;
        $daoThreads->update($threadRec);

        // 2) remove from the "source" Thread all the infos
        $threadRec = $daoThreads->get($parent_id);
        if ($threadRec->nb_replies > 0 )
            $threadRec->nb_replies -= $i;
        if ($dao->getLastCreatedPostByThreadId($parent_id) === false )
            $daoThreads->delete($parent_id);
        else {
            $threadRec->id_last_msg = $dao->getLastCreatedPostByThreadId($parent_id)->id_post;
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
    public static function checkPerm($rights,$ressources) {
        return jAcl2::check($rights,$ressources) ? true : false;
    }

    /**
     * get the list of the unread post by any moderator
     * @return data DaoData
     */
    public static function findUnreadThreadByMod() {
        return jDao::get('havefnubb~threads')->findUnreadThreadByMod();
    }
}
