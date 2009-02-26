<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postlistbytagZone extends jZone {
    protected $_tplname='zone.postlistbytag';

    protected function _prepareTpl(){
        $tag = $this->param('tag');
       
        $srvTags = jClasses::getService("jtags~tags");
        $tags = $srvTags->getSubjectsByTags($tag, "forumscope");
        
        $posts = '';
        $dao = jDao::get('havefnubb~posts');
        // We check the rights access to the posts in the template
        for ($i = 0 ; $i < count($tags) ; $i++) {            
            $posts[] = (array) $dao->get($tags[$i]);            
        }
        $this->_tpl->assign(compact('posts'));
    }
}