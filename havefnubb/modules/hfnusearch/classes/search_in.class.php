<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class search_in {
	function searchInWords($string,$param='') {
		$eventresp = jEvent::notify('HfnuSearchEngineRun', array('string'=>$string) );

		$result = array();
		foreach($eventresp->getResponse() as $rep){
			if(!isset($rep['SearchEngineResult']) )
				return false;
			else {
				$result[] = (array) $rep['SearchEngineResult'];
			}
		}
		return $result;
	}

	function searchInForums($string,$id_forum) {
		$eventresp = jEvent::notify('HfnuSearchEngineRun', array('string'=>$string,'id_forum'=>$id_forum) );

		$result = array();
		foreach($eventresp->getResponse() as $rep){
			if(!isset($rep['SearchEngineResult']) )
				return false;
			else {
				$result[] = (array) $rep['SearchEngineResult'];
			}
		}
		return $result;
	}

	function searchInAuthors($string,$param='') {
		$dao = jDao::get('havefnubb~posts');
		$records = $dao->findByAuthor($string);

		foreach ($records as $record)
			$result[] = (array) $record;

		return $result;
	}
}
