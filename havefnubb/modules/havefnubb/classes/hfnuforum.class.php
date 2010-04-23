<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008 FoxMaSk
 * @link      http://havefnubb.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
* main UI to manage the statement of the forums of HaveFnuBB!
*/
class hfnuforum {
	/**
	 * content of the forum
	 * @var $forums array
	 */
	public static $forums = array() ;
	/**
	 * content of the forum of a given category
	 * @var $forumsParents array
	 */
	public static $forumsParents = array() ;

	/**
	 * get info of the current forum
	 * @param  integer $id of the current forum
	 * @return array composed by the forum datas of the current forum
	 */
	public static function getForum($id) {
		if (!isset(self::$forums[$id]))
			self::$forums[$id] = jDao::get('havefnubb~forum')->get($id);
		return self::$forums[$id];
	}
	/**
	 * get info of the current category
	 * @param  integer $id of the current category
	 * @return array composed by the forum datas of the current category
	 */
	public static function findParentByCatId($id) {
		self::$forumsParents = array();
		foreach ( jDao::get('havefnubb~forum')->findParentByCatId($id) as $rec )
			self::$forumsParents[] = $rec;
		return self::$forumsParents;
	}
}
