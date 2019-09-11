<?php
/**
 * @package   havefnubb
 * @subpackage servinfo
 * @author    FoxMaSk
 * @copyright 2008-2011 FoxMaSk, 2019 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace HavefnuBB\ServerInfos;

class ServerInfoData {
    public $id = '';
    public $label = '';
    public $content = '';
    function __construct($id, $label, $content) {
        $this->id = $id;
        $this->label = $label;
        $this->content = $content;
    }
}
