<?php
/**
* @package   havefnubb
* @subpackage hfnuadmin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * This controller manages the Category of the forum
 */
class categoryCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */
	public $pluginParams = array(
		'*' => array('auth.required'=>true,
					'banuser.check'=>true,
                    'jacl2.right'=>'hfnu.admin.category'
		  ),
		'delete'=> array( 'jacl2.right'=>'hfnu.admin.category.delete'),
	);

	function index() {
		$tpl = new jTpl();

		$form = jForms::create('hfnuadmin~category');

		$dao = jDao::get('havefnubb~category');
		$categories = $dao->findAll();

		//initializing of the Token
		$token = jClasses::getService("havefnubb~hfnutoken");
		$token->setHfnuToken();

		$tpl->assign('hfnutoken',$token->getHfnuToken());
		$tpl->assign('form', $form);
		$tpl->assign('categories',$categories);

		$rep = $this->getResponse('html');
		$rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~category_index'));
		$rep->body->assign('selectedMenuItem','category');
		return $rep;

	}

	function savecreate () {
		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~category:index';

		if ($this->param('validate') == jLocale::get('hfnuadmin~category.saveBt')) {

			$dao = jDao::get('havefnubb~category');

			$form = jForms::fill('hfnuadmin~category');
			if (!$form) {
				jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
				return $rep;
			}

			if (!$form->check()) {
				jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
				return $rep;
			}

			$record = jDao::createRecord('havefnubb~category');
			$record->cat_name = $form->getData('cat_name');
			$record->cat_order = $form->getData('cat_order');

			$dao->insert($record);

			jForms::destroy('hfnuadmin~category');

			jMessage::add(jLocale::get('hfnuadmin~category.category.added'),'ok');
		}

		return $rep;

	}

	function saveedit () {
		$id_cat     = $this->param('id_cat');
		$cat_name   = $this->param('cat_name');
		$cat_order  = $this->param('cat_order');
		$hfnutoken  = (string) $this->param('hfnutoken');

		//let's check if we have a valid token in our form
		$token = jClasses::getService("havefnubb~hfnutoken");
		$token->checkHfnuToken($hfnutoken);

		if ($this->param('saveBt')== jLocale::get('hfnuadmin~category.saveBt')) {

			if (count($id_cat) == 0) {
				jMessage::add(jLocale::get('hfnuadmin~category.unknown.category'),'error');
				$rep = $this->getResponse('redirect');
				$rep->action='hfnuadmin~category:index';
				return $rep;
			}

			$dao = jDao::get('havefnubb~category');

			foreach ($id_cat as $thisId) {
				$record 			= $dao->get( (int) $id_cat[$thisId]);
				$record->cat_name 	= (string) $cat_name[$id_cat[$thisId]];
				$record->cat_order 	= (int) $cat_order[$id_cat[$thisId]];
				$dao->update($record);
			}
			jForms::destroy('hfnuadmin~category');
			jMessage::add(jLocale::get('hfnuadmin~category.category.modified'),'ok');
		}
		else {
			jForms::destroy('hfnuadmin~category');
			jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
		}

		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~category:index';
		return $rep;
	}

	function delete() {
		$id_cat = (integer) $this->param('id_cat');
		if ($id_cat == 0) {
			jMessage::add(jLocale::get('hfnuadmin~category.invalid.datas'),'error');
		} else {
			$rec = jDao::get('havefnubb~forum_cat')->countByIdCat($id_cat);
			if ($rec == 0) {
				$dao = jDao::get('havefnubb~category');
				$dao->delete($id_cat);
				jMessage::add(jLocale::get('hfnuadmin~category.category.deleted'),'ok');
			}
			else {
				jMessage::add(jLocale::get('hfnuadmin~category.category.cant.be.deleted',$rec),'error');
			}
		}

		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~category:index';
		return $rep;
	}

}
