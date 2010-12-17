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
* Controller to manage any default events
*/
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'	=> array('auth.required'=>false,
                        'banuser.check'=>true
                        ),
        'cloud'	=> array(
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
        global $gJConfig;
        $title = stripslashes($gJConfig->havefnubb['title']);
        $rep = $this->getResponse('html');

        $GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ) );
        $GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.goto_homepage'));

        $dao = jDao::get('havefnubb~forum_cat');
        $categories = $dao->findAllCatWithFathers();
        $nbCat = $categories->rowCount();
        $data = array();
        $id_cat = '';
        foreach ($categories as $cat) {
            if ($id_cat == 0 or $id_cat != $cat->cat_id) {
                if ( jAcl2::check('hfnu.forum.list','forum'.$cat->id_forum) ) {
                    $data[] = $cat;
                    // get the list of forum to build the RSS link
                    $url = jUrl::get('havefnubb~posts:rss', array('ftitle'=>$cat->forum_name,
                                                            'id_forum'=>$cat->id_forum));
                    $rep->addHeadContent('<link rel="alternate" type="application/rss+xml" title="'.$cat->forum_name.'" href="'.htmlentities($url).'" />');
                }
            }
        }

        $tpl = new jTpl();
        //$rep->body->assignZone('MAIN', 'havefnubb~category',array('data'=>$data,'nbCat'=>$nbCat));
        $tpl->assign('selectedMenuItem','community');
        $tpl->assign('currentIdForum',0);
        $tpl->assign('action','index');
        $tpl->assign('categories',$data);
        $tpl->assign('nbCat',$nbCat);
        $rep->body->assign('MAIN', $tpl->fetch('havefnubb~index'));
        return $rep;
    }
    /**
    * Display cloud of message from a given tag
    */
    function cloud () {
        $tag = $this->param('tag');

        global $gJConfig;
        $title = stripslashes($gJConfig->havefnubb['title']);
        $rep = $this->getResponse('html');

        $GLOBALS['gJCoord']->getPlugin('history')->change('label', ucfirst ( htmlentities($title,ENT_COMPAT,'UTF-8') ). ' - ' . jLocale::get('havefnubb~main.cloud'));
        $GLOBALS['gJCoord']->getPlugin('history')->change('title', jLocale::get('havefnubb~main.cloud'));

        $rep->title = jLocale::get('havefnubb~main.cloud.posts.by.tag',$tag);
        $rep->body->assignZone('MAIN', 'havefnubb~postlistbytag',array('tag'=>$tag));
        return $rep;
    }

    /**
    * The rules of the forum
    */
    function rules() {
        global $gJConfig;
        $tpl = new jTpl();
        if ($gJConfig->havefnubb['rules'] != '') {
            $rep = $this->getResponse('html');
            $tpl->assign('rules',$gJConfig->havefnubb['rules']);
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
