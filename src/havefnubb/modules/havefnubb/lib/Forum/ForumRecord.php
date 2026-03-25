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
 * contains informations about a forum
 */
class ForumRecord
{
    public $record = null;
    public $children = array();
    protected $forbidden = null;

    function __construct($r)
    {
        $this->record = $r;
    }

    /**
     * is this record allowed to be see?
     */
    function allowed()
    {
        if ($this->forbidden === null) {
            $this->forbidden = !\jAcl2::check('hfnu.forum.list', 'forum' . $this->record->id_forum);
            if ($this->forbidden)
                $this->disallow();
        }
        return !$this->forbidden;
    }

    /**
     * is this record disallowed to be see?
     */
    function disallow()
    {
        $this->forbidden = true;
        foreach ($this->children as $f) $f->disallow();
    }

    function addChild($c)
    {
        if (!$this->forbidden) {
            $this->children[] = $c;
            $c->allowed();
        } else {
            $c->disallow();
        }
    }

    function getLinearIteratorOnChildren()
    {
        return new \RecursiveIteratorIterator(new ForumRecordChildIterator($this->children), \RecursiveIteratorIterator::SELF_FIRST);
    }
}
