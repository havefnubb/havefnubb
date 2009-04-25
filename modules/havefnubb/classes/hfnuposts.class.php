<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/* main UI to manage the statement of the posts */

class hfnuposts {


    /*
     * get specific info to be display in the breadcrumb and title of each page
     * @param  integer $id_forum  the current forum
     * @return  array composed by the forum datas of the current forum and the category datas of the current forum
     */
	public function getCrumbs($id_forum) {
		
        if ($id_forum == 0) return array();
        
		// get info to display them in the breadcrumb
        $daoForum = jDao::get('havefnubb~forum');
        // find info for the current forum
        $forum = $daoForum->get($id_forum);
		
		
		$daoCategory = jDao::get('havefnubb~category');
		// find category name for the current forum
		$category = $daoCategory->get($forum->id_cat);
		
		$info = array($forum,$category);
		
		return $info;
	}
    
    /*
     * get the post of the given forum
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
    
    
    public function updateViewing($id_post) {
        if ($id_post == 0 ) return;
        $dao = jDao::get('havefnubb~posts'); 
        $post = $dao->get($id_post);
        $post->viewed = $post->viewed +1;
		$dao->update($post);                
    }
    
    
    public function view($id_post,$parent_id) {
        global $HfnuConfig;
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
	
			$nbRepliesPerPage = (int) $HfnuConfig->getValue('replies_per_page','messages');			
			// calculate the offset of this id_post
			$goto = (ceil ($child/$nbRepliesPerPage) * $nbRepliesPerPage) - $nbRepliesPerPage;
			if ($goto < 0 ) $goto = 0;
			// replacing the id_post by parent_id for the posts_replies.zones.php
			$id_post = $parent_id;	
		}		
		
		// let's update the viewed counter
		$this->updateViewing($id_post);
        
        return array($post,$goto);
    }
        
    public function save($user,$id_forum,$id_post=0) {
		global $HfnuConfig;
        $form = jForms::fill('havefnubb~posts',$id_post);

        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }

        //.. if the data are ok ; we get them !
        $subject	= $form->getData('subject');
        $message 	= $form->getData('message');
		
		if ( count($message) > $HfnuConfig->getValue('post_max_size','messages') and  $HfnuConfig->getValue('post_max_size','messages') > 0) {
			jMessage::add(jLocale::get('havefnubb~main.message.exceed.maximum.size', array($HfnuConfig->getValue('post_max_size','messages'))),'error');
			return false;
		}
        
        //CreateRecord object
        $dao = jDao::get('havefnubb~posts');						
        
        // create a post
        if ($id_post == 0) {
            jEvent::notify('HfnuPostBeforeSave',array('id'=>$id_post));
            $record = jDao::createRecord('havefnubb~posts');
        }
        // edit a post
        else {
            jEvent::notify('HfnuPostBeforeUpdate',array('id'=>$id_post));
            $record = $dao->get($id_post);
        }
        
        $record->subject	= $subject;
        $record->message	= $message;
        
        // store the datas
        // if id_post = 0 then
        // it's an adding 			
        if ($id_post == 0 ) {			
            $record->id_post  	= $id_post;
            $record->id_user 	= $user->id;
            $record->id_forum 	= $id_forum;

            $record->parent_id  = 0;
            $record->status		= 'opened';
            $record->date_created = time();
            $record->date_modified = time();
            $record->viewed		= 0;
            $record->poster_ip = $_SERVER['REMOTE_ADDR'];
            
            $dao->insert($record);
            $record->parent_id = $record->id_post;
            $id_post = $record->id_post;
            $parent_id = $record->parent_id;
            
            jEvent::notify('HfnuPostAfterInsert',array('id'=>$id_post));
            
            jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));
            
        } else {
            $record->date_modified = time();
            jEvent::notify('HfnuPostAfterUpdate',array('id'=>$id_post));
        }
        // otherwise it's an update
        // in all case we have to
        // update as we store the last insert id in the parent_id column
        $dao->update($record);
        
        jEvent::notify('HfnuPostAfterSave',array('id'=>$id_post));
        
        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));

        $tags = explode(",", $form->getData("tags"));

        jClasses::getService("jtags~tags")->saveTagsBySubject($tags, 'forumscope', $id_post);
        
        jForms::destroy('havefnubb~posts', $id_post);
        
        return $id_post;
        
    }
    
    public function savereply($user,$id_forum,$parent_id,$id_post) {
		global $HfnuConfig;        
        $form = jForms::create('havefnubb~posts',$parent_id);
		$form->initFromRequest();

        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }

        //.. if the data are ok ; we get them !
        $subject	= $form->getData('subject');
        $message 	= $form->getData('message');

		if ( strlen($message) > $HfnuConfig->getValue('post_max_size','messages') and  $HfnuConfig->getValue('post_max_size','messages') > 0) {
			jMessage::add(jLocale::get('havefnubb~main.message.exceed.maximum.size', array($HfnuConfig->getValue('post_max_size','messages'))),'error');
			return false;
		}
		
        
        jEvent::notify('HfnuPostBeforeSaveReply',array('id'=>$id_post));
        
        //CreateRecord object
        $dao = jDao::get('havefnubb~posts');		
        $record = jDao::createRecord('havefnubb~posts');
        
        // let's create the record of this reply
        $record->subject	= $subject;
        $record->message	= $message;			

        $record->id_post  	= 0;
        $record->id_user 	= $user->id;
        $record->id_forum 	= $id_forum;

        $record->parent_id  = $parent_id;
        $record->status		= 'opened';
        $record->date_created = time();
        $record->date_modified = time();
        $record->viewed		= 0;
        $record->poster_ip = $_SERVER['REMOTE_ADDR'];
        $dao->insert($record);
        
        jEvent::notify('HfnuPostAfterSaveReply',array('id_post'=>$record->id_post));
        
        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));
        
        jForms::destroy('havefnubb~posts', $parent_id);
        
        return $record;
        
    }
    
    
    public function savenotify($user,$id_forum,$id_post) {
        
        $form = jForms::fill('havefnubb~notify',$id_post);

        //.. if the data are not ok, return to the form and display errors messages form
        if (!$form->check()) {
            return false;
        }        

        //.. if the data are ok ; we get them !
        $subject	= $form->getData('subject');
        $message 	= $form->getData('message');
        
        jEvent::notify('HfnuPostBeforeSaveNotify',array('id'=>$id_post));
        
        //CreateRecord object
        $dao = jDao::get('havefnubb~notify');		
        $record = jDao::createRecord('havefnubb~notify');
        
        // let's create the record of this reply
        $record->subject	= $subject;
        $record->message	= $message;			

        $record->id_post  	= $id_post;
        $record->id_forum  	= $id_forum;
        $record->id_user 	= $user->id;

        $record->date_created = time();
        $record->date_modified = time();

        $dao->insert($record);
        
        jEvent::notify('HfnuPostAfterSaveNotify',array('id'=>$id_post));
        
        jEvent::notify('HfnuSearchEngineAddContent',array('id'=>$id_post));        
        
        jForms::destroy('havefnubb~notify', $id_post);
        
        return true;
        
    }
    
    public function switchStatus($parent_id,$status) {
        $statusAvailable = array('opened','closed','pined','pinedclosed');
		if (! in_array($status,$statusAvailable)) {
			jMessage::add(jLocale::get('havefnubb~post.invalid.status'),'error');
            return false;
		}        
        if ($parent_id < 0 ) return false;
        
        $dao = jDao::get('havefnubb~posts');
        $post = $dao->get($parent_id);
        
        if ( $dao->updateStatusByIdParent($parent_id,$status) ) {            
            jEvent::notify('HfnuPostAfterStatusChanged',array('id'=>$parent_id,'status'=>$status));
        }
        return $post;
        
    }
    
    public function delete($id_post) {
        if ($id_post == 0 ) return false;
		$dao = jDao::get('havefnubb~posts');
        $dao->delete($id_post);
        return true;
    }
    
    public static function checkPerm($rights,$ressources) {
        return jAcl2::check($rights,$ressources) ? true : false;
        
    }
    
}
?>