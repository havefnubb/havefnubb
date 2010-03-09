<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Zone to Handle a child forum
 */
class forumchildZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.forumchild';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){

		$id_forum   = $this->param('id_forum');
		$lvl        = $this->param('lvl');

		$calledFrom = $this->param('calledFrom');

		if (! $id_forum ) return;
		if (! $lvl ) return;
		if (! $calledFrom ) return;

		$dao = jDao::get('havefnubb~forum');

		$forumChilds = $dao->findChild($id_forum,$lvl);

		$this->_tpl->assign('childs',$forumChilds->rowCount());
		$this->_tpl->assign('calledFrom',$calledFrom);
		$this->_tpl->assign('lvl',$lvl);
		$this->_tpl->assign('forumChilds',$forumChilds);
	}
}
