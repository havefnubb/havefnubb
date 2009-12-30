<?php
/**
* @package   havefnubb
* @subpackage migrate
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

// Migration controller
class migrateCtrl extends jController {
	public $pluginParams = array(
		'*' =>array('auth.required'=>false,
			'hfnu.timeout.do.not.check'=>true)
	);

	function phorum() {
		global $gJConfig;

		$cnx = jDb::getConnection();

		// Let's Migrate the config
		$data = $cnx->query("SELECT * FROM ".$cnx->prefixTable('phorum_settings') .
						  " WHERE name = 'html_title' or name = 'system_email_from_address'" );
		$mainConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH . 'defaultconfig.ini.php');
		$nbSettings = 0;
		foreach ($data as $set) {
			if ($set->name == 'html_title') {
				$mainConfig->setValue('title',htmlentities($set->data),'havefnubb');
				$nbSettings++;
			}
			if ($set->name == 'system_email_from_address') {
				$mainConfig->setValue('webmasterEmail',$set->data,'mailer');
				$nbSettings++;
			}
		}

		$mainConfig->save();

		// Let's Migrate the members
		$data = $cnx->query('SELECT * FROM '.$cnx->prefixTable('phorum_users') );
		$nbUsers = $data->rowCount();

		$dao = jDao::get('havefnubb~member');
		foreach ($data as $member) {

			$insertSQL = 'INSERT INTO '.$cnx->prefixTable('member') . ' ( ' .
						'id_user,
						member_login,
						member_password,
						member_email,
						member_nickname,
						member_status,
						member_last_connect,
						member_created,
						member_comment,
						member_nb_msg,
						member_show_email)
						VALUES (
							"'. $member->user_id .'",
							"'. addslashes($member->username).'",
							"'.$member->password.'",
							"'.$member->email.'",
							"'.$member->display_name.'",
							"'.$member->active.'",
							"'.$member->date_last_active.'",
							"'.date('Y-m-d h:i:s',$member->date_added).'",
							"'.addslashes($member->signature).'",
							"'.$member->posts.'",
							"0"
						)';
			$cnx->exec($insertSQL);

			$p = jAcl2Db::getProfile();
			$daousergroup = jDao::get('jelix~jacl2usergroup',$p);
			$daogroup = jDao::get('jelix~jacl2group',$p);
			$usergrp = jDao::createRecord('jelix~jacl2usergroup',$p);
			$usergrp->login =$member->username;

			// si $defaultGroup -> assign le user aux groupes par defaut
			if($defaultGroup){
				$defgrp = $daogroup->getDefaultGroups();
				foreach($defgrp as $group){
					$usergrp->id_aclgrp = $group->id_aclgrp;
					$daousergroup->insert($usergrp);
				}
			}

			// creation d'un groupe personnel
			$persgrp = jDao::createRecord('jelix~jacl2group',$p);
			$persgrp->name = $member->username;
			$persgrp->grouptype = 2;
			$persgrp->ownerlogin = $member->username;

			$daogroup->insert($persgrp);
			$usergrp->id_aclgrp = $persgrp->id_aclgrp;
			$daousergroup->insert($usergrp);

			jAcl2DbUserGroup::addUserToGroup($member->username,2);

			//if this user an admin ?
			if ($member->admin == 1) {
				jAcl2DbUserGroup::addUserToGroup($member->username,1);
			}

		}

		// Let's Migrate the forums
		$data = $cnx->query('SELECT * FROM '.$cnx->prefixTable('phorum_forums') );
		$nbForums = $data->rowCount();
		foreach ($data as $forum) {

			if ($forum->parent_id == 0)
			   $childLevel = 0;
			else
				$childLevel = 1;

			$insertSQL = 'INSERT INTO '.$cnx->prefixTable('forum') . ' ( ' .
									" id_forum, " .
									" forum_name, " .
									" id_cat, " .
									" forum_desc, " .
									" forum_order ," .
									" parent_id," .
									" child_level, " .
									" forum_type, ".
									" forum_url, ".
									" post_expire ".
									" ) " .
									" VALUES ( ".
									"'".$forum->forum_id."',".
									"'".addslashes($forum->name)."',".
									"'1',".
									"'".addslashes($forum->description)."',".
									"'".$forum->display_order."',".
									"'".$forum->parent_id."',".
									"'".$childLevel."',".
									"'0',".
									"'',".
									"'0'".
									" ) " ;
			$cnx->exec($insertSQL);
			// set default rights to the forum;
			jClasses::getService('hfnuadmin~hfnuadminrights')->resetRights($forum->forum_id);
		}

		$daoUser = jDao::get('havefnubb~member');
		// Let's Migrate the posts
		$data = $cnx->query('SELECT * FROM '.$cnx->prefixTable('phorum_messages') );
		$nbPosts = $data->rowCount();
		foreach ($data as $posts) {

			if ($posts->parent_id == 0 )
				$parent_id = $posts->message_id;
			else
				$parent_id =  $posts->parent_id;

			switch ($posts->status) {
					case 2 : $status = 'opened'; break;
					case -1 : $status = 'pined'; break;
					case -2 : $status = 'hidden'; break;
			}

			$insertSQL = 'INSERT INTO '.$cnx->prefixTable('posts') . ' ( ' .
									" id_post, " .
									" id_user, " .
									" id_forum, " .
									" parent_id," .
									" status,".
									" subject,".
									" message,".
									" date_created, " .
									" date_modified, ".
									" viewed, ".
									" poster_ip, ".
									" censored_msg, ".
									" read_by_mod ".
									" ) " .
									" VALUES ( ".
									"'".$posts->message_id."','".
									$posts->user_id."','".
									$posts->forum_id."','".
									$parent_id."','".
									$status."','".
									addslashes($posts->subject)."','".
									addslashes($posts->body)."','".
									$posts->datestamp."','".
									$posts->modifystamp."','".
									$posts->viewcount."','".
									$posts->ip."',".
									"'',".
									"'1'".
									" ) " ;
			$cnx->exec($insertSQL);
		}

		$rep = $this->getResponse('html');
		$tpl = new jTpl();
		$tpl->assign('nbSettings',$nbSettings);
		$tpl->assign('nbUsers',$nbUsers);
		$tpl->assign('nbForums',$nbForums);
		$tpl->assign('nbPosts',$nbPosts);
		$rep->body->assign('MAIN', $tpl->fetch('hfnuinstall~migrate'));
		$rep->body->assign('step', 'migrate');
		return $rep;
	}
}
