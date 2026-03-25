<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2010-2026 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
namespace Havefnubb\Havefnubb\Forum;

/**
 * iterator to iterate on the forum list, and returns only allowed forums
 */
class ForumRecordIterator extends \FilterIterator
{
    public function accept() : bool
    {
        $forum = $this->getInnerIterator()->current();
        if( $forum->allowed()) {
            return true;
        }
        return false;
    }
}
