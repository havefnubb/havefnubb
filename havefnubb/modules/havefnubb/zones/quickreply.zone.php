<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class quickreplyZone extends jZone {
    protected $_tplname='zone.quickreply';

    protected function _prepareTpl(){
        global $HfnuConfig;
        $id_post = $this->param('id_post');
        $id_forum = $this->param('id_forum');
        if (!$id_post) return;


		$daoUser = jDao::get('havefnubb~member');
		$user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
        
        $form = jForms::create('havefnubb~posts',$id_post);
		$form->setData('id_forum',$id_forum);
		$form->setData('id_user',$user->id);
		$form->setData('id_post',0);
        $form->setData('parent_id',$id_post);
        
        $this->_tpl->assign('form',$form);
		$this->_tpl->assign('id_post',$id_post);
    }
}