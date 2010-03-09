<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Class that manage specific behavior of the search engine for HaveFnuBB
 */
jClasses::inc('hfnusearch~search_index');
class search_index_havefnubb extends search_index {
	/**
	 * searchEngineRun methode which make a search from the engine by querying the table define in the dao of the hfnusearch.ini.php file
	 * @param object $event
	 */
	function searchEngineRun ($event) {

		$cleaner = jClasses::getService('hfnusearch~cleaner');
		$words = $cleaner->stemPhrase($event->getParam('string'));
		$nb_words = count($words);
		// no words ; go back with nothing :P
		if (!$words) {
			return array('count'=>0,'result'=>array());
		}

		$id_forum = 0;
		if ($event->getParam('id_forum') > 0)
		 $id_forum = (int) $event->getParam('id_forum');

		// watch in search_words ...
		$cnx = jDb::getConnection();
		$strQuery = 'SELECT DISTINCT id_post, COUNT(*) as nb, SUM(weight) as total_weight, subject, posts.parent_id, message, member_login as login, date_created, forum.id_forum, forum_name, cat_name, category.id_cat FROM '.$cnx->prefixTable('search_words');
		$strQuery .= ' LEFT JOIN ' . $cnx->prefixTable('posts') .' AS posts  ON posts.id_post  = '.$cnx->prefixTable('search_words').'.id ';
		$strQuery .= ' LEFT JOIN ' . $cnx->prefixTable('member') .' AS member ON member.id_user = posts.id_user ';
		$strQuery .= ' LEFT JOIN ' . $cnx->prefixTable('forum') .' AS forum ON forum.id_forum = posts.id_forum ';
		$strQuery .= ' LEFT JOIN ' . $cnx->prefixTable('category') .' AS category ON category.id_cat = forum.id_cat ';
		$strQuery .= ' WHERE (';
		$counter=0;
		foreach ($words as $word) {
		  if ($counter > 0 ) $strQuery .= ' OR ';
		  $strQuery .= " words  = '". addslashes($word)."'";
		  $counter++;
		}

		$strQuery .= ') ';

		if ($id_forum > 0)
		 $strQuery .= " AND forum.id_forum = '".$id_forum."' ";

		$strQuery .= ' GROUP BY id_post ';

		// AND query?

		$exact = false;
		if ($exact)
		{
		$strQuery .= ' HAVING nb = '.$nb_words;
		}

		$strQuery .= ' ORDER BY cat_name ASC, nb DESC, total_weight DESC';

		$rs = $cnx->query($strQuery);
		$result='';

		while($record = $rs->fetch()){
		 //let check if the current user can access to the posts we found earlier above
		 if (jAcl2::check('hfnu.forum.view','forum'.$record->id_forum))
			$event->Add(array('SearchEngineResult'=>$record));
		}
	}

}
