<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008 FoxMaSk, 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * this controller manages the configuration of the forum
 */
class defaultCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */
	public $pluginParams = array(
		'*' => array('auth.required'=>true,
					'hfnu.check.installed'=>true,
					'banuser.check'=>true,
		),
		'index' => array( 'jacl2.right'=>'hfnu.admin.index'),
		'config'=> array( 'jacl2.right'=>'hfnu.admin.config'),
		//'loadconfig'=> array( 'jacl2.right'=>'hfnu.admin.config'),
		'saveconfig'=> array( 'jacl2.right'=>'hfnu.admin.config'),
	);

	function index() {
		$rep = $this->getResponse('redirect');
		$rep->action = 'master_admin~default:index';
		return $rep;
	}

    protected function initform($form) {
        global $gJConfig;
        $floodConfig = parse_ini_file(JELIX_APP_CONFIG_PATH.'havefnubb/flood.coord.ini.php');
		$timeoutConfig 	=  parse_ini_file(JELIX_APP_CONFIG_PATH.'havefnubb/timeout.coord.ini.php');

        $tzId = DateTimeZone::listIdentifiers();
        for ($i = 0 ; $i < count($tzId) ; $i++) {
            if ($gJConfig->timeZone == $tzId[$i])
                $selectedTimeZone = $i;
        }

        $form->setData('timezone'   ,$selectedTimeZone);
        $form->setData('title',           stripslashes($gJConfig->havefnubb['title']));
        $form->setData('description',     stripslashes($gJConfig->havefnubb['description']));
        $form->setData('rules',           stripslashes($gJConfig->havefnubb['rules']));
        $form->setData('webmaster_email', stripslashes($gJConfig->mailer['webmasterEmail']));
        $form->setData('admin_email',     stripslashes($gJConfig->havefnubb['admin_email']));
        $form->setData('posts_per_page',        (int) $gJConfig->havefnubb['posts_per_page']);
        $form->setData('replies_per_page',      (int) $gJConfig->havefnubb['replies_per_page']);
        $form->setData('members_per_page',      (int) $gJConfig->havefnubb['members_per_page']);
        $form->setData('avatar_max_width',      (int) $gJConfig->havefnubb['avatar_max_width']);
        $form->setData('avatar_max_height',     (int) $gJConfig->havefnubb['avatar_max_height']);
        $form->setData('stats_nb_of_lastpost',  (int) $gJConfig->havefnubb['stats_nb_of_lastpost']);
        $form->setData('only_same_ip',          (int) (isset($floodConfig['only_same_ip'])?$floodConfig['only_same_ip']:'on'));
        $form->setData('elapsed_time_between_two_post',(int) (isset($floodConfig['elapsed_time_between_two_post'])?$floodConfig['elapsed_time_between_two_post']:'0'));

        $form->setData('important_nb_replies',  (int) $gJConfig->havefnubb['important_nb_replies']);
        $form->setData('important_nb_views',    (int) $gJConfig->havefnubb['important_nb_views']);


        $form->setData('timeout_connected', (int) $timeoutConfig['timeout_connected']);
        $form->setData('timeout_visit',     (int) $timeoutConfig['timeout_visit']);

        $form->setData('post_max_size',     (int) $gJConfig->havefnubb['post_max_size']);

        $form->setData('social_network_twitter',    $gJConfig->social_networks['twitter']);
        $form->setData('social_network_digg',       $gJConfig->social_networks['digg']);
        $form->setData('social_network_delicious',  $gJConfig->social_networks['delicious']);
        $form->setData('social_network_facebook',   $gJConfig->social_networks['facebook']);
        $form->setData('social_network_reddit',     $gJConfig->social_networks['reddit']);
        $form->setData('social_network_netvibes',   $gJConfig->social_networks['netvibes']);
    }

    /*function loadconfig() {
        $resp = $this->getResponse('redirect');
        $resp->action ='hfnuadmin~default:config';
        $form = jForms::create('hfnuadmin~config');
        $this->initform($form);
        return $resp;
    }*/

	function config() {
		global $gJConfig;

		$resp = $this->getResponse('html');

        $form =  jForms::get('hfnuadmin~config');
        if (!$form) {
            $form = jForms::create('hfnuadmin~config');
            $this->initform($form);
        }

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $resp->body->assign('MAIN',$tpl->fetch('config'));
        $resp->body->assign('selectedMenuItem','config');
        return $resp;
	}


    function saveconfig() {
		global $gJConfig;

		$resp = $this->getResponse('redirect');
        $resp->action ='hfnuadmin~default:config';

        $form = jForms::fill('hfnuadmin~config');
        if (!$form->check()) {
            return $resp;
        }

        $defaultConfig =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');

        $defaultConfig->setValue('title',          htmlentities($this->param('title')),'havefnubb');
        $defaultConfig->setValue('description',    htmlentities($form->getData('description')),'havefnubb');

        $defaultConfig->setValue('webmasterEmail',    $this->param('webmaster_email'),'mailer');

        $defaultConfig->setValue('rules',               str_replace('"','',$form->getData('rules')),'havefnubb');
        $defaultConfig->setValue('admin_email',         $form->getData('admin_email'),'havefnubb');
        $defaultConfig->setValue('posts_per_page',      $form->getData('posts_per_page'),'havefnubb');
        $defaultConfig->setValue('replies_per_page',    $form->getData('replies_per_page'),'havefnubb');
        $defaultConfig->setValue('members_per_page',    $form->getData('members_per_page'),'havefnubb');
        $defaultConfig->setValue('stats_nb_of_lastpost',$form->getData('stats_nb_of_lastpost'),'havefnubb');
        $defaultConfig->setValue('post_max_size',       $form->getData('post_max_size'),'havefnubb');
        $defaultConfig->setValue('avatar_max_width',    $form->getData('avatar_max_width'),'havefnubb');
        $defaultConfig->setValue('avatar_max_height',   $form->getData('avatar_max_height'),'havefnubb');
        $defaultConfig->setValue('important_nb_replies',$form->getData('important_nb_replies'),'havefnubb');
        $defaultConfig->setValue('important_nb_views',  $form->getData('important_nb_views'),'havefnubb');

        $defaultConfig->setValue('twitter',     $form->getData('social_network_twitter'),'social_networks');
        $defaultConfig->setValue('digg',        $form->getData('social_network_digg'),'social_networks');
        $defaultConfig->setValue('delicious',   $form->getData('social_network_delicious'),'social_networks');
        $defaultConfig->setValue('facebook',    $form->getData('social_network_facebook'),'social_networks');
        $defaultConfig->setValue('reddit',      $form->getData('social_network_reddit'),'social_networks');
        $defaultConfig->setValue('netvibes',    $form->getData('social_network_netvibes'),'social_networks');

        $tz = DateTimeZone::listIdentifiers();

        $defaultConfig->setValue('timeZone',    $tz[$form->getData('timezone')]);
        $defaultConfig->save();

        $floodConfig 	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnubb/flood.coord.ini.php');

        $floodConfig->setValue('only_same_ip',                  $form->getData('only_same_ip'));
        $floodConfig->setValue('elapsed_time_between_two_post', $form->getData('elapsed_time_between_two_post'));
        $floodConfig->save();

        $timeoutConfig 	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnubb/timeout.coord.ini.php');
        $timeoutConfig->setValue('timeout_connected',  $form->getData('timeout_connected'));
        $timeoutConfig->setValue('timeout_visit',      $form->getData('timeout_visit'));
        $timeoutConfig->save();

        jForms::destroy('hfnuadmin~config');
        jMessage::add(jLocale::get('hfnuadmin~config.config.modified'),'ok');

        return $resp;
    }
}
