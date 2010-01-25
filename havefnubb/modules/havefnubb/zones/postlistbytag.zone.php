<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class postlistbytagZone extends jZone {
	protected $_tplname='zone.postlistbytag';

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
