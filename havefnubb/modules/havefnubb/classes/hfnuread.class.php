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
			$exist = $dao->get($id_user ,$post->id_forum,$post->id_post);
			if ($exist === false) {
				$rec = jDao::createRecord('havefnubb~read_posts');
				$rec->id_forum = $post->id_forum;
				$rec->id_post = $post->id_post;
				$rec->id_user = $id_user;
				$dao->insert($rec);
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

		if (jDao::get('havefnubb~read_forum')->get(jAuth::getUserSession()->id,$id_forum) !== false)
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
        return false;
	}

	/**
	 * this function says which message from which forum has been read by which user
	 * @param  integer $id_post the current id post
	 * @param integer $id_forum the current id forum
	 * @return boolean
	 */
	public static function getReadPost($id_post,$id_forum) {
		if ( !jAuth::isConnected() ) return true;
        $id_user = jAuth::getUserSession()->id;
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
        return ($postRead->date_modified > $dateReadForum) ? false : true;
	}
	/**
	 * this function get the read messages of the current user,
	 * and get the messages he does not made himself
	 * then return the given record
	 * @return $data record
	 */
	public static function findUnreadThread() {
		// limit before considering the are new posts
		$limit = time() - 900;
		$posts = jDao::get('havefnubb~posts')->findUnreadThread($limit);
		return $posts;
	}

}
