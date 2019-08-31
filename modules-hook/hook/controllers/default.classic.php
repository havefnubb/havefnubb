<?php
/**
* @package   havefnubb
* @subpackage hook
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */
    function index() {
        $rep = $this->getResponse('html');

        return $rep;
    }
}
