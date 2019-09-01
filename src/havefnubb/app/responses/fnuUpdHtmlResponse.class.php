<?php
/**
* @package   havefnubb
* @subpackage responses
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once (JELIX_LIB_CORE_PATH.'response/jResponseHtml.class.php');
/**
 * Class that manages the public part of all the template of HaveFnuBB
 */
class fnuUpdHtmlResponse extends jResponseHtml {
    /**
     * @var $bodyTpl the main template of the entire application
     */
    public $bodyTpl = 'hfnuupd~main';
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
        $conf = jApp::config();

        $title = $conf->havefnubb['title'];
        $description = $conf->havefnubb['description'];

        $language = preg_split('/_/',$conf->locale);

        $this->addHeadContent('<meta name="description" lang="'.$language[0].'" content="'.$description.'" />');

        if ($this->title)
            $this->title = $title . ' - ' . $this->title;
        else
            $this->title = $title;

        $this->body->assignIfNone('MAIN','');
        $this->body->assignIfNone('TITLE',$title);
        $this->body->assignIfNone('DESC',$description);
        $this->body->assignIfNone('BOARD_TITLE',$title);
    }
}
