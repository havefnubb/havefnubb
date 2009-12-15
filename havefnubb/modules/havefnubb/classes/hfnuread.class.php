<?php
/**
* main UI to manage the statement of the forums of HaveFnuBB!
* 
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class hfnuread {
    /**
     * the read posts
     * var $postsRead array
     */    
    public static $postsRead = array();
    /**
     * this function mark all forum as read
     */    
    public static function markAllAsRead() {
        if (jAuth::getUserSession ()->id > 0) {

            $cnx = jDb::getConnection();
            // delete all previous forum the current user has read
            $cnx->exec("DELETE FROM " .$cnx->prefixTable('read_forum') .
                       " WHERE id_user = '".jAuth::getUserSession ()->id ."'");
            
            $forums = $cnx->query("SELECT id_forum FROM ".$cnx->prefixTable('forum'));
            
            $dao = jDao::create('havefnubb~read_forum');
            foreach ($forums as $forum) {
                $rec = jDao::createRecord('havefnubb~read_forum');
                $rec->id_forum = $forum->id_forum;
                $rec->date_read = time();
                $rec->id_user = jAuth::getUserSession ()->id;
                $dao->insert($rec);                
            }
            // delete all previous posts the current user has read
            $cnx->exec("DELETE FROM " .$cnx->prefixTable('read_posts') .
                       " WHERE id_user = '".jAuth::getUserSession ()->id ."'");
        }
    }
    /**
     * this function says which forum has been marked as read by which user
     * @param $id the forum id
     */    
    public static function markForumAsRead($id) {
        if (jAuth::getUserSession ()->id > 0) {            
            $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($id);
            $dao = jDao::get('havefnubb~read_forum');
            $exist = $dao->get(jAuth::getUserSession ()->id,$forum->id_forum);
            if ($exist === false) {            
                $rec = jDao::createRecord('havefnubb~read_forum');
                $rec->id_forum = $forum->id_forum;
                $rec->date_read = time();
                $rec->id_user = jAuth::getUserSession ()->id;
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
     * @param $post record of the current read post
     */
    public static function insertReadPost($post) {
        if ($post->id_post > 0 and $post->id_forum > 0 and jAuth::getUserSession ()->id > 0) {			
            $dao = jDao::get('havefnubb~read_posts');
            $exist = $dao->get(jAuth::getUserSession ()->id ,$post->id_forum,$post->id_post);
            if ($exist === false) {
                $rec = jDao::createRecord('havefnubb~read_posts');
                $rec->id_forum = $post->id_forum;
                $rec->id_post = $post->id_post;
                $rec->id_user = jAuth::getUserSession ()->id;
                $dao->insert($rec);
            }
        }
    }
    /**
     * this function says which message from which forum has been read by which user
     * @param $id_post the current id post
     * @param $id_forum the current id forum
     * @return boolean
     */
    public static function getReadPost($id_post,$id_forum) {
        $alreadyRead = jDao::get('havefnubb~read_posts')->get(jAuth::getUserSession()->id,$id_forum,$id_post); 
        // no record found in the read_post table, that means i marked all the forum as read
        // let's check the last forum between now and 3min
        if ($alreadyRead === false) 
            return true ?  time() < jDao::get('havefnubb~read_forum')->get(jAuth::getUserSession()->id,$id_forum)->date_read + 180 : false;
    }
    /**
     * this function get the read messages of the current user,
     * and get the messages he does not made himself
     * then return the given record
     * @return $data record
     */
    public static function getUnreadThread() {
        $cnx = jDb::getConnection();
        $posts = $cnx->query("SELECT distinct parent_id, " .
                              "rp.id_post,".
                              "rp.id_forum,".
                              "p.subject,".
                              "p.message,".
                              "p.status,".
                              "p.date_created,".
                              "p.date_modified,".
                              "p.viewed,".
                              "p.poster_ip,".
                              "p.censored_msg ".
                              "  FROM ".
                              $cnx->prefixTable('read_posts') . " as rp, " .
                              $cnx->prefixTable('posts') . " as  p " .
                              " WHERE " .
                              " rp.id_user = '".jAuth::getUserSession()->id."' " .
                              " AND ".
                              " p.id_user <> '".jAuth::getUserSession()->id."' group by parent_id" 
                              );
        return $posts;        
    }
    
}