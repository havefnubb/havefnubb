<?php
/**
* @package   havefnubb
* @subpackage responses
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');
/**
 * Class that manages the public part of all the template of HaveFnuBB
 */
class fnuHtmlResponse extends jResponseHtml {
    /**
     * @var $bodyTpl the main template of the entire application
     */
    public $bodyTpl = 'havefnubb~main';
    /**
     * method which manages the things that go to <HEAD>...</HEAD>
     */
    function __construct() {
        parent::__construct();
    }
    /**
     * method which manages 'globales' behavior/var
     */
    protected function doAfterActions() {
        global $gJConfig;

        $title = $gJConfig->havefnubb['title'];
        $description = $gJConfig->havefnubb['description'];
        $keywords = $gJConfig->havefnubb['keywords'];

        $language = preg_split('/_/',$gJConfig->locale);

        /* Dublin Core Meta and Content */
        $this->addHeadContent('<meta name="description" lang="'.$language[0].'" content="'.$description.'" />');
        $this->addHeadContent('<meta name="keywords" content="'.$keywords.'"/>');
        $this->addHeadContent('<meta name="language" content="'.$language[0].'"/>');
        $this->addHeadContent('<link rel="schema.dc" href="http://purl.org/dc/elements/1.1/"/>');
        $this->addHeadContent('<meta name="dc.title" lang="'.$language[0].'" content="'.$title.'" />');
        $this->addHeadContent('<meta name="dc.description" lang="'.$language[0].'" content="'.$description.'" />');
        $this->addHeadContent('<meta name="dc.language" content="'.$language[0].'" />');
        $this->addHeadContent('<meta name="dc.type" content="text" />');
        $this->addHeadContent('<meta name="dc.format" content="text/html" />');
        $this->addHeadContent('<link rel="section" href="'.htmlentities(jurl::get('havefnubb~default:index')).'" title="'.jLocale::get('havefnubb~main.community').'" />');
        $this->addHeadContent('<link rel="section" href="'.htmlentities(jurl::get('hfnusearch~default:index')).'" title="'.jLocale::get('havefnubb~main.search').'" />');

        /* Dublin Core */


        if ($this->title)
            $this->title = $title . ' - ' . $this->title;
        else
            $this->title = $title;

        if (empty($GLOBALS['gJCoord']->request->params)) {
            $this->body->assign('home',0);
            $this->body->assign('selectedMenuItem','members');
            $this->body->assign('currentIdForum',0);
        }
        else {
            list($ctrl,$method) = preg_split('/:/',$GLOBALS['gJCoord']->request->params['action']);

            switch ($GLOBALS['gJCoord']->request->params['module']) {
                case 'havefnubb' :
                    switch ($ctrl) {
                        case 'members':
                            if ($method != 'mail') {
                                $this->body->assign('home',0);
                                $this->body->assign('selectedMenuItem','members');
                                $this->body->assign('currentIdForum',0);
                            }
                            else  {
                                $this->body->assign('home',0);
                                $this->body->assign('selectedMenuItem','users');
                                $this->body->assign('currentIdForum',0);
                            }
                            break;
                        case 'posts':

                            $this->body->assign('home',0);
                            $this->body->assign('selectedMenuItem','community');
                            if ($method == 'view' or $method == 'lists')
                                $this->body->assign('currentIdForum',$GLOBALS['gJCoord']->request->params['id_forum']);
                            else
                                $this->body->assign('currentIdForum',0);
                            break;
                        case 'forum':
                        case 'cat':
                            $this->body->assign('home',0);
                            $this->body->assign('selectedMenuItem','community');
                            $this->body->assign('currentIdForum',0);
                            break;
                        default:
                            $this->body->assign('home',1);
                            $this->body->assign('selectedMenuItem','community');
                            $this->body->assign('currentIdForum',0);
                            break;
                    }
                    break;
                case 'hfnusearch' :
                        $this->body->assign('home',0);
                        $this->body->assign('selectedMenuItem','search');
                        $this->body->assign('currentIdForum',0);
                        break;
                case 'jcommunity':
                case 'jmessenger':
                        $this->body->assign('home',0);
                        $this->body->assign('selectedMenuItem','users');
                        $this->body->assign('currentIdForum',0);
                        break;
                case 'downloads':
                        $this->body->assign('home',0);
                        $this->body->assign('selectedMenuItem','downloads');
                        $this->body->assign('currentIdForum',0);
                        break;
                default:
                        $this->body->assign('home',1);
                        $this->body->assign('selectedMenuItem','community');
                        $this->body->assign('currentIdForum',0);
                        break;
            }
        }

        $this->body->assignIfNone('MAIN','');
        $this->body->assignIfNone('TITLE',$title);
        $this->body->assignIfNone('DESC',$description);
        $this->body->assignIfNone('BOARD_TITLE',$title);
    }
}
