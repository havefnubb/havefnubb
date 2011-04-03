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
 * Class the displays the a form to quiclky reply to a post
 */
class quickreplyZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.quickreply';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $thread_id  = (int) $this->param('thread_id');
        $id_post    = (int) $this->param('id_post');
        $id_forum   = (int) $this->param('id_forum');
        if ($id_post < 1) return;
        if ($id_forum < 1) return;

        $daoUser = jDao::get('havefnubb~member');
        if (jAuth::isConnected())
            $user = $daoUser->getByLogin( jAuth::getUserSession ()->login);
        else {
            $user = new StdClass;
            $user->id=0;
        }
        $post = jClasses::getService('havefnubb~hfnuposts')->getPost($id_post);
        $subject = '';
        if ($post->subject != '') {
            $subject = $post->subject;
        }
        if (jAuth::isConnected())
            $form = jForms::create('havefnubb~posts',$thread_id);
        else
            $form = jForms::create('havefnubb~posts_anonym',$thread_id);
        $form->setData('id_forum',$id_forum);
        $form->setData('id_user',$user->id);
        $form->setData('id_post',0);
        $form->setData('thread_id',$thread_id);
        $form->setData('subject',$subject);

        $this->_tpl->assign('form',$form);
        $this->_tpl->assign('id_post',$id_post);
        $this->_tpl->assign('thread_id',$thread_id);
    }
}
