<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2011 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller for manage any specific Management tasks related to Posts events
*/
class postsmgrCtrl extends jController {
    /**
     * @var $pluginParams plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*' => array('auth.required'=>false,
                    'banuser.check'=>true
                    ),

        'moveToForum'   => array('auth.required'=>true),
        'notify'        => array('auth.required'=>true),
        'censor'        => array('jacl2.right'=>'hfnu.admin.post'),
        'uncensor'      => array('jacl2.right'=>'hfnu.admin.post'),
        'savecensor'    => array('jacl2.right'=>'hfnu.admin.post'),
        'status'        => array('jacl2.right'=>'hfnu.admin.post'),

    );
    /**
     * @var static $statusClosed array of the 'closed' status
     */
    //4='closed',2='pinedclosed',5='censored'
    public static $statusClosed = array(4,2,5);

    public static $statusAvailable = array('pined',
                                           'pinedclosed',
                                           'opened',
                                           'closed',
                                           'censored',
                                           'uncensored',
                                           'hidden');

    /**
     * notify something from a given post (from the thread_id) to the admin
     */
    function notify() {

        $id_post = (int) $this->param('id_post');
        if ($id_post == 0 ) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be 0] $id_post','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        $post = $hfnuposts->getPost($id_post);

        if ( ! jAcl2::check('hfnu.posts.notify','forum'.$post->id_forum) ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        // crumbs infos
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($post->id_forum);
        if (! $forum) {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be false] $forum','DEBUG');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
            $rep->setHttpStatus('404', 'Not found');
            return $rep;
        }

        $form = jForms::create('havefnubb~notify',$id_post);
        $form->setData('id_user',jAuth::getUserSession ()->id);
        $form->setData('id_post',$id_post);
        $form->setData('id_forum',$post->id_forum);
        $form->setData('thread_id',$post->thread_id);

        //set the needed parameters to the template
        $tpl = new jTpl();
        $tpl->assign('forum',$forum);
        $tpl->assign('id_post',$id_post);
        $tpl->assign('form', $form);
        $tpl->assign('forum', $forum);
        $tpl->assign('subject', $post->subject);
        $tpl->assign('heading',jLocale::get("havefnubb~post.form.notify.message") . ' - ' . $post->subject);
        $tpl->assign('submitAction','havefnubb~postsmgr:savenotify');

        $rep = $this->getResponse('html');
        $rep->title = jLocale::get("havefnubb~post.form.notify.message") . ' - ' . $post->subject;
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~posts.notify'));
        return $rep;
    }
    /**
    * save the datas posted from the notify form
    */
    function savenotify() {
        $id_post    = (int) $this->param('id_post');
        $thread_id  = (int) $this->param('thread_id');
        $id_forum   = (int) $this->param('id_forum');

        if ( ! jAcl2::check('hfnu.posts.notify','forum'.$id_forum) ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $submit = $this->param('validate');
        if ($submit == jLocale::get('havefnubb~post.form.saveBt') ) {
            $rep = $this->getResponse('redirect');

            if ($id_post ==  0 or $id_forum == 0) {
                jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
                $rep->action = 'havefnubb~default:index';
                return $rep;
            }

            //let's save the post
            $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
            $result = $hfnuposts->savenotify($id_post,$thread_id);
            if ($result === false) {
                jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
                $rep->action = 'havefnubb~default:index';
                return $rep;
            }

            jMessage::add(jLocale::get('havefnubb~main.common.notify.added'),'ok');
        }

        $rep = $this->getResponse('redirect');
        $rep->action = 'havefnubb~default:index';
        return $rep;
    }
    /**
     * change the status of the post
     * known status : 'opened','closed','pined','pinedclosed','hidden'
     */
    function status () {

        $thread_id = (int) $this->param('thread_id');
        $id_post = (int) $this->param('id_post');
        $status  = (int) $this->param('status');

        $rep = $this->getResponse('redirect');

        $form = jForms::fill('havefnubb~posts_status');
        if (!$form) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }

        if (!$form->check()) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }

        $post = jClasses::getService('havefnubb~hfnuposts')->switchStatus($thread_id,$id_post,$status);

        if ($post === false ) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep->action = 'havefnubb~default:index';
        }
        else {
            jMessage::add(jLocale::get('havefnubb~post.status.'.self::$statusAvailable[$status -1]),'ok');
            $rep->action = 'havefnubb~posts:viewtogo';
            $rep->params = array('id_post'=>$post->id_post,
                    'thread_id'=>$thread_id,
                    'id_forum'=>$post->id_forum,
                    'ftitle'=>$post->forum_name,
                    'ptitle'=>$post->subject,
                    'go'=>$post->id_post);
        }
        return $rep;
    }
    /**
     * this function permits to move a complet thread to another forum
     */
    public function moveToForum() {
        $id_forum = (int) $this->param('id_forum');
        $thread_id = (int) $this->param('thread_id');
        $id_post = (int) $this->param('id_post');

        if ( $id_forum == 0) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }

        if ($thread_id == 0 or $id_post == 0) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~posts:lists';
            $rep->params = array('id_forum'=>$id_forum);
            return $rep;
        }

        if ( ! jAcl2::check('hfnu.admin.post') ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        //let's move the thread
        $hfnuposts = jClasses::getService('havefnubb~hfnuposts');
        $result = $hfnuposts->moveToForum($thread_id,$id_forum);

        if ($result === false ) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~posts:lists';
            $rep->params = array('id_forum'=>$id_forum);
            return $rep;
        }

        $post = $hfnuposts->getPost($id_post);
        jMessage::add(jLocale::get('havefnubb~main.common.thread.moved'),'ok');
        $rep = $this->getResponse('redirect');
        $rep->params = array('ftitle'=>$post->forum_name,
                            'ptitle'=>$post->subject,
                            'id_forum'=>$id_forum,
                            'id_post'=>$post->id_post,
                            'thread_id'=>$post->thread_id);
        $rep->action ='havefnubb~posts:view';
        return $rep;
    }
    /**
     * 'Wizard' to ask to the admin where to move the selected thread,
     * starting from the current message
     */
    public function splitTo() {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $id_post    = (int) $this->param('id_post');
        $thread_id  = (int) $this->param('thread_id');
        $id_forum   = (int) $this->param('id_forum');

        if (($id_post == 0 or $id_forum == 0 or $thread_id == 0) or
            ($id_post == 0 and $id_forum == 0 and $thread_id == 0))
            {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }

        $form = jForms::create('havefnubb~split');
        $form->setData('id_post',$id_post);
        $form->setData('thread_id',$thread_id);
        $form->setData('id_forum',$id_forum);
        $form->setData('step',1);

        $dao = jDao::get('havefnubb~posts');
        $post = $dao->get($id_post);

        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('title',$post->subject);
        $tpl->assign('form',$form);
        $tpl->assign('id_post',$id_post);
        $tpl->assign('thread_id',$thread_id);
        $tpl->assign('id_forum',$id_forum);
        $tpl->assign('step',1);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~split.to'));
        $rep->title = jLocale::get("havefnubb~main.split.this.thread.from.this.message") . ' : ' . $post->subject;
        return $rep;
    }
    /**
     * 'Wizard' to ask to the admin where to move the selected thread,
     * starting from the current message
     */
    public function splitedTo() {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $form = jForms::fill('havefnubb~split');
        if (!$form) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
        if (!$form->check()) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
        $submit = $this->param('validate');
        if ( $submit == jLocale::get('havefnubb~post.form.saveBt') ) {
            // let's define the possible actions we can do :
            // where to split this thread  :
            // 1) in the same forum and create a new one
            // 2) in another forum and create a new one
            // 3) link to an existing thread in the SAME forum
            $possibleActions = array('same_forum','others','existings');
            // the choice is ?
            $choice = (string) $this->param('choice');

            if (! in_array($choice,$possibleActions) ) {
                jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this should not be a valid choice] in_array($choice,$possibleActions)','DEBUG');
                $rep = $this->getResponse('html');
                $tpl = new jTpl();
                $rep->body->assign('MAIN', $tpl->fetch('havefnubb~404.html'));
                $rep->setHttpStatus('404', 'Not found');
                return $rep;
            }

            $dao = jDao::get('havefnubb~posts');
            //post record of the current post to move/spluit
            $post = $dao->get($form->getData('id_post'));
            switch ($choice) {
                case 'same_forum' :
                    $id_forum = (int) $this->param('id_forum');
                    $id_post = jClasses::getService('havefnubb~hfnuposts')->splitToForum($form->getData('thread_id'),$form->getData('id_post'),$id_forum);
                    if ($id_post > 0 ) $result = true; else $result = false;
                    break;
                case 'others' :
                    // the id_forum change to the new selected one
                    $id_forum = (int) $this->param('other_forum');
                    $id_post = jClasses::getService('havefnubb~hfnuposts')->splitToForum($form->getData('thread_id'),$form->getData('id_post'),$id_forum);
                    if ($id_post > 0 ) $result = true; else $result = false;
                    break;
                case 'existings' :
                    // the thread_id change to the new selected one
                    $new_thread_id = (int) $this->param('existing_thread');
                    $id_forum = $form->getData('id_forum');
                    $id_post = jClasses::getService('havefnubb~hfnuposts')->splitToThread($form->getData('id_post'),$form->getData('thread_id'),$new_thread_id);
                    break;
            }
            $dao = jDao::get('havefnubb~posts');
            //post record of the moved/splited post
            $post = $dao->get($id_post);

            $rep = $this->getResponse('redirect');

            if ($post === false) {
                jMessage::add(jLocale::get('havefnubb~main.common.thread.cant.be.moved'),'error');
                $rep = $this->getResponse('redirect');
                $rep->action = 'havefnubb~default:index';
                return $rep;
            }
            else {
                jMessage::add(jLocale::get('havefnubb~main.common.thread.moved'),'ok');
                $rep->params = array('ftitle'=>$post->forum_name,
                                    'ptitle'=>$post->subject,
                                    'id_forum'=>$id_forum,
                                    'id_post'=>$post->id_post,
                                    'thread_id'=>$post->thread_id);
                $rep->action ='havefnubb~posts:view';
            }

            return $rep;
        }
        else {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
    }
    /**
     * censored this post (or thread if thread_id = id_post )
     */
    public function censor () {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $rep = $this->getResponse('html');

        $id_post    = (int) $this->param('id_post');
        $thread_id  = (int) $this->param('thread_id');

        if ($id_post < 1 or $thread_id < 0) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }

        $form = jForms::create('havefnubb~censor',$id_post);
        $form->setData('id_post',$id_post);
        $form->setData('thread_id',$thread_id);

        $tpl = new jTpl();
        $tpl->assign('form',$form);
        $tpl->assign('id_post',$id_post);
        $tpl->assign('thread_id',$thread_id);
        $tpl->assign('title',jClasses::getService('havefnubb~hfnuposts')->getPost($id_post)->subject);
        $rep->body->assign('MAIN',$tpl->fetch('havefnubb~censor'));
        return $rep;
    }
    /**
     * save the censored message
     */
    public function savecensor() {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }

        $rep = $this->getResponse('redirect');
        $id_post    = (int) $this->param('id_post');
        $thread_id  = (int) $this->param('thread_id');

        if ($id_post < 1 or $thread_id < 0) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }

        $form = jForms::fill('havefnubb~censor',$id_post);
        if (!$form) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep->action = 'havefnubb~postsmgr:censor';
            $rep->params = array('id_post'=>$id_post,'thread_id'=>$thread_id);
            return $rep;
        }

        if (!$form->check()) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep->action = 'havefnubb~postsmgr:censor';
            $rep->params = array('id_post'=>$id_post,'thread_id'=>$thread_id);
            return $rep;
        }

        //censoring an entire thread
        $result = jClasses::getService('havefnubb~hfnuposts')
                    ->censor($thread_id,$id_post,$form->getData('censored_msg')
                        );

        if ($result === false ) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }
        else {
            $post  = $result;
            jMessage::add(jLocale::get('havefnubb~post.status.censored'),'ok');
            $rep->action = 'havefnubb~posts:viewtogo';
            $rep->params = array('id_post'=>$post->id_post,
                                'thread_id'=>$thread_id,
                                'id_forum'=>$post->id_forum,
                                'ftitle'=>$post->forum_name,
                                'ptitle'=>$post->subject,
                                'go'=>$post->id_post);
            return $rep;
        }
    }
    /**
     * uncensored this id post (or thread if thread_id = id_post)
     */
    public function uncensor() {
        if ( ! jAcl2::check('hfnu.admin.post') ) {
            jMessage::add(jLocale::get('havefnubb~main.permissions.denied'),'error');
            $rep = $this->getResponse('html');
            $tpl = new jTpl();
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~403.html'));
            $rep->setHttpStatus('403', 'Permission denied');
            return $rep;
        }
        $id_post    = (int) $this->param('id_post');
        $thread_id  = (int) $this->param('thread_id');

        $post = jClasses::getService('havefnubb~hfnuposts')->uncensor($thread_id,$id_post);//'uncensored'

        if ($post === false) {
            jMessage::add(jLocale::get('havefnubb~main.invalid.datas'),'error');
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }

        $rep = $this->getResponse('redirect');
        jMessage::add(jLocale::get('havefnubb~post.status.uncensored'),'ok');
        $rep->action = 'havefnubb~posts:view';
        $rep->params = array('id_post'=>$post->id_post,
                            'thread_id'=>$thread_id,
                            'id_forum'=>$post->id_forum,
                            'ftitle'=>$post->forum_name,
                            'ptitle'=>$post->subject);
        return $rep;
    }
}
