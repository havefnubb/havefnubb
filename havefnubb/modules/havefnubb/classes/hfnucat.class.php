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
 * main UI to manage the statement of the categories of HaveFnuBB!
 */
class hfnucat {
	/**
	 * content of the category
	 * @var $cat array
	 */
	public static $cat = array() ;
	/**
	 * get the category from the given id
	 * @param integer $id current category
	 * @return $cat array
	 */
	public static function getCat($id) {
		if (!isset(self::$cat[$id]))
			self::$cat[$id] = jDao::get('havefnubb~forum_cat')->get($id);
		return self::$cat[$id];
	}
}
