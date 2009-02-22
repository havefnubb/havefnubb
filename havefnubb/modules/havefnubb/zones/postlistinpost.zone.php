<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postlistinpostZone extends jZone {
    protected $_tplname='zone.postlistinpost';

    protected function _prepareTpl(){
        global $HfnuConfig;
        $id_post = $this->param('id_post');
        $page = (int) $this->param('page');
        if (!$id_post) return;
        
        $srv_tags = jClasses::getService("jtags~tags");
        $tags = $srv_tags->getTagsBySubject('forumscope', $id_post);
        
        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => ' ',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("havefnubb~main.common.pagelinks.end"),
                      'area-size'   => 5);
        
        $nbRepliesPerPage = 0;
        $nbRepliesPerPage = (int) $HfnuConfig->getValue('replies_per_page');
              
        $daoPost = jDao::get('havefnubb~posts');
        // 3- total number of posts
        $nbReplies = $daoPost->findNbOfResponse($id_post);
        // 4- get the posts of the current forum, limited by point 1 and 2
        $posts = $daoPost->findByIdParent($id_post,$page,$nbRepliesPerPage);

		if(jAuth::isConnected()) 
			$this->_tpl->assign('current_user',jAuth::getUserSession ()->login);
		else
			$this->_tpl->assign('current_user','');
        
        $this->_tpl->assign('posts',$posts);
        $this->_tpl->assign('tags',$tags);
        $this->_tpl->assign('page',$page);
        $this->_tpl->assign('id_post',$id_post);
        $this->_tpl->assign('nbRepliesPerPage',$nbRepliesPerPage);
        $this->_tpl->assign('nbReplies',$nbReplies);        
        $this->_tpl->assign('properties',$properties);        
    }
}