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
 * This controller manages the creation/edit/deletion of the forum
 * and this rights associated to them
 */
class forumCtrl extends jController {
	/**
	 * @var plugins to manage the behavior of the controller
	 */
	public $pluginParams = array(
		'*' => array('auth.required'=>true,
			'banuser.check'=>true,
            'jacl2.right'=>'hfnu.admin.forum'
		),
		'delete'=> array( 'jacl2.right'=>'hfnu.admin.forum.delete'),
	);


	function index() {
	//get list of cagetory in which we can create a forum
		$form = jForms::create('hfnuadmin~category_list');
		$tpl = new jTpl();
		$rep = $this->getResponse('html');

		$tpl->assign('form',$form);

		$rep->body->assign('MAIN', $tpl->fetch('hfnuadmin~forum_index'));
		$rep->body->assign('selectedMenuItem','forum');
		return $rep;
	}

	// creation of Forum
	function create () {

		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~forum:index';

		// let's define the possible actions we can do :
		// where to add a forum :
		// 1) in a category
		// 2) before a given forum
		// 3) after a given forum
		// 4) a sub-forum
		$possibleActions = array('in_cat','before','after','childof');

		// the choice is ?
		$choice = (string) $this->param('forum');
		// build the next param to check
		$put = 'put_'.$choice;
		// the id_forum is ?
		$id_forum = (int) $this->param($put);

		//check if submitted data are ok.
		if ($id_forum == 0 or ! in_array($choice,$possibleActions) ) {
			jMessage::add(jLocale::get('hfnuadmin~forum.invalid.datas'),'error');
			return $rep;
		}

		if ($this->param('validate') == jLocale::get('hfnuadmin~forum.createBt')) {

			$dao = jDao::get('havefnubb~forum');

			$forum = $dao->get($id_forum);

			$parent_id      = 0;
			$child_level    = 0;
			$forum_order    = 0;
			$id_cat         = 0;
			// now check what choice has been done :
			switch ($choice) {
				// my parent is the selected forum so i add one level to child_level
				case 'childof' :
							$parent_id      = $forum->id_forum;
							$child_level    = $forum->child_level +1;
							$forum_order    = 0;
							$id_cat         = $forum->id_cat;
							break;
				// we have the same parent and same child_level
				case 'before' :  $parent_id      = $forum->parent_id;
							$child_level    = $forum->child_level;
							$forum_order    = ($forum_order -1 < 0) ? 0 : $forum_order -1;
							$id_cat         = $forum->id_cat;
							break;
				case 'after' :
							$parent_id      = $forum->parent_id;
							$child_level    = $forum->child_level;
							$forum_order    = $forum->forum_order +1;
							$id_cat         = $forum->id_cat;
							break;
				case 'in_cat' :
							$parent_id = 0;
							$child_level = 0;
							$forum_order = 0;
	/***********************************************************************************
	/!\ id_forum contains the id cat when we choose to add a forum to a category ! /!\
	***********************************************************************************/
							$id_cat = $id_forum;
							break;
			}

			$record = jDao::createRecord('havefnubb~forum');
			$record->forum_name = jLocale::get('hfnuadmin~forum.new.forum');
			$record->id_cat     = $id_cat;
			$record->parent_id  = $parent_id;
			$record->child_level = $child_level;
			$record->forum_order = $forum_order;
			$record->post_expire = 0;
			$record->forum_type = (int) $this->param('forum_type');
			$record->forum_desc = jLocale::get('hfnuadmin~forum.new.forum');

			$dao->insert($record);

			// create default rights with the lastInsertId of the forum we've just created
			jClasses::getService("hfnuadmin~hfnuadminrights")->resetRights($record->id_forum);

			jMessage::add(jLocale::get('hfnuadmin~forum.forum.added'),'ok');
			return $rep;
		}
		else {
			return $rep;
		}
	}

	function edit () {
		$id_forum = (int) $this->param('id_forum');

		if ($id_forum == 0 ) {
			jMessage::add(jLocale::get('hfnuadmin~forum.invalid.datas'),'error');
			$rep = $this->getResponse('redirect');
			$rep->action='hfnuadmin~forum:index';
			return $rep;
		}
		$dao = jDao::get('havefnubb~forum');
		$forum = $dao->get($id_forum);

		$form = jForms::create('hfnuadmin~forum_edit',$id_forum);
		$form->initFromDao("havefnubb~forum");

		$rep = $this->getResponse('html');
		$rep->body->assign('selectedMenuItem','forum');
		$tpl = new jTpl();

		$gid=array(0);
		$o = new StdClass;
		$o->id_aclgrp ='0';
		$o->name = jLocale::get('jacl2db_admin~acl2.anonymous.group.name');
		$o->grouptype=0;
		$groups=array($o);
		$grouprights=array(0=>false);

		$dao = jDao::get('jelix~jacl2group',jAcl2Db::getProfile())->findAllPublicGroup();

		foreach($dao as $grp) {
			$gid[]=$grp->id_aclgrp;
			$groups[]=$grp;
			$grouprights[$grp->id_aclgrp]=false;
		}
		$rights=array();
		$p = jAcl2Db::getProfile();

		$rs = jDao::get('jelix~jacl2subject',$p)->findHfnuSubject();
		foreach($rs as $rec){
			$rights[$rec->id_aclsbj] = $grouprights;
		}

		$rs = jDao::get('jelix~jacl2rights',$p)->getHfnuRightsByGroups($gid,'forum'.$id_forum);
		foreach($rs as $rec){
			$rights[$rec->id_aclsbj][$rec->id_aclgrp] = true;
		}

		$tpl->assign('forum',$forum);
		$tpl->assign('form',$form);
		$tpl->assign(compact('groups', 'rights'));
		$rep->body->assign('MAIN',$tpl->fetch('forum_edit'));
		return $rep;
	}

	function saveedit () {
		$id_forum = (int) $this->param('id_forum');

		$submit = $this->param('validate');

		if ($submit == jLocale::get('hfnuadmin~forum.saveBt') ) {
			$form = jForms::fill('hfnuadmin~forum_edit',$id_forum);
			if (!$form->check()) {
				jMessage::add(jLocale::get('hfnuadmin~forum.unknown.forum'),'error');
				$rep = $this->getResponse('redirect');
				$rep->action='hfnuadmin~forum:edit';
				$rep->params = array('id_forum'=>$id_forum);
				return $rep;
			}
			$form->saveToDao('havefnubb~forum');
		}

		$submitRight = $this->param('validateright');
		if ($submitRight == jLocale::get('hfnuadmin~forum.saveBt') ) {
			$rights = $this->param('rights',array());
			foreach(jAcl2DbUserGroup::getGroupList() as $grp) {
				$id = intval($grp->id_aclgrp);
				jClasses::getService("hfnuadmin~hfnuadminrights")->setRightsOnForum($id, (isset($rights[$id])?$rights[$id]:array()),'forum'.$id_forum);
			}

			jClasses::getService("hfnuadmin~hfnuadminrights")->setRightsOnForum(0, (isset($rights[0])?$rights[0]:array()),'forum'.$id_forum);
		}

		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~forum:index';
		return $rep;
	}

	function delete() {
		$id_forum = (integer) $this->param('id_forum');

		$dao = jDao::get('havefnubb~forum');
		$dao->delete($id_forum);

		jMessage::add(jLocale::get('hfnuadmin~forum.forum.deleted'),'ok');

		$rep = $this->getResponse('redirect');
		$rep->action='hfnuadmin~forum:index';
		return $rep;
	}

	function defaultrights() {
		$id_forum = (int) $this->param('id_forum');
		if ($id_forum == 0) {
			jMessage::add(jLocale::get('hfnuadmin~forum.unknown.forum'),'error');
			$rep = $this->getResponse('redirect');
			$rep->action='hfnuadmin~forum:index';
			return $rep;
		}

		jClasses::getService("hfnuadmin~hfnuadminrights")->resetRights($id_forum);

		jMessage::add(jLocale::get('hfnuadmin~forum.forum.rights.restored'),'ok');
		$rep = $this->getResponse('redirect');
		$rep->params = array('id_forum'=>$id_forum);
		$rep->action='hfnuadmin~forum:edit';
		return $rep;
	}
}
