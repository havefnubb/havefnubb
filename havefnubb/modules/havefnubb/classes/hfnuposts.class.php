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
	private static $statusAvailable = array('opened','closed','pined','pinedclosed','censored','uncensored','hidden');
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

		return self::$posts[$id];
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
	 * @return boolean of the success or not
	 */
	public static function delete($id_post) {
		if ($id_post == 0 ) return false;
		$post = self::getPost($id_post);
		self::deletePost($id_post);
		$dao = jDao::get('havefnubb~posts');
		//thread post ?
		if ($post->id_post == $post->parent_id)
			//remove the "sons"
			$dao->deleteSonsPost($post->parent_id);
		//remove the "father"
		$dao->delete($id_post);
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
		$daoPost = jDao::get('havefnubb~posts');
		// total number of posts
		$nbPosts = $daoPost->countPostsByForumId($id_forum);
		// get the posts of the current forum, limited by point 1 and 2
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            $posts = $daoPost->findByIdForumVisible($id_forum,$page,$nbPostPerPage);
            if ($posts->rowCount() == 0) {
                    $posts = $daoPost->findByIdForumVisible($id_forum,0,$nbPostPerPage);
                    $page = 0;
            }
        }
        else {
            $posts = $daoPost->findByIdForum($id_forum,$page,$nbPostPerPage);
            // check if we have found record ;
            if ($posts->rowCount() == 0) {
                    $posts = $daoPost->findByIdForum($id_forum,0,$nbPostPerPage);
                    $page = 0;
            }
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
			$dao = jDao::get('havefnubb~posts');
			// the number of post between the current post_id and the parent_id
			$child = (int) $dao->countReplies($post->id_post,$post->parent_id);

			$nbRepliesPerPage = (int) $gJConfig->havefnubb['replies_per_page'];
			// calculate the offset of this id_post
			$goto = (ceil ($child/$nbRepliesPerPage) * $nbRepliesPerPage) - $nbRepliesPerPage;
			if ($goto < 0 ) $goto = 0;
			// replacing the id_post by parent_id for the posts_replies.zones.php
			$id_post = $parent_id;
		}

		// let's update the viewed counter
		self::updateViewing($id_post);
		// let's update the 'read by mod'
		self::readByMod($parent_id);
		// let's add the user to the post_read table
		jClasses::getService('havefnubb~hfnuread')->insertReadPost($post);

		return array($id_post,$post,$goto);
	}
	/**
	 * updateViewing : update the counter of the views of a given post
	 * @param integer $id_post post id of the current post
	 */
	public static function updateViewing($id_post) {
		if ($id_post == 0 ) return;
		$dao = jDao::get('havefnubb~posts');
		$post = $dao->get($id_post);
		$post->viewed = $post->viewed +1;
		$dao->update($post);
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
			$record->subject	    = $subject;
			$record->message	    = $message;
			$record->id_post  	    = $id_post;
			$record->id_user 	    = jAuth::getUserSession ()->id;
			$record->id_forum 	    = $id_forum;
			$record->parent_id      = 0;
			$record->status	        = 'opened';
			$record->date_created   = time();
			$record->date_modified  = time();
			$record->viewed         = 0;
			$record->poster_ip 	    = $_SERVER['REMOTE_ADDR'];
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

			// now let's get the inserted id to put this one in parent_id column !
			$record->parent_id = $record->id_post;
			$id_post = $record->id_post;
			$parent_id = $record->parent_id;

			self::addPost($id_post,$record);

			jEvent::notify('HfnuPostAfterInsert',array('id'=>$id_post));

		}
		// edit a post
		else {
			jEvent::notify('HfnuPostBeforeUpdate',array('id'=>$id_post));

			//remove the id_post of the array
			self::deletePost($id_post);

			$record = $dao->get($id_post);
			$record->subject		= $subject;
			$record->message		= $message;
			$record->date_modified 	= time();
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

		jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'forumscope', $id_post);

		//subscription management
		if ($form->getData('subscribe') == 1) {
            jClasses::getService('havefnubb~hfnusub')->subscribe($parent_id);
		}
		else {
		    jClasses::getService('havefnubb~hfnusub')->unsubscribe($parent_id);
		}

		jForms::destroy('havefnubb~posts', $id_post);

		return $id_post;

	}

	/**
	 * save a reply to one post
	 * @param integer $parent_id parent id of the current post if editing of 0 if adding
	 * @return mixed boolean / DaoRecord $record of the reply
	 */
	public static function savereply($parent_id) {
		global $gJConfig;
		$form = jForms::fill('havefnubb~posts',$parent_id);

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

		$result = $form->prepareDaoFromControls('havefnubb~posts');
		$result['daorec']->date_created	= time();
		$result['daorec']->date_modified= time();
		$result['daorec']->status	    = 'opened';
		$result['daorec']->poster_ip	= $_SERVER['REMOTE_ADDR'];
		$result['daorec']->viewed 	    = 0;
		$result['daorec']->id_post  	= 0;
		$result['daorec']->id_user 	    = jAuth::getUserSession ()->id;
		$result['dao']->insert($result['daorec']);
		$id_post = $result['daorec']->getPk();

		self::addPost($id_post,$result['daorec']);

        jEvent::notify('HfnuPostAfterSaveReply',array('id_post'=>$id_post));

		if ( $form->getData('subscribe') == 1 ) {
    	    //subscribe to a post
    	    jClasses::getService('havefnubb~hfnusub')->subscribe($parent_id);
    	    //send message to anyone who subscribes to this thread
	        jClasses::getService('havefnubb~hfnusub')->sendMail( $parent_id );
		}

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

		//.. if the data are not ok, return to the form and display errors messages form
		if (!$form->check()) {
			return false;
		}

		jEvent::notify('HfnuPostBeforeSaveNotify',array('id'=>$id_post));
                $dao = jDao::get('havefnubb~notify')->getNotifByUserId($id_post,$form->getData('id_user'));
		if ($dao != null) {
			jMessage::add(jLocale::get('havefnubb~post.notification.already.done'),'error');
			return false;
		}

		$result = $form->prepareDaoFromControls('havefnubb~notify');
		$result['daorec']->parent_id	= $parent_id;
		$result['daorec']->subject		= '['.$form->getData('reason').'] ' . self::getPost($id_post)->subject;
		$result['daorec']->message		= $form->getData('message');
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
	 * @param string $censor_msg the censored message
	 * @return DaoRecord $record
	 */
	public static function switchStatus($parent_id,$id_post,$status,$censor_msg='') {
	if (! in_array($status,self::$statusAvailable)) {
		jMessage::add(jLocale::get('havefnubb~post.invalid.status'),'error');
			return false;
	}
		if ( $parent_id < 0 or $id_post < 1) return false;

		$dao = jDao::get('havefnubb~posts');

		if ($id_post == $parent_id ) {
		// we want to uncesored a post so let's open it
		if ($status == 'uncensored') $status = 'opened';

		if ( $dao->updateStatusByIdParent($parent_id,$status,$censor_msg) )
			jEvent::notify('HfnuPostAfterStatusChanged',array('id'=>$parent_id,'status'=>$status));
		} else {
			// $status can be 'uncensored' , 'censored'
			self::$status($parent_id,$id_post,$censor_msg);
		}
		//delete the post from the array $posts
		self::deletePost($id_post);
		// get the updated record
		$record = $dao->get($id_post);
		//add the post to the array $posts
		self::addPost($id_post,$record);

		return $record;
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
	public static function censored($parent_id,$id_post,$censor_msg) {
		if ( $parent_id < 0 or $id_post < 1) return false;

		$dao = jDao::get('havefnubb~posts');
		if ( $dao->censorIt($id_post,$censor_msg) ) {
			jEvent::notify('HfnuPostAfterStatusChanged',
						   array('id'=>$id_post,
								 'status'=>'censored')
						   );
		}
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
	public static function uncensored($parent_id,$id_post,$censor_msg='') {
		if ( $parent_id < 0 or $id_post < 1) return false;

	$dao = jDao::get('havefnubb~posts');
	$status = ( $id_post == $parent_id ) ? 'opened' : self::getPost($parent_id)->status ;
		if ( $dao->updateStatusById($id_post,$status) ) {
			jEvent::notify('HfnuPostAfterStatusChanged',
						   array('id'=>$id_post,
								 'status'=>self::getPost($parent_id)->status)
						   );
		}
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
	public static function moveToForum($id_post,$id_forum) {
		if ($id_post == 0 or $id_forum == 0) return false;
		$dao = jDao::get('havefnubb~posts');
		$dao->moveToForum($id_post,$id_forum);
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

		foreach($datas as $data) {

			$record = jDao::createRecord('havefnubb~posts');
			$record = $data;
			$record->id_post = null;//to create a new record !
			$record->id_forum = $id_forum; // the id forum where we want to move this post
			// we only set parent_id to id_post for the first post which becomes the parent !
			if ($i > 0 ) {
					$record->parent_id = $id_post_new;
			}
			$dao->insert($record); // create the new record
			if ($i == 0 ) {
					$record->parent_id 	= $record->id_post;
					$id_post_new 		= $record->id_post;
					$dao->update($record);
			}
			$i++;
		}

		$dao->deleteAllFromCurrentIdPostWithParentId($parent_id,$id_post); // delete the old records

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

		foreach($datas as $data) {

			$record = jDao::createRecord('havefnubb~posts');
			$record = $data;
			$record->id_post = null;//to create a new record !
			$record->id_forum = $data->id_forum; // the id forum of the same forum
			$record->parent_id = $new_parent_id; // the id of the parent id on which we link the thread

			$result = $dao->insert($record);
		}

		$dao->deleteAllFromCurrentIdPostWithParentId($parent_id,$id_post); // delete the old records

		return true;
	}


	/******************************************************************
	 * This part handles others behaviors of post/information of post *
	 ******************************************************************/

	/**
	 * get specific info to be display in the breadcrumb and title of each page
	 * @param integer $id_forum the current forum
	 * @return array $info array composed by the forum datas of the current forum and the category datas of the current forum
	 */
	public static function getCrumbs($id_forum) {
		if ($id_forum == 0) return array();
		$forum 		= jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);
		$category 	= jClasses::getService('havefnubb~hfnucat')->getCat($forum->id_cat);
		$info = array($forum,$category);
		return $info;
	}

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
		return jDao::get('havefnubb~posts')->findUnreadThreadByMod();
	}
}
