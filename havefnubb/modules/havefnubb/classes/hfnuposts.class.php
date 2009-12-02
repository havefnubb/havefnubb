<?php
/**
* main UI to manage the statement of the posts  of the forum HaveFnuBB!
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/
class hfnuposts {
    /**
     * status of the newest post
     * var $postStatus array
     */
	public static $posts = array();
    public static $postStatus = array();
	private static $statusAvailable = array('opened','closed','pined','pinedclosed','censored','uncensored');

    /**
     * get info of the current post
     * @param  integer $id of the current post
     * @return array composed by the post datas of the current post
     */    		
    public function getPost($id) {
        if (!isset(self::$posts[$id]) and $id > 0) 
            self::$posts[$id] = jDao::get('havefnubb~posts')->get($id);
        return self::$posts[$id];
    }
    /**
     * delete a post
     * @param  integer $id of the current post
     * @return boolean 
     */    		
    public function deletePost($id) {
        if (isset(self::$posts[$id]) and $id > 0) {
			self::$posts = array_shift(self::$posts[$id]);
			$dao = jDao::get('havefnubb~posts');
	        $dao->delete($id);			
			return true;
		} else {
			return false;
		}
    }	
    /**
     * get specific info to be display in the breadcrumb and title of each page
     * @param  integer $id_forum  the current forum
     * @return  array composed by the forum datas of the current forum and the category datas of the current forum
     */
	public function getCrumbs($id_forum) {
        if ($id_forum == 0) return array();
		$forum 		= jClasses::getService('havefnubb~hfnuforum')	->getForum($id_forum);		
		$category 	= jClasses::getService('havefnubb~hfnucat')		->getCat($forum->id_cat);
		$info = array($forum,$category);
		return $info;
	}
    
    /**
     * get the posts of the given forum
     * @param integer $id_forum the current forum 
     * @param integer $page the current page
     * @param integer $nbPostPerPage the number of posts per page
     * @return array $page,$nbPosts,$posts if no record have been found, return page = 0 otherwise return the posts 
     */
    public function getPostsByIdForum($id_forum,$page,$nbPostPerPage) {
        
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
        
        return array($page,$nbPosts,$posts);        
    }
    
    /**
	 * updateViewing : update the counter of the views of a given post
     * @param id_post integer id post of the current post
     */    
    public function updateViewing($id_post) {
        if ($id_post == 0 ) return;
        $dao = jDao::get('havefnubb~posts'); 
        $post = $dao->get($id_post);
        $post->viewed = $post->viewed +1;
		$dao->update($post);                
    }

    /**
	 * view a given thread !
     * business check :
     * 1) do those id exist ?
     * 2) permission ok ?
     * 3) if parent_id > 0 then calculate the page + assign id_post with parent_id
     * business update :
     * 1) update the count of view of this thread
     * @param id_post integer id post of the current post
     * @param parent_id integer parent id of the current post
     * @return array of id_post, DaoRecord of Post, Paginator
     */    
    public function view($id_post,$parent_id) {
        global $gJConfig;
		$dao = jDao::get('havefnubb~posts');
		$post = $dao->get($id_post);

        if ($id_post == 0 or $post === false) {
            return array(null,null);
        }
                		
		if ( ! self::checkPerm('hfnu.posts.view','forum'.$post->id_forum) ) {
            return array(null,null);
        }
		
		$goto = 0;
		if ($parent_id > 0) {
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
		$this->updateViewing($id_post);
        
        return array($id_post,$post,$goto);
    }
    
    /**
     * save one post
     * @param $id_forum integer id forum of the post
     * @param id_post integer id post of the current post if editing of 0 if adding
     * @return $id_post integer id post of the editing post or the id of the post created
     */
    public function save($id_forum,$id_post=0) {
		global $gJConfig;
        $form = jForms::fill('havefnubb~posts',$id_post);

        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }

        //.. if the data are ok ; we get them !
        $subject	= $form->getData('subject');
        $message 	= $form->getData('message');
		
		if ( count($message) > $gJConfig->havefnubb['post_max_size'] and $gJConfig->havefnubb['post_max_size'] > 0) {
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
			$record->subject		= $subject;
			$record->message		= $message;
            $record->id_post  		= $id_post;
            $record->id_user 		= jAuth::getUserSession ()->id;
            $record->id_forum 		= $id_forum;
            $record->parent_id  	= 0;
            $record->status			= 'opened';
            $record->date_created 	= time();
            $record->date_modified 	= time();
            $record->viewed			= 0;
            $record->poster_ip 		= $_SERVER['REMOTE_ADDR'];
            
            $dao->insert($record);
            
            // now let's get the inserted id to put this one in parent_id column !
            $record->parent_id = $record->id_post;
            $id_post = $record->id_post;
            $parent_id = $record->parent_id;
            
            jEvent::notify('HfnuPostAfterInsert',array('id'=>$id_post));                        
			
        }
        // edit a post
        else {
            jEvent::notify('HfnuPostBeforeUpdate',array('id'=>$id_post));
            $record = $dao->get($id_post);
			$record->subject		= $subject;
			$record->message		= $message;
            $record->date_modified 	= time();
            jEvent::notify('HfnuPostAfterUpdate',array('id'=>$id_post));
			
        }
        
        // in all cases (id_post = 0 or not ) 
        // we have to update as we store the last insert id in the parent_id column
        $dao->update($record);
        
        jEvent::notify('HfnuPostAfterSave',array('id'=>$id_post));

        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));

        $tags = explode(",", $form->getData("tags"));

        jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'forumscope', $id_post);
        
        jForms::destroy('havefnubb~posts', $id_post);
        
        return $id_post;
        
    }

    /**
     * save a reply to one post
     * @param parent_id integer parent id of the current post if editing of 0 if adding
     * @return $record DaoRecord of the reply
     */    
    public function savereply($parent_id) {
		global $gJConfig;
        $form = jForms::fill('havefnubb~posts',$parent_id);

        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }
		
        $message 	= $form->getData('message');
		//is the size of the message limited ?
		if ( strlen($message) > $gJConfig->havefnubb['post_max_size'] 			                    
            and  $gJConfig->havefnubb['post_max_size'] > 0)
        {
			jMessage::add(jLocale::get('havefnubb~main.message.exceed.maximum.size', array($gJConfig->havefnubb['post_max_size'])),'error');
			return false;
		}
        
        jEvent::notify('HfnuPostBeforeSaveReply',array('id'=>$parent_id));
        
		$result = $form->prepareDaoFromControls('havefnubb~posts');
		$result['daorec']->date_created	= time();
		$result['daorec']->date_modified= time();
		$result['daorec']->status		= 'opened';
		$result['daorec']->poster_ip	= $_SERVER['REMOTE_ADDR'];
		$result['daorec']->viewed 		= 0;
		$result['daorec']->id_post  	= 0;
		$result['daorec']->id_user 		= jAuth::getUserSession ()->id;
		$result['dao']->insert($result['daorec']);
		$id_post = $result['daorec']->getPk();		
		
        jEvent::notify('HfnuPostAfterSaveReply',array('id_post'=>$id_post));
        
        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));
        
        jForms::destroy('havefnubb~posts', $parent_id);
        
        return $result['daorec'];        
    }
    
    /**
     * save a notification posted by a user
     * @param id_post integer id post of the current post if editing of 0 if adding
     * @return boolean status of success of this submit 
     */       
    public function savenotify($id_post) {
        
        $form = jForms::fill('havefnubb~notify',$id_post);

        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }        
        
        jEvent::notify('HfnuPostBeforeSaveNotify',array('id'=>$id_post));
        
		$result = $form->prepareDaoFromControls('havefnubb~notify');
		$result['daorec']->date_created		= time();
		$result['daorec']->date_modified	= time();
		$result['dao']->insert($result['daorec']);

        jEvent::notify('HfnuPostAfterSaveNotify',array('id'=>$id_post));
        
        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post,'datasource'=>'havefnubb~posts'));        
        
        jForms::destroy('havefnubb~notify', $id_post);
        
        return true;        
    }

    /**
     * change the status of the current THREAD (not just one post) !
     * @param $parent_id integer parent id of the thread
     * @param $id_post integer parent id of the thread
     * @param $status string the status to switch to
     * @param $censor_msg string of the censored message
     * @return $record DaoRecord 
     */     
    public function switchStatus($parent_id,$id_post,$status,$censor_msg='') {        
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
			$post = $this->$status($parent_id,$id_post,$censor_msg);
		}
				
        return $dao->get($id_post);       
    }
	
    /**
     * censor the current POST
     * @param $parent_id integer parent id of the thread
     * @param $id_post integer parent id of the thread
     * @param $status string the status to switch to
     * @param $censor_msg string of the censored message
     */     
    public function censored($parent_id,$id_post,$censor_msg) {        
        if ( $parent_id < 0 or $id_post < 1) return false;
		
        $dao = jDao::get('havefnubb~posts');                
        if ( $dao->censorIt($id_post,$censor_msg) ) {            
            jEvent::notify('HfnuPostAfterStatusChanged',array('id'=>$id_post,'status'=>'censored'));			
        }
    }

    /**
     * remove the censor of current POST
     * To uncensor :
     * 1) get the status of the Father Post
     * 2) apply the Father's status to the Son Post
     * @param $parent_id integer parent id of the thread
     * @param $id_post integer parent id of the thread
     * @param $status string the status to switch to
     * @param $censor_msg string of the censored message
     */     
    public function uncensored($parent_id,$id_post,$censor_msg='') {
        if ( $parent_id < 0 or $id_post < 1) return false;
		
		$dao = jDao::get('havefnubb~posts');
		$status = ( $id_post == $parent_id ) ? 'opened' : $this->getPost($parent_id)->status ;
        if ( $dao->updateStatusById($id_post,$status) ) {
            jEvent::notify('HfnuPostAfterStatusChanged',array('id'=>$id_post,'status'=>$this->getPost($parent_id)->status));
        }
    }
	
    /**
     * remove one post
     * @param $id_post integer id post to remove
     * @return boolean of the success or not
     */     
    public function delete($id_post) {
        if ($id_post == 0 ) return false;
		return $this->deletePost($id_post);
    }
    
	/**
	 * check the permissions/rights to the resources
	 * @param $rights the rights to check to the resource
	 * @param $resources the resource to check
	 * @return boolean 
	 */
    public static function checkPerm($rights,$ressources) {
        return jAcl2::check($rights,$ressources) ? true : false;        
    }
	
	/**
	 * this function permits to move a complet thread to another forum
	 * @param $id_post integer id post to move
	 * @param $id_forum integer id forum to move to
	 * @return boolean
	 */
	public function moveToForum($id_post,$id_forum) {
		if ($id_post == 0 or $id_forum == 0) return false;
		$dao = jDao::get('havefnubb~posts');
        $dao->moveToForum($id_post,$id_forum);
		return true;		
	}

	/**
	 * this function permits to split the thread to a forum
	 * @param $parent_id integer parent_id 
	 * @param $id_post integer id post 
	 * @param $id_forum integer id forum 
	 * @return $id_post_new the new Id
	 */
	public function splitToForum($parent_id,$id_post,$id_forum) {
		if ($id_post == 0 or $id_forum == 0 or $parent_id == 0) return false;
		$dao = jDao::get('havefnubb~posts');

		$datas = $dao->getAllFromCurrentIdPostWithParentId($parent_id,$id_post);
		
		$i			 = 0;
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
	 * @param $id_post integer id post to split
	 * @param $parent_id integer parent_id  of the current id post
	 * @param $new_parent_id parent id to attach to 
	 * @return boolean
	 */
	public function splitToThread($id_post,$parent_id,$new_parent_id) {
		if ($id_post == 0 or $parent_id == 0 or $new_parent_id == 0) return false;
		
		$dao = jDao::get('havefnubb~posts');		
		$datas = $dao->getAllFromCurrentIdPostWithParentId($parent_id,$id_post);
		
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

	/**
	 * this function permits to get the status of the posts
	 * @param $id_post integer id post to split
	 * @return $postStatus array
	 */	
	public function getPostStatus($id_post) {
        if (!isset(self::$postStatus[$id_post])) 
            self::$postStatus[$id_post] = jDao::get('havefnubb~newest_posts')->getPostStatus($id_post);
        return self::$postStatus[$id_post];		
	}
	
}

