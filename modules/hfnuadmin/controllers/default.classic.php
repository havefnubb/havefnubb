<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class defaultCtrl extends jController {
    /**
    *
    */
    public $pluginParams = array(
        '*'		=>	array('auth.required'=>true,
						  'hfnu.check.installed'=>true,
						  'banuser.check'=>true,
					),
		'index' => array( 'jacl2.right'=>'hfnu.admin.index'),
		'config'=> array( 'jacl2.right'=>'hfnu.admin.config'),
		'check_upgrade'=> array( 'jacl2.right'=>'hfnu.admin.config'),				
    );
    
    function index() {
		$rep = $this->getResponse('html');
		return $rep;
	}
    function config() {
        global $HfnuConfig;
		
		$defaultConfig =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
		
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
			
			$defaultConfig->setValue('theme',htmlentities($this->param('theme')));
			$defaultConfig->setValue('webmasterEmail',htmlentities($this->param('webmaster_email')),'mailer');
			$defaultConfig->save();
			
			
            $HfnuConfig->setValue('theme',htmlentities($this->param('theme')));
            $HfnuConfig->setValue('rules',htmlentities($this->param('rules')));
            
            $HfnuConfig->setValue('admin_email',htmlentities($this->param('admin_email')));
            
            $HfnuConfig->setValue('posts_per_page',htmlentities($this->param('posts_per_page')));
            $HfnuConfig->setValue('replies_per_page',htmlentities($this->param('replies_per_page')));
            $HfnuConfig->setValue('members_per_page',htmlentities($this->param('members_per_page')));
            $HfnuConfig->setValue('stats_nb_of_lastpost',htmlentities($this->param('stats_nb_of_lastpost')));            
            
            $HfnuConfig->setValue('elapsed_time_after_posting_before_editing',
                                  htmlentities($this->param('elapsed_time_after_posting_before_editing')));            
            $HfnuConfig->setValue('elapsed_time_between_two_post_by_same_ip',
                                  htmlentities($this->param('elapsed_time_between_two_post_by_same_ip')));
            $HfnuConfig->setValue('post_max_size',
                                  htmlentities($this->param('post_max_size')));
            
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
        $form->setData('webmaster_email', stripslashes($defaultConfig->getValue('webmasterEmail','mailer')));
        $form->setData('admin_email',     stripslashes($HfnuConfig->getValue('admin_email')));
        $form->setData('posts_per_page',  (int) $HfnuConfig->getValue('posts_per_page'));
        $form->setData('replies_per_page',(int) $HfnuConfig->getValue('replies_per_page'));
        $form->setData('members_per_page',(int) $HfnuConfig->getValue('members_per_page'));
        $form->setData('stats_nb_of_lastpost',(int) $HfnuConfig->getValue('stats_nb_of_lastpost'));
        $form->setData('elapsed_time_after_posting_before_editing',(int) $HfnuConfig->getValue('elapsed_time_after_posting_before_editing'));
        $form->setData('elapsed_time_between_two_post_by_same_ip',(int) $HfnuConfig->getValue('elapsed_time_between_two_post_by_same_ip'));
        $form->setData('post_max_size',(int) $HfnuConfig->getValue('post_max_size'));
                              
        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $rep->body->assign('MAIN',$tpl->fetch('config'));
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
        $rep->action = 'hfnuadmin~default:index';
        return $rep;
        
    }
}   