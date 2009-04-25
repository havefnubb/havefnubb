<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class posts_repliesZone extends jZone {
    protected $_tplname='zone.posts_replies';

    protected function _prepareTpl(){
        global $HfnuConfig;
		
        $id_post 	= (int) $this->param('id_post');
		$id_forum 	= (int) $this->param('id_forum');
        $page 		= (int) $this->param('page');
		
        if (!$id_post) return;
		if (!$id_forum) return;

		if ($page < 0 ) $page = 0;
        
        $srvTags = jClasses::getService("jtags~tags");
        $tags = $srvTags->getTagsBySubject('forumscope', $id_post);

        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => ' ',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("havefnubb~main.common.pagelinks.end"),
                      'area-size'   => 5);
        // 1- get the nb of replies per page
        $nbRepliesPerPage = 0;
        $nbRepliesPerPage = (int) $HfnuConfig->getValue('replies_per_page','messages');
        // 2- get the post
        $daoPost = jDao::get('havefnubb~posts');
        // 3- total number of posts
        $nbReplies = $daoPost->countResponse($id_post);
        // 4- get the posts of the current forum, limited by point 1 and 2
        $posts = $daoPost->findByIdParent($id_post,$page,$nbRepliesPerPage);

		// id_post is the parent_id ; we need to know
		// the status of it to determine if the member can
		// reply to the current thread
		$parentPost = $daoPost->get($id_post);

		$groups = jAcl2DbUserGroup::getGroupList(jAuth::getUserSession ()->login);

		// check if we have found record ; 
		if ($posts->rowCount() == 0) {
			$posts = $daoPost->findByIdParent($parentPost->parent_id,0,$nbRepliesPerPage);
			$page = 0;
		}

		if(jAuth::isConnected()) 
			$this->_tpl->assign('current_user',jAuth::getUserSession ()->login);
		else
			$this->_tpl->assign('current_user','');
        
		$formStatus = jForms::create('havefnubb~posts_status');

		$this->_tpl->assign('formStatus',$formStatus);
        $this->_tpl->assign('posts',$posts);
		$this->_tpl->assign('id_forum',$id_forum);
        $this->_tpl->assign('tags',$tags);
        $this->_tpl->assign('page',$page);
        $this->_tpl->assign('id_post',$id_post);
        $this->_tpl->assign('nbRepliesPerPage',$nbRepliesPerPage);
        $this->_tpl->assign('nbReplies',$nbReplies);        
        $this->_tpl->assign('properties',$properties);
		$this->_tpl->assign('parentStatus',$parentPost->status);
		$this->_tpl->assign('groups',$groups);
    }
}