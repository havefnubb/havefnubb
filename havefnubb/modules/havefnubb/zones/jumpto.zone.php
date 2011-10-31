<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class the displays the dropdown menu to jump to another forum
 */
class jumptoZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.jumpto';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $id_forum = $this->param('id_forum');
        if (!$id_forum) return;

        $form = jForms::create('havefnubb~jumpto',$id_forum);
        $this->_tpl->assign('form',$form);
    }
}
