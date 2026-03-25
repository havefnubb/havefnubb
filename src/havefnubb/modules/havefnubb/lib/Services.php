<?php
/**
 * @author     Laurent Jouanneau
 * @copyright 2026 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
namespace Havefnubb\Havefnubb;

use Havefnubb\Havefnubb\Forum\Forums;
use Havefnubb\Havefnubb\Forum\Posts;

class Services {

    static protected $forums = null;

    /**
     * @return Forums
     */
    static function forums()
    {
        if (self::$forums === null) {
            self::$forums = new Forums();
        }
        return self::$forums;
    }

    static protected $posts = null;

    static function posts()
    {
        if (self::$posts === null) {
            self::$posts = new Posts();
        }
        return self::$posts;
    }

}
