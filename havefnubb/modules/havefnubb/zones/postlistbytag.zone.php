<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the posts by tag of the forum
 */
class postlistbytagZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.postlistbytag';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $tag = $this->param('tag');

        $srvTags = jClasses::getService("jtags~tags");
        $tags = $srvTags->getSubjectsByTags($tag, "forumscope");

        $posts = array();
        // We check the rights access to the posts in the template
        foreach ($tags as $tag)
            if ( jClasses::getService('havefnubb~hfnuposts')->getPost($tag) !== false)
                $posts[] = jClasses::getService('havefnubb~hfnuposts')->getPost($tag);

        $this->_tpl->assign('posts',$posts);
    }
}
