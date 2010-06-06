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
            $cnx = jDb::getConnection();

            $forums = $cnx->query("SELECT id_forum FROM ".$cnx->prefixTable('forum'));

            $dao = jDao::create('havefnubb~read_forum');
            // delete all previous forum the current user has read
            $dao->deleteByUser($id_user);

            foreach ($forums as $forum) {
                $rec = jDao::createRecord('havefnubb~read_forum');
                $rec->id_forum = $forum->id_forum;
                $rec->date_read = time();
                $rec->id_user = $id_user;
                $dao->insert($rec);
            }
            // delete all previous posts the current user has read
            $cnx->exec("DELETE FROM " .$cnx->prefixTable('read_posts') .
                        " WHERE id_user = '".$id_user ."'");
        }
    }
    /**
     * this function says which forum has been marked as read by which user
     * @param integer $id the forum id
     */
    public static function markForumAsRead($id) {
        if ($id < 1 ) return;
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
        }
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param record $post record of the current read post
     */
    public static function insertReadPost($post) {

        if ($post->id_post > 0 and $post->id_forum > 0 and jAuth::isConnected()) {
            $dao = jDao::get('havefnubb~read_posts');
            $id_user = jAuth::getUserSession ()->id;
            //add all the post of the thread as READ !
            $posts = jDao::get('havefnubb~posts')->findAllPostByIdParent($post->parent_id);
            foreach ($posts as $p) {
                $exist = $dao->get($id_user ,$p->id_forum,$p->id_post);
                if ($exist === false) {
                    $rec = jDao::createRecord('havefnubb~read_posts');
                    $rec->id_forum = $p->id_forum;
                    $rec->id_post = $p->id_post;
                    $rec->id_user = $id_user;
                    $dao->insert($rec);
                }
            }
        }
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param  integer $id_post the current id post
     * @param integer $id_forum the current id forum
     * @return boolean
     */
    public static function getReadForum($id_forum) {
        if ($id_forum < 1 ) return true;
        if (!jAuth::isConnected() ) return true;
        //FIXME : when i mark all forum as read ; i always return true...
/*        if (jDao::get('havefnubb~read_forum')->get(jAuth::getUserSession()->id,$id_forum) !== false)
            return true;
        else {
            $nbReadPosts = jDao::get('havefnubb~read_posts')->countReadPosts(jAuth::getUserSession()->id,$id_forum) ;
            $daoPosts = jDao::get('havefnubb~posts');

            // i dont read that forum so display a "new" indicator
            if ( jAcl2::check('hfnu.admin.post') ) {
                $nbPosts = $daoPosts->findAllByIdForum($id_forum);
                // do we have read all the posts ?
                if ($nbReadPosts == $nbPosts) return true;
                $forumRead = $daoPosts->getUserLastCommentOnForums($id_forum);
            }
            else {
                $nbPosts = $daoPosts->findAllByIdForumVisible($id_forum);
                // do we have read all the posts ?
                if ($nbReadPosts == $nbPosts) return true;
                $forumRead = $daoPosts->getUserLastVisibleCommentOnForums($id_forum);
            }

            if ($forumRead === false) return true;
        }
        return false;*/
        if ( jAcl2::check('hfnu.admin.post') ) {
            $date_last_msg = jDao::get('havefnubb~threads_alone')->getLastThreadByIdForum($id_forum)->date_last_post;
        }
        else {
            $date_last_msg = jDao::get('havefnubb~threads_alone')->getLastVisibleThreadByIdForum($id_forum)->date_last_post;
        }

        $date_last_connect = jDao::get('havefnubb~connected')->get(jAuth::getUserSession()->id)->connected;
        return ($date_last_msg > $date_last_connect )  ? false : true;
    }

    /**
     * this function says which message from which forum has been read by which user
     * @param  integer $id_post the current id post
     * @param integer $id_forum the current id forum
     * @return boolean
     */
    public static function getReadPost($id_post,$parent_id,$id_forum) {
        if ($id_post < 1 ) return true;
        if ($parent_id < 1 ) return true;
        if ($id_forum < 1 ) return true;
        if ( !jAuth::isConnected() ) return true;
        $id_user = jAuth::getUserSession()->id;
        /*
        $alreadyRead = jDao::get('havefnubb~read_posts')->get($id_user,$id_forum,$id_post);
        // no record found in the read_post table, that means i marked all the forum as read
        // let's check the last forum between now and 3min
        if ($alreadyRead !== false)
            return true;
        $readForum = jDao::get('havefnubb~read_forum')->get($id_user,$id_forum);
        if ( $readForum === false)
            return false;

        if (  jAcl2::check('hfnu.admin.post') )
            $postRead = jDao::get('havefnubb~posts')->getUserLastCommentOnPosts($id_post);
        else
            $postRead = jDao::get('havefnubb~posts')->getUserLastVisibleCommentOnPosts($id_post);

        if ($postRead == false) return false;

        $dateReadForum = $readForum->date_read;
        return ($postRead->date_modified > $dateReadForum) ? false : true;*/

        if (  jAcl2::check('hfnu.admin.post') )
            $postRead = jDao::get('havefnubb~threads')->getUserLastCommentOnPosts($parent_id);
        else
            $postRead = jDao::get('havefnubb~threads')->getUserLastVisibleCommentOnPosts($parent_id);

        if ($postRead === false) return false; else return true;


    }
    /**
     * this function get the messages that the current user does not read yet between his last connection and last 15min
     * then return the given record
     * @return array : the limited records + total of records
     */
    public static function findUnreadThread($page=0,$nbPostPerPage=25) {
        if ( !jAuth::isConnected() )
            return array('posts'=>0,'nbPosts'=>0);
/*        $start = jAuth::getUserSession()->member_last_connect - 900;
        $end = time();
        $nbPosts = jDao::get('havefnubb~threads_alone')->countUnreadThread($start,$end);
        $posts = jDao::get('havefnubb~posts')->findUnreadThread($start,$end,$page,$nbPostPerPage);
        return array('posts'=>$posts,'nbPosts'=>$nbPosts);*/
        $id_user = jAuth::getUserSession()->id;
        $date_last_connect = jDao::get('havefnubb~connected')->get($id_user)->connected;

        $nbPosts = jDao::get('havefnubb~threads_alone')->countUnreadThread($date_last_connect);
        if (  jAcl2::check('hfnu.admin.post') ) {
                $posts = jDao::get('havefnubb~threads')
                        ->findUnreadThread(
                            $date_last_connect,
                            $page,
                            $nbPostPerPage);
        }
        else {
                $posts = jDao::get('havefnubb~threads')
                        ->findUnreadThreadVisible(
                            $date_last_connect,
                            $page,
                            $nbPostPerPage);
        }
        return array('posts'=>$posts,'nbPosts'=>$nbPosts);
    }
}
