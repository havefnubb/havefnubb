<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postsCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'=>array('auth.required'=>false),
        'edit'=>array('auth.required'=>true),
        'delete'=>array('auth.required'=>true),
        'quote'=>array('auth.required'=>true),
        'view' => array('history.add'=>true)
    );
    
    function view() {
        $id_post = (int) $this->param('id_post');
        if ($id_post == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
        }
        
        $dao = jDao::get('posts');        
        $post = $dao->get($id_post);
        
        $GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($post->subject) );
        
        // let's update the viewed counter
        $post->viewed = $post->viewed +1;
        $dao->update($post);
        
        $posts = $dao->findChildByIdPost($id_post);
        $posts_forum = $dao->findForumByIdPost($id_post);
        
        $dao = jDao::get('forum');
        $forum = $dao->get($posts_forum->id_forum);

		$daoCategory = jDao::get('category');
        // find category name for the current forum
		$category = $daoCategory->get($forum->id_cat);
        
        $tpl = new jTpl();
        $tpl->assign('posts',$posts);
        $tpl->assign('forum',$forum);
        $tpl->assign('category',$category);
        
        $rep = $this->getResponse('html');
        $rep->title = $post->subject;
        
        $rep->body->assign('MAIN', $tpl->fetch('postview'));
        return $rep;
    }
    
    function edit () {
        
    }
    
    function delete() {
        
    }
    
    function quote() {
        
    }
}

