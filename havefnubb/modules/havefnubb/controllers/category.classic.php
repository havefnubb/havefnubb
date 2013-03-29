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
* Controller to manage any category events
*/
class categoryCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*'	=> array('auth.required'=>false,
            'banuser.check'=>true,
            ),
        'view' 	=> array('history.add'=>true,
                        'history.label'=>'Accueil',
                        'history.title'=>'Aller vers la page d\'accueil')
    );
    /**
     * View a given Category of forum then the list of forums
     */
    function view() {
        $ctitle = $this->param('ctitle');
        $id_cat = (int) $this->param('id_cat');
        if ($id_cat == 0 ) {
            $rep = $this->getResponse('redirect');
            $rep->action = 'havefnubb~default:index';
            return $rep;
        }


        // add the category name in the page title
        // so
        // 1) get the category record
        $category = jClasses::getService('havefnubb~hfnucat')->getCat($id_cat);

        // check that the title of the category exist
        // if not => error404

        if (jUrl::escape($ctitle,true) != jUrl::escape($category->cat_name,true)) {
            $rep = $this->getResponse('redirect');
            $rep->action = jApp::config()->urlengine['notfoundAct'];
            return $rep;
        }

        $rep = $this->getResponse('html');

        // 2) assign the title page
        $rep->title = $category->cat_name;

        $historyPlugin = $GLOBALS['gJCoord']->getPlugin('history');
        $histname = ucfirst ( htmlentities($category->cat_name,ENT_COMPAT,'UTF-8') );
        $historyPlugin->change('label',  $histname);
        $historyPlugin->change('title', $histname );

        $categories = jDao::get('havefnubb~forum')->findParentByCatId($id_cat);
        foreach ($categories as $cat) {
            if ( jAcl2::check('hfnu.forum.list','forum'.$cat->id_forum) ) {
                // get the list of forum to build the RSS link
                $url = jUrl::get('havefnubb~posts:rss', array('ftitle'=>$cat->forum_name,
                                                        'id_forum'=>$cat->id_forum));
                $rep->addHeadContent('<link rel="alternate" type="application/rss+xml" title="'.$cat->forum_name.'" href="'.htmlentities($url).'" />');
            }
        }
        $tpl = new jTpl();

        $tpl->assign('action','view');
        $tpl->assign('cat_name',$category->cat_name);
        $tpl->assign('categories',$categories);
        $tpl->assign('currentIdForum',0);
        $rep->body->assign('MAIN', $tpl->fetch('index'));
        return $rep;
    }
}
