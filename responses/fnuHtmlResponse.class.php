<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');

class fnuHtmlResponse extends jResponseHtml {

    public $bodyTpl = 'havefnubb~main';

    function __construct() {
        parent::__construct();

        // Include your common CSS and JS files here
    }

    protected function doAfterActions() {
        // Include all process in common for all actions, like the settings of the
        // main template, the settings of the response etc..
        global $gJConfig;
        global $HfnuConfig;
        
        if ($HfnuConfig->getValue('installed','main') == 0) $this->bodyTpl = 'havefnubb~main_not_installed';


        $language = preg_split('/_/',$gJConfig->locale);
       
        /* Dublin Core Meta and Content */
       
        $this->addHeadContent('<meta name="description" lang="'.$language[0].'" content="'.$HfnuConfig->getValue('description','main').'" />');
       
        $this->addHeadContent('<link rel="schema.dc" href="http://purl.org/dc/elements/1.1/"/>');
        $this->addHeadContent('<meta name="dc.title" lang="'.$language[0].'" content="'.$HfnuConfig->getValue('title','main').'" />');
        $this->addHeadContent('<meta name="dc.description" lang="'.$language[0].'" content="'.$HfnuConfig->getValue('description','main').'" />');
        $this->addHeadContent('<meta name="dc.language" content="'.$language[0].'" />');
        $this->addHeadContent('<meta name="dc.type" content="text" />');
        $this->addHeadContent('<meta name="dc.format" content="text/html" />');
        $this->addHeadContent('<link rel="section" href="'.jurl::get('havefnubb~default:index').'" title="'.jLocale::get('havefnubb~main.community').'" />');
        $this->addHeadContent('<link rel="section" href="'.jurl::get('hfnusearch~default:index').'" title="'.jLocale::get('havefnubb~main.search').'" />');
       
        /* Dublin Core */


        $chemin = $gJConfig->urlengine['basePath'].'themes/'.$gJConfig->theme.'/';
        $this->addCssLink($chemin.'css/havefnuboard.css');
        $this->addCssLink($chemin.'css/havefnuboard_posts.css');
        $this->addCssLink($chemin.'css/havefnuboard_users.css');

        $title = stripslashes($HfnuConfig->getValue('title','main'));
        $description = stripslashes($HfnuConfig->getValue('description','main'));

        if ($this->title)
            $this->title = $title . ' - ' . $this->title;        
        else
            $this->title = $title;       
        
        $pi = $GLOBALS['gJCoord']->request->urlPathInfo;

        if ($pi == '/' || $pi =='' ) {
            $this->body->assign('selectedMenuItem','community');
            $this->body->assign('home',1);
        } else if(preg_match('!^/([a-zA-Z]+)(/.*)?$!', $pi, $m)) {
            switch($m[1]) {
                //case 'newticket':
				case 'members':
                    $this->body->assign('home',0);                    
                    $this->body->assign('selectedMenuItem','members');
                    break;
                case 'search':
                    $this->body->assign('home',0);                    
                    $this->body->assign('selectedMenuItem','search');
                    break;
				case 'post':
                case 'forum':
                case 'cat':
                    $this->body->assign('home',0);                    
                    $this->body->assign('selectedMenuItem','community');
                    break;
                case 'tag':
                    $this->body->assign('home',0);                    
                    $this->body->assign('selectedMenuItem','tags');
                    break;                     
                case 'users':
                case 'messages':
                    $this->body->assign('home',0);                    
                    $this->body->assign('selectedMenuItem','users');
                    break;                     
                case 'auth':
                case 'registration':
                    $this->body->assign('home',0);                    
                    $this->body->assign('selectedMenuItem','');
                    break;
                case 'downloads':
                    $this->body->assign('home',0);                    
                    $this->body->assign('selectedMenuItem','downloads');
                    break;                    
                default:
                    $this->body->assign('home',1);
                    $this->body->assign('selectedMenuItem','community');            
            }
        }


        $this->body->assignIfNone('MAIN','');
        $this->body->assignIfNone('TITLE',$title);
        $this->body->assignIfNone('DESC',$description);
        $this->body->assignIfNone('BOARD_TITLE',$title);        
    }
}
