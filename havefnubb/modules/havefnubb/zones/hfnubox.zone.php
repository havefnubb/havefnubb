<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that handles the Box Widget
 */
class HfnuBoxWidget {
    /**
     *@var string title
     */
    public $title = '';
    /**
     *@var string content
     */
    public $content = '';
}
/**
 * Class the handles the response to the jEvent to populate the Widget box
 */
class hfnuboxZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.hfnubox';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $this->_tpl->assign('widgets', jEvent::notify('HfnuBoxWidget')->getResponse());
    }
}
