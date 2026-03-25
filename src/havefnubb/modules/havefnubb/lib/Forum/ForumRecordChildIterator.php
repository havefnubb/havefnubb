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
 * iterator to iterate on the forum hierarchy
 */
class ForumRecordChildIterator extends \ArrayIterator implements \RecursiveIterator
{

    #[\ReturnTypeWillChange]
    public function getChildren ( ) /* : ?RecursiveIterator */
    {
        return new ForumRecordChildIterator($this->current()->children);
    }

    public function hasChildren ( ) : bool
    {
        return (count($this->current()->children) > 0);
    }
}
