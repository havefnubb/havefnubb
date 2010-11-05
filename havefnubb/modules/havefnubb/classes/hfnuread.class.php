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
* main UI to manage the statement of the forums of HaveFnuBB!
*/
class hfnuread {
    /**
     * the read posts
     * @var $postsRead array
     */
    public static $postsRead = array();
    /**
     * this function mark all forum as read
     */
    public static function markAllAsRead() {
        if (jAuth::isConnected()) {
            $id_user = jAuth::getUserSession ()->id;

            // delete all previous forum the current user has read
            $dao = jDao::get('havefnubb~read_forum');
            $dao->deleteByUserId($id_user);

            $dateReadForum = time();
            $forums = jDao::get('havefnubb~forum')->findAll();
            foreach ($forums as $forum) {
                $rec = jDao::createRecord('havefnubb~read_forum');
                $rec->id_forum = $forum->id_forum;
                $rec->date_read = $dateReadForum;
                $rec->id_user = $id_user;
                $dao->insert($rec);
            }
            // delete all previous posts the current user has read
            jDao::get('havefnubb~read_posts')->deleteByUserId($id_user);
        }
    }
    /**
     * this function says which forum has been marked as read by which user
     * @param integer $id the forum id
     */
    public static function markForumAsRead($id) {
        if ( jAuth::isConnected() ) {
            $dao = jDao::get('havefnubb~read_forum');
            $id_user = jAuth::getUserSession ()->id;

            $exist = $dao->get($id_user,$id);
            if ($exist === false) {
                $rec = jDao::createRecord('havefnubb~read_forum');
                $rec->id_forum = $id;
                $rec->date_read = time();
                $rec->id_user = $id_user;
                $dao->insert($rec);
            }
            else {
                $exist->date_read = time();
                $dao->update($exist);
            }
            //delete all the read posts
            $daoReadPost = jDao::get('havefnubb~read_posts');
            $daoReadPost->deleteByUserIdAndIdForum($id_user,$id);
        }
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param record $post record of the current read post
     */
    public static function insertReadPost($post,$datePost) {
        if ($post->id_post > 0 and $post->id_forum > 0 and jAuth::isConnected()) {
            $dao = jDao::get('havefnubb~read_posts');
            $id_user = jAuth::getUserSession ()->id;
            $exist = $dao->get($id_user ,$post->id_forum,$post->id_post);
            if ($exist === false) {
                $rec = jDao::createRecord('havefnubb~read_posts');
                $rec->id_forum = $post->id_forum;
                $rec->id_post = $post->id_post;
                $rec->parent_id = $post->parent_id;
                $rec->id_user = $id_user;
                $rec->date_post_read = $datePost;
                $dao->insert($rec);
            }
            else {
                $exist->date_post_read = $datePost;
                $dao->update($exist);
            }
        }
    }
    /**
     * this function get the messages that the current user does not read yet between his last connection and last 15min
     * then return the given record
     * @return array : the limited records + total of records
     */
    public static function findUnreadThread($page=0,$nbPostPerPage=25) {
        if ( !jAuth::isConnected() )
            return array('posts'=>0,'nbPosts'=>0);
        // let's find the threads we have not read yet
        $c = jDb::getConnection();
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
                    forum.parent_id as forum_parent_id,
                    forum.forum_name,
                    rp.date_read as date_read_post,
                    rf.date_read as date_read_forum
             FROM ".$c->prefixTable('hfnu_threads')." AS threads
                LEFT JOIN ".$c->prefixTable('community_users')." AS usr ON ( threads.id_user =usr.id)
                LEFT JOIN ".$c->prefixTable('hfnu_forum')." AS forum ON ( threads.id_forum=forum.id_forum)
                LEFT JOIN ".$c->prefixTable('hfnu_read_posts')." as rp ON ( threads.id_forum=rp.id_forum AND
                                                                threads.id_last_msg=rp.id_post AND
                                                                rp.id_user = '".jAuth::getUserSession ()->id."')
                LEFT JOIN ".$c->prefixTable('hfnu_read_forum')." as rf ON ( threads.id_forum=rf.id_forum AND
                                                                rf.id_user = '".jAuth::getUserSession ()->id."')
            , ".$c->prefixTable('hfnu_posts')." AS posts
            WHERE
                threads.id_thread = posts.parent_id ";

        if ( ! jAcl2::check('hfnu.admin.post') )
            $sql .= " AND posts.status <> 7 ";

        $sql .= " GROUP BY posts.parent_id ORDER BY threads.date_last_post desc ";

        $posts = $c->limitQuery($sql, $page,$nbPostPerPage);
        if ($posts->rowCount() == 0) {
            $posts = $c->limitQuery($sql, 0,$nbPostPerPage);
            $page = 0;
        }
        $nbPosts = 0;
        foreach ($posts as $post) {
            if  (! ($post->p_date_created < $post->date_read_forum ||
                    $post->p_date_created <= $post->date_read_post) )
            $nbPosts++;
        }
        return array('posts'=>$posts,'nbPosts'=>$nbPosts);
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param integer $id_forum the current id forum
     * @return boolean
     */
    public static function findUnreadThreadbyForumId($id_forum) {
        if ( jAuth::isConnected() and $id_forum > 0) {
            // let's find the threads we have not read yet
            $c = jDb::getConnection();
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
                        forum.parent_id as forum_parent_id,
                        forum.forum_name,
                        rp.date_read as date_read_post,
                        rf.date_read as date_read_forum
                 FROM ".$c->prefixTable('hfnu_threads')." AS threads
                    LEFT JOIN ".$c->prefixTable('community_users')." AS usr ON ( threads.id_user =usr.id)
                    LEFT JOIN ".$c->prefixTable('hfnu_forum')." AS forum ON ( threads.id_forum=forum.id_forum)
                    LEFT JOIN ".$c->prefixTable('hfnu_read_posts')." as rp ON ( threads.id_forum=rp.id_forum AND
                                                                    threads.id_last_msg=rp.id_post AND
                                                                    rp.id_user = '".jAuth::getUserSession ()->id."')
                    LEFT JOIN ".$c->prefixTable('hfnu_read_forum')." as rf ON ( threads.id_forum=rf.id_forum AND
                                                                    rf.id_user = '".jAuth::getUserSession ()->id."')
                , ".$c->prefixTable('hfnu_posts')." AS posts
                WHERE
                    threads.id_thread = posts.parent_id AND forum.id_forum = '".$id_forum."' ";

            if ( ! jAcl2::check('hfnu.admin.post') )
                $sql .= " AND posts.status <> 7 ";

            $sql .= " GROUP BY posts.parent_id ORDER BY threads.date_last_post desc ";

            $posts = $c->Query($sql);

            $nbPosts = 0;
            foreach ($posts as $post) {
                if  (! ($post->p_date_created < $post->date_read_forum ||
                        $post->p_date_created <= $post->date_read_post) )
                $nbPosts++;
            }
            return ($nbPosts > 0) ? false : true;
        }
        else {
            return true;
        }
    }
}
