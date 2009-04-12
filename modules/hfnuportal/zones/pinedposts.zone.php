<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class pinedpostsZone extends jZone {
    protected $_tplname='zone.pinedposts';

    protected function _prepareTpl(){

        global $HfnuConfig;
        
        $dao = jDao::get('havefnubb~posts');
        $posts = $dao->findAllPinedPost();
        
		if(jAuth::isConnected()) 
			$this->_tpl->assign('current_user',jAuth::getUserSession ()->login);
		else
			$this->_tpl->assign('current_user','');
            
        $this->_tpl->assign('posts',$posts);		

    }
}