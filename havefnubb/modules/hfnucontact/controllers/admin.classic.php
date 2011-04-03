<?php
/**
* @package   havefnubb
* @subpackage hfnucontact
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Controller to manage the default contact email of the website
*/
class adminCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */
	public $pluginParams = array(
		'*' => array('auth.required'=>true,
				'banuser.check'=>true,
				'hfnu.admin.contact'=>true,
				),
	);
	/**
	 * Main page
	 */
	public function index() {
		global $gJConfig;
		$submit = $this->param('validate');

		if ($submit == jLocale::get('hfnucontact~contact.form.saveBt') ) {

			$form = jForms::fill('hfnucontact~admincontact');
			$rep = $this->getResponse('redirect');

			if (!$form->check()) {
				$rep->action='hfnucontact~admin:index';
				return $rep;
			}
			$HfnucontactConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'defaultconfig.ini.php');
			$HfnucontactConfig->setValue('email_contact',$this->param('contact'),'hfnucontact');
			$HfnucontactConfig->save();
			jMessage::add(jLocale::get('hfnucontact~contact.admin.form.email.saved'),'ok');
			jForms::destroy('hfnucontact~admincontact');

			$rep->action ='hfnucontact~admin:index';
			return $rep;
		}
		else
			$form = jForms::create('hfnucontact~admincontact');

		$form->setData('contact',$gJConfig->hfnucontact['email_contact']);

		$rep = $this->getResponse('html');
		$tpl = new jTpl();
		$tpl->assign('form',$form);
		$rep->body->assign('MAIN',$tpl->fetch('hfnucontact~admincontact'));
		$rep->body->assign('selectedMenuItem','contact');
		return $rep;

	}
}
