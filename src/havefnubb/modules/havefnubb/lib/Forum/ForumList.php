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
 * contains and manage the list of forums
 */
class ForumList {

    protected $forumList = array();

    public $forumTree = array();

    function addForum($f)
    {
        // put the forum into the linear list
        $forum = new ForumRecord($f);
        $this->forumList[$f->id_forum] = $forum;

        if ($f->child_level > 0 && $f->parent_id) {
            // add the forum in its parent
            $this->forumList[$f->parent_id]->addChild($forum);
        }
        else if($forum->allowed()) {
            if (!isset($this->forumTree[$f->id_cat])) {
                $this->forumTree[$f->id_cat] = array($f->cat_name, array());
            }

            // add the forum in the top of tree
            $this->forumTree[$f->id_cat][1][] = $forum;
        }
    }

    function getLinearIterator()
    {
        $list = new \ArrayObject($this->forumList);
        $iterator = new ForumRecordIterator($list->getIterator());
        return $iterator;
    }
}
