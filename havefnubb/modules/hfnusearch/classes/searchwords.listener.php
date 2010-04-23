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
 * Class that manage the responds of the Events on the Requests
 */
class searchwordsListener extends jEventListener{
	/**
	* updating the search_words table for each of the following event
	*/
	function onHfnuSearchEngineAddContent ($event) {
		$id 			= $event->getParam('id');
		$dataSource 	= $event->getParam('datasource');

		$strId = '';
		if (is_array($id)) {

			for ($i = 0 ; $i < count($id) ; $i ++)
				$strId .= $id[$i];
		}
		else $strId = $id;

		// due to a bug in jelix parser of ini file, we drop the ~ for nothing and
		// will be able to find the datasource in our config file
		$dataSourceCfg = str_replace('~','',$dataSource);

		// 1) get the column definition we whish to index in the search engine
		$HfnuSearchConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');

		$indexSubject = $HfnuSearchConfig->getValue('index_subject',$dataSourceCfg);
		$indexMessage = $HfnuSearchConfig->getValue('index_message',$dataSourceCfg);

		// 2) get the Datas we just added
		$dao = jDao::get($dataSource);
		$rec = $dao->get($id);

		$subject = $indexSubject != '' ? $rec->$indexSubject : '';
		$message = $indexMessage != '' ? $rec->$indexMessage : '';

		// 3) get the service and initialize the needed properties
		jClasses::inc('hfnusearch~search_index');
		$service = new search_index( $strId, $dataSource, $subject, $message);

		// update the SearchWords table !
		$service->searchEngineUpdate();
	}

	function onHfnuSearchEngineDeleteContent ($event) {
		$id = $event->getParam('id');
		$dataSource = $event->getParam('datasource');
		if ($id == '' or $dataSource == '') return;

		$strId = '';
		if (is_array($id)) {

		   for ($i = 0 ; $i < count($id) ; $i ++)
			  $strId .= $id[$i];
		}
		else $strId = $id;

		jClasses::inc('hfnusearch~search_index');
		$service = new search_index( $strId, $dataSource);
		$service->searchEngineDelete();
	}

	function onHfnuSearchEngineRun ($event) {
		$HfnuSearchConfig = new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');

		$cleaner = jClasses::getService('hfnusearch~cleaner');
		$words = $cleaner->stemPhrase($event->getParam('string'));
		$nb_words = count($words);
		// no words ; go back with nothing :P
		if (!$words) {
			return array('count'=>0,'result'=>array());
		}

		$service = jClasses::getService($HfnuSearchConfig->getValue('classToPerformSearchEngine'));
		$result = $service->searchEngineRun($event);
	}
}
