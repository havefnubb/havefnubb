<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class searchwordsListener extends jEventListener{

   /**
   * updating the search_words table for each of the following event
   */
   
   function onHfnuSearchEngineAddContent ($event) {
        $id = $event->getParam('id');
		if ($id == 0) return;
		
		$HfnuSearchConfig  =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');
		//in the config, get the dao we need to use to get the content to
		// populate the table of the search engine
        $dao = jDao::get($HfnuSearchConfig->getValue('dao'));
        $rec = $dao->get($id);
        // update the SearchWords table !
        jClasses::inc('hfnusearch~search_index');
        $service = new search_index();
        $service->subject = $rec->subject;
        $service->message = $rec->message;
        $service->searchEngineUpdate($id);
    }
	
   function onHfnuSearchEngineDeleteContent ($event) {
        $id = $event->getParam('id');
        if ($id == 0) return ;
        $service = jClasses::getService('hfnusearch~search_index');
        $service->searchEngineDelete($id);
   }
   
   function onHfnuSearchEngineRun ($event) {
	  
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
	  $strQuery = 'SELECT DISTINCT id_post, COUNT(*) as nb, SUM(weight) as total_weight, subject, message, member_login, date_created, id_forum FROM search_words';
	  $strQuery .= ' LEFT JOIN posts  ON posts.id_post  = search_words.id ';
	  $strQuery .= ' LEFT JOIN member ON member.id_user = posts.id_user   ';
	  $strQuery .= ' WHERE (';
	  $counter=0;
	  foreach ($words as $word) {
		  if ($counter > 0 ) $strQuery .= ' OR ';
		  $strQuery .= " words  = '". addslashes($word)."'";
		  $counter++;
	  }
	  
	  $strQuery .= ') ';
	  
	  if ($id_forum > 0)
		 $strQuery .= " AND id_forum = '".$id_forum."' ";
		 
	  $strQuery .= ' GROUP BY id_post ';

	  // AND query?
	  
	  $exact = false;
	  if ($exact)
	  {
		$strQuery .= ' HAVING nb = '.$nb_words;
	  }
	 
	  $strQuery .= ' ORDER BY nb DESC, total_weight DESC';        

	  $rs = $cnx->query($strQuery);
	  $result='';
	  
	  while($record = $rs->fetch()){
		 //let check if the current user can access to the posts we found earlier above
		 if (jAcl2::check('hfnu.forum.view','forum'.$record->id_forum))
			$event->Add(array('SearchEngineResult'=>$record));
	  }	  
 }

}