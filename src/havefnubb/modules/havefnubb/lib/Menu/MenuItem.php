<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2026 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
namespace Havefnubb\Havefnubb\Menu;

require JELIX_LIB_PATH . DIRECTORY_SEPARATOR .'..'. DIRECTORY_SEPARATOR . 'jelix-admin-modules'. DIRECTORY_SEPARATOR . 'master_admin' .DIRECTORY_SEPARATOR . 'classes' .DIRECTORY_SEPARATOR . 'masterAdminMenuItem.class.php';

/**
 * MenuItem of HaveFnuBB
 */
class MenuItem extends \masterAdminMenuItem
{

    /**
     * Sort Items of a menu
     * @param array $iteamA
     * @param array $iteamB
     */
    static function itemsSort($itemA, $itemB)
    {
        return ($itemA->order - $itemB->order);
    }
}
