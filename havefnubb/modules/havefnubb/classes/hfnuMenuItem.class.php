<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008-2011 FoxMaSk
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
require JELIX_LIB_PATH . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR . 'jelix-admin-modules'. DIRECTORY_SEPARATOR . 'master_admin' .DIRECTORY_SEPARATOR . 'classes' .DIRECTORY_SEPARATOR . 'masterAdminMenuItem.class.php';
/**
 * MenuItem of HaveFnuBB
 */
class hfnuMenuItem extends masterAdminMenuItem {

}
/**
 * Sort the Item of the menu
 * @param array $iteamA
 * @param array $iteamB
 */
function hfnuItemSort($itemA, $itemB)
{
    return ($itemA->order - $itemB->order);
}
