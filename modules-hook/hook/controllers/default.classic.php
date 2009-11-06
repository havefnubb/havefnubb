<?php
/**
* @package   havefnubb
* @subpackage hook
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
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

