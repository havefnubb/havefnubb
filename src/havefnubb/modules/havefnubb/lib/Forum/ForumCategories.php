<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2019-2026 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Havefnubb\Havefnubb\Forum;
use jDao;

/**
 * main UI to manage the statement of the categories of HaveFnuBB!
 */
class ForumCategories
{
    /**
     * content of the category
     * @var $cat array
     */
    protected $cat = array();

    /**
     * get the category from the given id
     * @param integer $id current category
     * @return $cat array
     */
    public function getCat($id)
    {
        if (!isset($this->cat[$id])) {
            $this->cat[$id] = jDao::get('havefnubb~forum_cat')->get($id);
        }
        return $this->cat[$id];
    }
}
