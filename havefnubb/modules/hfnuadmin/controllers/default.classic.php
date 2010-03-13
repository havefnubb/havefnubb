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
		'*' => array('auth.required'=>true,
					'hfnu.check.installed'=>true,
					'banuser.check'=>true,
		),
		'index' => array( 'jacl2.right'=>'hfnu.admin.index'),
		'config'=> array( 'jacl2.right'=>'hfnu.admin.config'),
	);

	function index() {
		$rep = $this->getResponse('redirect');
		$rep->action = 'master_admin~default:index';
		return $rep;
	}

	function config() {
		global $gJConfig;

		$rep = $this->getResponse('html');
		$submit = $this->param('validate');

		if ($submit == jLocale::get('hfnuadmin~config.saveBt') ) {

			$floodConfig 	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnubb/flood.coord.ini.php');
			$timeoutConfig 	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnubb/timeout.coord.ini.php');

			$form = jForms::fill('hfnuadmin~config');

			if (!$form->check()) {
				$rep = $this->getResponse('redirect');
				$rep->action='havefnubb~default:index';
				return $rep;
			}
			$defaultConfig =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');

			$defaultConfig->setValue('title',htmlentities($this->param('title')),'havefnubb');
			$defaultConfig->setValue('description',htmlentities($this->param('description')),'havefnubb');

			$defaultConfig->setValue('webmasterEmail',htmlentities($this->param('webmaster_email')),'mailer');

			$defaultConfig->setValue('rules',str_replace('"','',$this->param('rules')),'havefnubb');
			$defaultConfig->setValue('admin_email',htmlentities($this->param('admin_email')),'havefnubb');
			$defaultConfig->setValue('posts_per_page',htmlentities($this->param('posts_per_page')),'havefnubb');
			$defaultConfig->setValue('replies_per_page',htmlentities($this->param('replies_per_page')),'havefnubb');
			$defaultConfig->setValue('members_per_page',htmlentities($this->param('members_per_page')),'havefnubb');
			$defaultConfig->setValue('stats_nb_of_lastpost',htmlentities($this->param('stats_nb_of_lastpost')),'havefnubb');
			$defaultConfig->setValue('post_max_size',htmlentities($this->param('post_max_size')),'havefnubb');
			$defaultConfig->setValue('avatar_max_width',htmlentities($this->param('avatar_max_width')),'havefnubb');
			$defaultConfig->setValue('avatar_max_height',htmlentities($this->param('avatar_max_height')),'havefnubb');
			$defaultConfig->setValue('important_nb_replies',(int) $this->param('important_nb_replies'),'havefnubb');
			$defaultConfig->setValue('important_nb_views',(int) $this->param('important_nb_views'),'havefnubb');

			$defaultConfig->setValue('twitter',(int) $this->param('social_network_twitter'),'social_networks');
			$defaultConfig->setValue('digg',(int) $this->param('social_network_digg'),'social_networks');
			$defaultConfig->setValue('delicious',(int) $this->param('social_network_delicious'),'social_networks');
			$defaultConfig->setValue('facebook',(int) $this->param('social_network_facebook'),'social_networks');
			$defaultConfig->setValue('reddit',(int) $this->param('social_network_reddit'),'social_networks');
			$defaultConfig->setValue('netvibes',(int) $this->param('social_network_netvibes'),'social_networks');
			$defaultConfig->save();


			$floodConfig->setValue('elapsed_time_after_posting_before_editing',
								  htmlentities($this->param('elapsed_time_after_posting_before_editing')));
			$floodConfig->setValue('elapsed_time_between_two_post_by_same_ip',
								  htmlentities($this->param('elapsed_time_between_two_post_by_same_ip')));
			$floodConfig->save();

			$timeoutConfig->setValue('timeout_connected',(int) htmlentities($this->param('timeout_connected')));
			$timeoutConfig->setValue('timeout_visit',(int) htmlentities($this->param('timeout_visit')));
			$timeoutConfig->save();

			jForms::destroy('hfnuadmin~config');
			jMessage::add(jLocale::get('hfnuadmin~config.config.modified'),'ok');
			$rep->action ='hfnuadmin~default:config';
			return $rep;
		}
		else {
			$form = jForms::create('hfnuadmin~config');
			$floodConfig = parse_ini_file(JELIX_APP_CONFIG_PATH.'havefnubb/flood.coord.ini.php');
			$timeoutConfig 	=  parse_ini_file(JELIX_APP_CONFIG_PATH.'havefnubb/timeout.coord.ini.php');

			$form->setData('title',           stripslashes($gJConfig->havefnubb['title']));
			$form->setData('description',     stripslashes($gJConfig->havefnubb['description']));
			$form->setData('rules',           stripslashes($gJConfig->havefnubb['rules']));
			$form->setData('webmaster_email', stripslashes($gJConfig->mailer['webmasterEmail']));
			$form->setData('admin_email',     stripslashes($gJConfig->havefnubb['admin_email']));
			$form->setData('posts_per_page',  (int) $gJConfig->havefnubb['posts_per_page']);
			$form->setData('replies_per_page',(int) $gJConfig->havefnubb['replies_per_page']);
			$form->setData('members_per_page',(int) $gJConfig->havefnubb['members_per_page']);
			$form->setData('avatar_max_width',(int) $gJConfig->havefnubb['avatar_max_width']);
			$form->setData('avatar_max_height',(int) $gJConfig->havefnubb['avatar_max_height']);
			$form->setData('stats_nb_of_lastpost',(int) $gJConfig->havefnubb['stats_nb_of_lastpost']);
			$form->setData('elapsed_time_after_posting_before_editing',(int) $floodConfig['elapsed_time_after_posting_before_editing']);
			$form->setData('elapsed_time_between_two_post_by_same_ip',(int) $floodConfig['elapsed_time_between_two_post_by_same_ip']);

			$form->setData('important_nb_replies',(int) $gJConfig->havefnubb['important_nb_replies']);
			$form->setData('important_nb_views',(int) $gJConfig->havefnubb['important_nb_views']);


			$form->setData('timeout_connected',(int) $timeoutConfig['timeout_connected']);
			$form->setData('timeout_visit',(int) $timeoutConfig['timeout_visit']);

			$form->setData('post_max_size',(int) $gJConfig->havefnubb['post_max_size']);

			$form->setData('social_network_twitter', $gJConfig->social_networks['twitter']);
			$form->setData('social_network_digg', $gJConfig->social_networks['digg']);
			$form->setData('social_network_delicious', $gJConfig->social_networks['delicious']);
			$form->setData('social_network_facebook', $gJConfig->social_networks['facebook']);
			$form->setData('social_network_reddit', $gJConfig->social_networks['reddit']);
			$form->setData('social_network_netvibes', $gJConfig->social_networks['netvibes']);

			$tpl = new jTpl();
			$tpl->assign('form', $form);
			$rep->body->assign('MAIN',$tpl->fetch('config'));
			$rep->body->assign('selectedMenuItem','config');
			return $rep;
		}
	}
}
