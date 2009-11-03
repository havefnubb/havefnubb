<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
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
		$rep = $this->getResponse('redirect');
		$rep->action = 'master_admin~default:index';
		return $rep;
	}
    function config() {
        global $HfnuConfig;
		
		$defaultConfig 	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
		$floodConfig 	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'flood.coord.ini.php');
		$timeoutConfig 	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'timeout.coord.ini.php');
		$socialNetwork	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'social.network.ini.php');
		
        $rep = $this->getResponse('html');
		$submit = $this->param('validate');
        
		if ($submit == jLocale::get('hfnuadmin~config.saveBt') ) {
            
			
            $form = jForms::fill('hfnuadmin~config');

            if (!$form->check()) {
                $rep = $this->getResponse('redirect');
                $rep->action='havefnubb~default:index';
                return $rep;
            }
            
            $HfnuConfig->setValue('title',				htmlentities($this->param('title')),'main');
            $HfnuConfig->setValue('description',		htmlentities($this->param('description')),'main');
			
			$defaultConfig->setValue('webmasterEmail',	htmlentities($this->param('webmaster_email')),'mailer');
			$defaultConfig->save();
			
            $HfnuConfig->setValue('rules',				$this->param('rules'),'main');
            $HfnuConfig->setValue('admin_email',		htmlentities($this->param('admin_email')),'main');            
            $HfnuConfig->setValue('posts_per_page',		htmlentities($this->param('posts_per_page')),'messages');
            $HfnuConfig->setValue('replies_per_page',	htmlentities($this->param('replies_per_page')),'messages');
            $HfnuConfig->setValue('members_per_page',	htmlentities($this->param('members_per_page')),'messages');
            $HfnuConfig->setValue('stats_nb_of_lastpost',htmlentities($this->param('stats_nb_of_lastpost')),'messages');
            $HfnuConfig->setValue('post_max_size',		htmlentities($this->param('post_max_size')),'messages');
			$HfnuConfig->setValue('avatar_max_width',	htmlentities($this->param('avatar_max_width')),'main');
			$HfnuConfig->setValue('avatar_max_height',	htmlentities($this->param('avatar_max_height')),'main');			
            $HfnuConfig->save();
            
            $floodConfig->setValue('elapsed_time_after_posting_before_editing',
                                  htmlentities($this->param('elapsed_time_after_posting_before_editing')));            
            $floodConfig->setValue('elapsed_time_between_two_post_by_same_ip',
                                  htmlentities($this->param('elapsed_time_between_two_post_by_same_ip')));
            $floodConfig->save();
						
			$timeoutConfig->setValue('timeout_connected',(int) htmlentities($this->param('timeout_connected')));
			$timeoutConfig->setValue('timeout_visit',(int) htmlentities($this->param('timeout_visit')));
			$timeoutConfig->save();
			
			
			$socialNetwork->setValue('twitter',(int) $this->param('social_network_twitter'));
			$socialNetwork->setValue('digg',(int) $this->param('social_network_digg'));
			$socialNetwork->setValue('delicious',(int) $this->param('social_network_delicious'));
			$socialNetwork->setValue('facebook',(int) $this->param('social_network_facebook'));
			$socialNetwork->setValue('reddit',(int) $this->param('social_network_reddit'));
			$socialNetwork->setValue('netvibes',(int) $this->param('social_network_netvibes'));
			$socialNetwork->save();
			
			jForms::destroy('hfnuadmin~config');
			$rep->action ='hfnuadmin~default:config';
			return $rep;            
        }
        else 
            $form = jForms::create('hfnuadmin~config');

        $form->setData('title',           stripslashes($HfnuConfig->getValue('title','main')));
        $form->setData('description',     stripslashes($HfnuConfig->getValue('description','main')));
        $form->setData('rules',           stripslashes($HfnuConfig->getValue('rules','main')));
        $form->setData('webmaster_email', stripslashes($defaultConfig->getValue('webmasterEmail','mailer')));
        $form->setData('admin_email',     stripslashes($HfnuConfig->getValue('admin_email','main')));
        $form->setData('posts_per_page',  (int) $HfnuConfig->getValue('posts_per_page','messages'));
        $form->setData('replies_per_page',(int) $HfnuConfig->getValue('replies_per_page','messages'));
        $form->setData('members_per_page',(int) $HfnuConfig->getValue('members_per_page','messages'));
		$form->setData('avatar_max_width',(int) $HfnuConfig->getValue('avatar_max_width','main'));
		$form->setData('avatar_max_height',(int) $HfnuConfig->getValue('avatar_max_height','main'));
        $form->setData('stats_nb_of_lastpost',(int) $HfnuConfig->getValue('stats_nb_of_lastpost','messages'));
        $form->setData('elapsed_time_after_posting_before_editing',(int) $floodConfig->getValue('elapsed_time_after_posting_before_editing'));
        $form->setData('elapsed_time_between_two_post_by_same_ip',(int) $floodConfig->getValue('elapsed_time_between_two_post_by_same_ip'));

        $form->setData('timeout_connected',(int) $timeoutConfig->getValue('timeout_connected'));
        $form->setData('timeout_visit',(int) $timeoutConfig->getValue('timeout_visit'));
		
        $form->setData('post_max_size',(int) $HfnuConfig->getValue('post_max_size','messages'));

		$form->setData('social_network_twitter', 	$socialNetwork->getValue('twitter'));
		$form->setData('social_network_digg', 		$socialNetwork->getValue('digg'));
		$form->setData('social_network_delicious', 	$socialNetwork->getValue('delicious'));
		$form->setData('social_network_facebook', 	$socialNetwork->getValue('facebook'));
		$form->setData('social_network_reddit', 	$socialNetwork->getValue('reddit'));
		$form->setData('social_network_netvibes', 	$socialNetwork->getValue('netvibes'));		

                              
        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $rep->body->assign('MAIN',$tpl->fetch('config'));
		$rep->body->assign('selectedMenuItem','config');
        return $rep;
    }
   
    public function check_upgrade() {
        
        global $HfnuConfig;
        $url = $HfnuConfig->getValue('url_check_version','main');

        if (!ini_get('allow_url_fopen'))
            jMessage::add('Impossible de vérifier les mises à jour tant que \'allow_url_fopen\' est désactivé sur ce système.');
        else {   
            $fp = @fopen($url, 'r');
            $latestVersion = trim(@fread($fp, 16));
            @fclose($fp);
        
            if ($latestVersion == '')
                jMessage::add('La vérification de mise à jour a échouée pour une raison inconnue.');
            else {  
                $curVersion = str_replace(array('.', 'dev', 'beta', ' '), '', strtolower($HfnuConfig->getValue('version','main')));
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