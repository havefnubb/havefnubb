<?php
/**
 * @package     InstallWizard
 * @subpackage  pages
 * @author      Laurent Jouanneau
 * @copyright   2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that handles the pages for Installation wizard
 */
class hfnconfWizPage extends installWizardPage {

    /**
     * action to display the page
     * @param jTpl $tpl the template container
     */
    function show ($tpl) {
        if (!isset($_SESSION['hfnconf'])) {
            $_SESSION['hfnconf'] = $this->loadconf();
        }


        $themes = array();

        $dir = new DirectoryIterator(JELIX_APP_VAR_PATH.'themes/');
        foreach ($dir as $dirContent) {
            if ($dirContent->isDir() && !$dirContent->isDot())
                $themes[] = $dirContent->getFilename();
        }

        $tpl->assign('themes', $themes);
        $tpl->assign($_SESSION['hfnconf']);

        return true;
    }

    /**
     * action to process the page after the submit
     */
    function process() {
        $ini = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
        $errors = array();
        $_SESSION['hfnconf']['theme'] = trim($_POST['theme']);
        if ($_SESSION['hfnconf']['theme'] == '') {
            $errors[] = $this->locales['error.missing.theme'];
        }
        else {
            $ini->setValue('theme',$_SESSION['hfnconf']['theme']);
        }

        $_SESSION['hfnconf']['title'] = trim($_POST['title']);
        if ($_SESSION['hfnconf']['title'] == '') {
            $errors[] = $this->locales['error.missing.title'];
        }
        else {
            $ini->setValue('title',$_SESSION['hfnconf']['title'],'havefnubb');
        }

        $_SESSION['hfnconf']['description'] = trim($_POST['description']);
        $ini->setValue('description',$_SESSION['hfnconf']['description'],'havefnubb');

        if (count($errors)) {
            $_SESSION['hfnconf']['errors'] = $errors;
            return false;
        }
        $ini->save();
        unset($_SESSION['hfnconf']);
        return 0;
    }


    protected function loadconf() {
        $ini = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
        $config = array(
            'theme'=>$ini->getValue('theme'),
            'title'=>$ini->getValue('title','havefnubb'),
            'description'=>$ini->getValue('description','havefnubb'),
            'errors'=>array()
        );
        return $config;
    }


}
