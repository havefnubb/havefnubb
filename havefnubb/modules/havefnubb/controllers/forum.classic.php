<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class forumCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'=>array('auth.required'=>false),
		'view' => array('history.add'=>true)
    );
 
    
    function view() {
        global $HfnuConfig;
        $id = (int) $this->param('id');
        if ($id == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }

        $daoForum = jDao::get('forum');
        // find info for the current forum
        $forum = $daoForum->get($id);

		$daoCategory = jDao::get('category');
        // find category name for the current forum
		$category = $daoCategory->get($forum->id_cat);
		        
        // let's build the pagelink var
        // A Preparing / Collecting datas
        // 0- the properties of the pager
        $properties = array('start-label' => ' ',
                      'prev-label'  => '',
                      'next-label'  => '',
                      'end-label'   => jLocale::get("forum.pagelinks.end"),
                      'area-size'   => 5);
        // 1- get the offset parm if exist
        $page = 0;
        if ( $this->param('page') > 0 )
            $page = (int) $this->param('page');
        // 2- limit per page 
        $nbPostPerPage = 0;
        $nbPostPerPage = $HfnuConfig->getValue('posts_per_page','board');
              
        $daoPost = jDao::get('posts');
        // 3- total number of posts
        $nbPosts = $daoPost->findNbOfPostByForumId($id);
        // 4- get the posts of the current forum, limited by point 1 and 2
        $posts = $daoPost->findByIdForum($id,$page,$nbPostPerPage);

        // change the label of the breadcrumb
		$GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($forum->forum_name) . ' - ' . jLocale::get('havefnubb~forum.page') . ' ' .($page+1));
		
        $rep = $this->getResponse('html');
        if ($page == 0)
            $rep->title = $forum->forum_name;
        else
            $rep->title = $forum->forum_name . ' - ' . jLocale::get('havenfubb~forum.page') . ' ' .($page+1) ;
        $tpl = new jTpl();
        
        // B- Using the collected datas
        $tpl->assign('tableclass','forumView');
        // 1- the posts 
        $tpl->assign('posts',$posts);		
        // 2- the forum		
        $tpl->assign('forum',$forum);
		// 3 - the category
		$tpl->assign('category',$category);
        // 4- vars for pagelinks
        // see A-1 / A-2 / A-3
        $tpl->assign('page',$page);                
        $tpl->assign('nbPostPerPage',$nbPostPerPage);
        $tpl->assign('nbPosts',$nbPosts);
        $tpl->assign('id',$id);
        $tpl->assign('properties',$properties);
        
        $rep->body->assign('MAIN', $tpl->fetch('forumlist'));
        return $rep;        
    }        
}

