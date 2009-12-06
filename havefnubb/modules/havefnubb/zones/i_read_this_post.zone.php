<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @license   http://www.gnu.org/licenses/gpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class i_read_this_postZone extends jZone {
    protected $_tplname='zone.i_read_this_post';

    protected function _prepareTpl(){
        $id_post = (int) $this->getParam('id_post');
        $id_forum = (int)  $this->getParam('id_forum');
        //@TODO : go in read_forum table to check when we read this forum for the last time + 5mins
        $bool =  jClasses::getService('havefnubb~hfnuread')->getReadPost($id_post,$id_forum) === false ? false  : true;
        $this->_tpl->assign('bool',$bool);
    }
}
