<?php
/**
* @package   havefnubb
* @subpackage hfnucontact
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Zone to Handle the details of the module
 */
class aboutZone extends jZone {
	/**
	 *@var string $_tplname the template name used by the zone
	 */
	protected $_tplname='zone.about';
	/**
	 * function to manage data before assigning to the template of its zone
	 */
	protected function _prepareTpl(){
		jClasses::inc('havefnubb~modulexml');
		$moduleInfo = modulexml::parse('hfnucontact');
		$this->_tpl->assign('moduleInfo',$moduleInfo);
	}
}
