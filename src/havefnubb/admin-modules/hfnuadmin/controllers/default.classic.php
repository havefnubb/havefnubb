<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2010 Laurent Jouanneau
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

use Jelix\IniFile\IniModifier;

/**
 * This controller manages the configuration of the forum
 */
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*' => array('auth.required'=>true,
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
        $gJConfig = jApp::config();
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
        $form->setData('anonymous_post_authorized',(int)$gJConfig->havefnubb['anonymous_post_authorized']);
        $form->setData('only_same_ip',          (int) (isset($gJConfig->flood['only_same_ip'])?$gJConfig->flood['only_same_ip']:'on'));
        $form->setData('elapsed_time_between_two_post',(int) (isset($gJConfig->flood['elapsed_time_between_two_post'])?$gJConfig->flood['elapsed_time_between_two_post']:'0'));

        $form->setData('important_nb_replies',  (int) $gJConfig->havefnubb['important_nb_replies']);
        $form->setData('important_nb_views',    (int) $gJConfig->havefnubb['important_nb_views']);

        $form->setData('post_max_size',     (int) $gJConfig->havefnubb['post_max_size']);

    }

    /*function loadconfig() {
        $resp = $this->getResponse('redirect');
        $resp->action ='hfnuadmin~default:config';
        $form = jForms::create('hfnuadmin~config');
        $this->initform($form);
        return $resp;
    }*/

    function config() {

        $resp = $this->getResponse('html');

        $form =  jForms::get('hfnuadmin~config');
        if (!$form) {
            $form = jForms::create('hfnuadmin~config');
            $this->initform($form);
        }

        $tpl = new jTpl();
        $tpl->assign('form', $form);
        $forumUrl =
                    '<a href="'
                    .jUrl::get('hfnuadmin~forum:index')
                    .'" >'
                    .jLocale::get('config.anonymous_post_authorized.rights.management.by.forum')
                    .'</a>';
        $tpl->assign('forumUrl',$forumUrl);
        $resp->body->assign('MAIN',$tpl->fetch('config'));
        $resp->body->assign('selectedMenuItem','config');
        return $resp;
    }


    function saveconfig() {

        $resp = $this->getResponse('redirect');
        $resp->action ='hfnuadmin~default:config';

        $form = jForms::fill('hfnuadmin~config');
        if (!$form->check()) {
            return $resp;
        }

        $defaultConfig =  new IniModifier(jApp::varConfigPath('liveconfig.ini.php'));

        //if we want to allow the anonymous users on the forum :
        if ($form->getData('anonymous_post_authorized')) {
            $rights =array( 'hfnu.forum.list'=>'on',
                            'hfnu.forum.view'=>'on',
                            'hfnu.posts.list'=>'on',
                            'hfnu.posts.view'=>'on',
                            'hfnu.posts.rss'=>'on',
                            'hfnu.posts.reply'=>'on',
                            'hfnu.posts.create'=>'on',
                            'hfnu.search'=>'on');

            jAcl2DbManager::setRightsOnGroup('__anonymous', $rights);
        }
        // we disable the anonymous access on the forum
        else
            jAcl2DbManager::setRightsOnGroup('__anonymous', array());

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
        $defaultConfig->setValue('anonymous_post_authorized',  $form->getData('anonymous_post_authorized'),'havefnubb');

        $tz = DateTimeZone::listIdentifiers();

        $defaultConfig->setValue('timeZone',    $tz[$form->getData('timezone')]);

        $defaultConfig->setValue('only_same_ip',                  $form->getData('only_same_ip'), 'flood');
        $defaultConfig->setValue('elapsed_time_between_two_post', $form->getData('elapsed_time_between_two_post'), 'flood');

        $defaultConfig->save();

        jForms::destroy('hfnuadmin~config');
        jMessage::add(jLocale::get('hfnuadmin~config.config.modified'),'ok');

        return $resp;
    }
}
