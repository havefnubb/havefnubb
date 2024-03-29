<?php
/**
 * @package   havefnubb
 * @subpackage responses
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008-2011 FoxMaSk, 2019-2023 Laurent Jouanneau
 * @link      https://havefnubb.jelix.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

namespace Havefnubb\Havefnubb\Response;
use jApp;
use jLocale;
use jResponseHtml;

require_once(JELIX_LIB_CORE_PATH . 'response/jResponseHtml.class.php');

/**
 * Class that manages the public part of all the template of HaveFnuBB
 */
class HtmlResponse extends jResponseHtml
{
    /**
     * @var string $bodyTpl the main template of the entire application
     */
    public $bodyTpl = 'havefnubb~main';

    /**
     * method which manages the things that go to <HEAD>...</HEAD>
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * method which manages 'globales' behavior/var
     */
    protected function doAfterActions()
    {
        $conf = jApp::config();

        $title = $conf->havefnubb['title'];
        $description = $conf->havefnubb['description'];
        $keywords = $conf->havefnubb['keywords'];

        $language = preg_split('/_/', $conf->locale);

        /* Dublin Core Meta and Content */
        $this->addHeadContent('   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
');
        $this->addHeadContent('<meta name="description" lang="' . $language[0] . '" content="' . $description . '" />');
        $this->addHeadContent('<meta name="keywords" content="' . $keywords . '"/>');
        $this->addHeadContent('<meta name="language" content="' . $language[0] . '"/>');
        $this->addHeadContent('<link rel="schema.dc" href="http://purl.org/dc/elements/1.1/"/>');
        $this->addHeadContent('<meta name="dc.title" lang="' . $language[0] . '" content="' . $title . '" />');
        $this->addHeadContent('<meta name="dc.description" lang="' . $language[0] . '" content="' . $description . '" />');
        $this->addHeadContent('<meta name="dc.language" content="' . $language[0] . '" />');
        $this->addHeadContent('<meta name="dc.type" content="text" />');
        $this->addHeadContent('<meta name="dc.format" content="text/html" />');
        $this->addHeadContent('<link rel="section" href="' . htmlentities(\jUrl::get('havefnubb~default:index')) . '" title="' . jLocale::get('havefnubb~main.community') . '" />');
        $this->addHeadContent('<link rel="section" href="' . htmlentities(\jUrl::get('hfnusearch~default:index')) . '" title="' . jLocale::get('havefnubb~main.search') . '" />');

        /* Dublin Core */


        if ($this->title)
            $this->title = $title . ' - ' . $this->title;
        else
            $this->title = $title;

        $coord = jApp::coord();
        if (empty($coord->request->params)) {
            $this->body->assign('home', 0);
            $this->body->assign('selectedMenuItem', 'members');
            $this->body->assign('currentIdForum', 0);
        } else {
            list($ctrl, $method) = preg_split('/:/', $coord->request->params['action']);

            switch ($coord->request->params['module']) {
                case 'havefnubb' :
                    switch ($ctrl) {
                        case 'members':
                            if ($method != 'mail') {
                                $this->body->assign('home', 0);
                                $this->body->assign('selectedMenuItem', 'members');
                                $this->body->assign('currentIdForum', 0);
                            } else {
                                $this->body->assign('home', 0);
                                $this->body->assign('selectedMenuItem', 'users');
                                $this->body->assign('currentIdForum', 0);
                            }
                            break;
                        case 'posts':

                            $this->body->assign('home', 0);
                            $this->body->assign('selectedMenuItem', 'community');
                            if ($method == 'view' or $method == 'lists')
                                $this->body->assign('currentIdForum', $coord->request->params['id_forum']);
                            else
                                $this->body->assign('currentIdForum', 0);
                            break;
                        case 'forum':
                        case 'cat':
                            $this->body->assign('home', 0);
                            $this->body->assign('selectedMenuItem', 'community');
                            $this->body->assign('currentIdForum', 0);
                            break;
                        default:
                            $this->body->assign('home', 1);
                            $this->body->assign('selectedMenuItem', 'community');
                            $this->body->assign('currentIdForum', 0);
                            break;
                    }
                    break;
                case 'hfnusearch' :
                    $this->body->assign('home', 0);
                    $this->body->assign('selectedMenuItem', 'search');
                    $this->body->assign('currentIdForum', 0);
                    break;
                case 'jcommunity':
                case 'jmessenger':
                    $this->body->assign('home', 0);
                    $this->body->assign('selectedMenuItem', 'users');
                    $this->body->assign('currentIdForum', 0);
                    break;
                case 'downloads':
                    $this->body->assign('home', 0);
                    $this->body->assign('selectedMenuItem', 'downloads');
                    $this->body->assign('currentIdForum', 0);
                    break;
                default:
                    $this->body->assign('home', 1);
                    $this->body->assign('selectedMenuItem', 'community');
                    $this->body->assign('currentIdForum', 0);
                    break;
            }
        }

        $this->body->assignIfNone('MAIN', '');
        $this->body->assignIfNone('TITLE', $title);
        $this->body->assignIfNone('DESC', $description);
        $this->body->assignIfNone('BOARD_TITLE', $title);
    }
}
