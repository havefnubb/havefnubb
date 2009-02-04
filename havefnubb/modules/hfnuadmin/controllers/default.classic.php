<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @license    All right reserved
*/

class defaultCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
		'config' 	=> array( 'jacl2.right'=>'hfnu.admin.config.edit'),
        'category' 	=> array( 'jacl2.right'=>'hfnu.admin.index'),
        'forum' 	=> array( 'jacl2.right'=>'hfnu.admin.index'),
        'notify' 	=> array( 'jacl2.right'=>'hfnu.admin.index'),        
        'rank' 	    => array( 'jacl2.right'=>'hfnu.admin.index'),
        'phpinfo' 	=> array( 'jacl2.right'=>'hfnu.admin.server.info'),        
        'check_upgrade'=> array( 'jacl2.right'=>'hfnu.admin.config.view'),		
    );
    
    function index() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    }
    
    function config() {
        global $HfnuConfig ;        
        $rep = $this->getResponse('html');
		$submit = $this->param('validate');
        
		if ($submit == jLocale::get('hfnuadmin~config.saveBt') ) {
            
            $form = jForms::get('hfnuadmin~config');
            
            if (!$form->check()) {
                $rep = $this->getResponse('redirect');
                $rep->action='havefnubb~default:index';
                return $rep;
            }
            
            $HfnuConfig->setValue('title',htmlentities($this->param('title')));
            $HfnuConfig->setValue('description',htmlentities($this->param('description')));
            $HfnuConfig->setValue('theme',htmlentities($this->param('theme')));
            $HfnuConfig->setValue('rules',htmlentities($this->param('rules')));
            $HfnuConfig->setValue('webmaster_email',htmlentities($this->param('webmaster_email')));
            $HfnuConfig->setValue('admin_email',htmlentities($this->param('admin_email')));
            
            $HfnuConfig->setValue('posts_per_page',htmlentities($this->param('posts_per_page')));
            $HfnuConfig->setValue('replies_per_page',htmlentities($this->param('replies_per_page')));
            $HfnuConfig->setValue('members_per_page',htmlentities($this->param('members_per_page')));
            $HfnuConfig->setValue('stats_nb_of_lastpost',htmlentities($this->param('stats_nb_of_lastpost')));
            
            $HfnuConfig->save();
			jForms::destroy('hfnuadmin~config');
			$rep->action ='hfnuadmin~default:config';
			return $rep;            
        }
        else 
            $form = jForms::create('hfnuadmin~config');

        $form->setData('title',           stripslashes($HfnuConfig->getValue('title')));
        $form->setData('description',     stripslashes($HfnuConfig->getValue('description')));
        $form->setData('theme',           stripslashes($HfnuConfig->getValue('theme')));
        $form->setData('rules',           stripslashes($HfnuConfig->getValue('rules')));
        $form->setData('webmaster_email', stripslashes($HfnuConfig->getValue('webmaster_email')));
        $form->setData('admin_email',     stripslashes($HfnuConfig->getValue('admin_email')));
        $form->setData('posts_per_page',  (int) $HfnuConfig->getValue('posts_per_page'));
        $form->setData('replies_per_page',(int) $HfnuConfig->getValue('replies_per_page'));
        $form->setData('members_per_page',(int) $HfnuConfig->getValue('members_per_page'));
        $form->setData('stats_nb_of_lastpost',(int) $HfnuConfig->getValue('stats_nb_of_lastpost'));

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $rep->body->assign('MAIN',$tpl->fetch('config'));
        return $rep;
    }
    
    function categories() {
        $dao = jDao::get('havefnubb~category');
        $categories = $dao->findAll();
        $tpl = new jTpl();        
        $rep = $this->getResponse('html');
        $tpl->assign('categories',$categories);
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~category_index'));
        return $rep; 
    }
    
    function forums() {
    
        $form = jForms::create('hfnuadmin~category_list');
        
        $dao = jDao::get('havefnubb~category');
        $cats = $dao->findAll();
        
        $dao = jDao::get('havefnubb~forum');
        $forums = $dao->findAll(); 
        
        $tpl = new jTpl();        
        $rep = $this->getResponse('html');
        $tpl->assign('cats',$cats);
        $tpl->assign('forums',$forums);
        $tpl->assign('form',$form);
        
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~forum_index'));
        return $rep; 
    }
    
    function notifying() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
    }
    
    function ranks() {
        $dao = jDao::get('havefnubb~ranks');
        $ranks = $dao->findAll();
        $tpl = new jTpl();        
        $rep = $this->getResponse('html');
        $tpl->assign('ranks',$ranks);
        $rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~ranks_index'));
        return $rep;    
    }    
    
    function ban() {
        $rep = $this->getResponse('html');
        $rep->body->assign('MAIN', '');
        return $rep;
   }
   
    public function check_upgrade() {
        
        global $HfnuConfig;
        $url = $HfnuConfig->getValue('url_check_version');

        if (!ini_get('allow_url_fopen'))
            jMessage::add('Impossible de vérifier les mises à jour tant que \'allow_url_fopen\' est désactivé sur ce système.');
        else {   
            $fp = @fopen($url, 'r');
            $latestVersion = trim(@fread($fp, 16));
            @fclose($fp);
        
            if ($latestVersion == '')
                jMessage::add('La vérification de mise à jour a échouée pour une raison inconnue.');
            else {  
                $curVersion = str_replace(array('.', 'dev', 'beta', ' '), '', strtolower($HfnuConfig->getValue('version')));
                $curVersion = (strlen($curVersion) == 2) ? intval($curVersion) * 10 : intval($curVersion);
            
                $latestVersion = str_replace('.', '', strtolower($latestVersion));
                $latestVersion = (strlen($latestVersion) == 2) ? intval($latestVersion) * 10 : intval($latestVersion);
            
                if ($curVersion >= $latestVersion)
                    jMessage::add('Vous utilisez la dernière version de HaveFnuBB.');
                else
                    jMessage::add('Une nouvelle version de HaveFnuBB est disponible ! Vous pouvez télécharger cette dernière version sur <a href="http://forge.jelix.org/projects/havefnubb/">HaveFnuBB!</a>.');
            }
        }
        $rep = $this->getResponse('redirect');
        $rep->action = 'default:index';
        return $rep;
        
    }
    
    public function phpinfo() {
        phpinfo();
    }
}   