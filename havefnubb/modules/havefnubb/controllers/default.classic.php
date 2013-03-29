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
* Controller to manage any default events
*/
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'     => array('auth.required'=>false,
                        'banuser.check'=>true
                        ),
        'cloud' => array(
                        'history.add'=>true,
                        'history.label'=>'Accueil',
                        'history.title'=>'Aller vers la page d\'accueil'
                         ),
        'index' => array(
                        'history.add'=>true,
                        'history.label'=>'Accueil',
                        'history.title'=>'Aller vers la page d\'accueil')
    );
    /**
     * Main page
     */
    function index() {
        $title = stripslashes(jApp::config()->havefnubb['title']);
        $rep = $this->getResponse('html');

        $historyPlugin = jApp::coord()->getPlugin('history');

        $historyPlugin->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
        $historyPlugin->change('title', jLocale::get('havefnubb~main.goto_homepage'));

        $forums = jClasses::getService('hfnuforum');

        $forumsList = $forums->getFullList();

        // generate rss links list
        foreach($forumsList->getLinearIterator() as $f) {
            // get the list of forum to build the RSS link
            $url = jUrl::get('havefnubb~posts:rss', array('ftitle'=>$f->record->forum_name,
                                                    'id_forum'=>$f->record->id_forum));
            $rep->addHeadContent('<link rel="alternate" type="application/rss+xml" title="'.$f->record->forum_name.' - RSS" href="'.htmlentities($url).'" />');
            $url = jUrl::get('havefnubb~posts:atom', array('ftitle'=>$f->record->forum_name,
                                                    'id_forum'=>$f->record->id_forum));
            $rep->addHeadContent('<link rel="alternate" type="application/atom+xml" title="'.$f->record->forum_name.' - ATOM " href="'.htmlentities($url).'" />');
        }

        $tpl = new jTpl();
        $tpl->assign('selectedMenuItem','community');
        $tpl->assign('currentIdForum',0);
        $tpl->assign('action','index');
        $tpl->assign('forumsList',$forumsList);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~index'));
        return $rep;
    }
    /**
    * Display cloud of message from a given tag
    */
    function cloud () {
        $tag = $this->param('tag');

        $title = stripslashes(jApp::config()->havefnubb['title']);
        $rep = $this->getResponse('html');

        jApp::coord()->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ). ' - ' . jLocale::get('havefnubb~main.cloud'));
        jApp::coord()->getPlugin('history')->change('title', jLocale::get('havefnubb~main.cloud'));

        $rep->title = jLocale::get('havefnubb~main.cloud.posts.by.tag',$tag);
        $rep->body->assignZone('MAIN', 'havefnubb~postlistbytag',array('tag'=>$tag));
        return $rep;
    }

    /**
    * The rules of the forum
    */
    function rules() {
        $tpl = new jTpl();
        if (jApp::config()->havefnubb['rules'] != '') {
            $rep = $this->getResponse('html');
            $tpl->assign('rules',jApp::config()->havefnubb['rules']);
            $rep->body->assign('MAIN', $tpl->fetch('havefnubb~rules'));
        }
        else {
            jLog::log(__METHOD__ . ' line : ' . __LINE__ . ' [this action should not be used] rules are empty','DEBUG');
            $rep = $this->getResponse('html', true);
            $rep->bodyTpl = 'havefnubb~404.html';
            $rep->setHttpStatus('404', 'Not Found');
            return $rep;
        }
        return $rep;
    }
}
