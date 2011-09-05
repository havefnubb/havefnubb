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
* Controller to manage any specific forum events
*/
class forumCtrl extends jController {
    /**
     * @var $pluginParams plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'     => array('auth.required'=>false,
                'banuser.check'=>true
                ),
        'mark_all_as_read' => array('auth.required'=>true,
                'banuser.check'=>true
                ),
        'mark_forum_as_read' => array('auth.required'=>true,
                'banuser.check'=>true
                ),
        'subscribe' => array('auth.required'=>true,
                'banuser.check'=>true
                ),
    );
    /**
    * display the RSS of the forum
    */
    public function read_rss() {
        global $gJConfig;
        $ftitle = jUrl::escape($this->param('ftitle'),true);

        $id_forum = (int) $this->param('id_forum');

        if ( ! jAcl2::check('hfnu.posts.list','forum'.$id_forum) ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }

        if ($id_forum == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'default:index';
            return $rep;
        }
        $forum = jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum);

        if (jUrl::escape($forum->forum_name,true) != $ftitle )
        {
            $rep = $this->getResponse('redirect');
            $rep->action = $gJConfig->urlengine['notfoundAct'];
            return $rep;
        }

        $GLOBALS['gJCoord']->getPlugin('history')->change('label', htmlentities($forum->forum_name,ENT_COMPAT,'UTF-8'));

        $feed_reader = new jFeedReader;
        $feed_reader->setCacheDir(JELIX_APP_VAR_PATH.'feeds');
        $feed_reader->setTimeout(2);
        $feed_reader->setUserAgent('HaveFnuBB - http://www.havefnubb.org/');
        $feed = $feed_reader->parse($forum->forum_url);

        $rep = $this->getResponse('html');
        $tpl = new jTpl();
        $tpl->assign('feed',$feed);
        $tpl->assign('forum',$forum);
        $rep->title = $forum->forum_name;
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~forum_rss.view'));
        return $rep;
    }
    /**
     * Mark one given forum as read
     */
    public function mark_forum_as_read() {
        $id_forum = (int) $this->param('id_forum');
        jClasses::getService('havefnubb~hfnuread')->markForumAsRead($id_forum);

        jMessage::add(jLocale::get('havefnubb~forum.forum.marked.as.read'));
        $rep = $this->getResponse('redirect');
        $rep->action = 'havefnubb~posts:lists';
        $rep->params = array('id_forum'=>$id_forum,
                             'ftitle'=>jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum)->forum_name);
        return $rep;
    }
    /**
     * Mark all forum as read
     */
    public function mark_all_as_read() {
        jClasses::getService('havefnubb~hfnuread')->markAllAsRead();

        jMessage::add(jLocale::get('havefnubb~forum.all.forum.marked.as.read'));
        $rep = $this->getResponse('redirect');
        $rep->action = 'default:index';
        return $rep;
    }
    /**
     * Subscribe to this forum
     */
    public function subscribe() {
        $id_forum = (int) $this->param('id_forum');
        jClasses::getService('havefnubb~hfnuforum')->subscribe($id_forum);

        jMessage::add(jLocale::get('havefnubb~forum.subscribe.to.this.forum.done'));
        $rep = $this->getResponse('redirect');
        $rep->action = 'havefnubb~posts:lists';
        $rep->params = array('id_forum'=>$id_forum,
                             'ftitle'=>jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum)->forum_name);
        return $rep;
    }
    /**
     * Unsubscribe to this forum
     */
    public function unsubscribe() {
        $id_forum = (int) $this->param('id_forum');
        jClasses::getService('havefnubb~hfnuforum')->unsubscribe($id_forum);

        jMessage::add(jLocale::get('havefnubb~forum.unsubscribe.to.this.forum.done'));
        $rep = $this->getResponse('redirect');
        $rep->action = 'havefnubb~posts:lists';
        $rep->params = array('id_forum'=>$id_forum,
                             'ftitle'=>jClasses::getService('havefnubb~hfnuforum')->getForum($id_forum)->forum_name);
        return $rep;
    }
}
