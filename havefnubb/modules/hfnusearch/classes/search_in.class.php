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
 * Class that look at where to find what
 */
class search_in {
	/**
	 * Search words
	 * @param string $string
	 * @param string $param
	 * @return recordset
	 */
	function searchInWords($string,$param='',$page=0,$limit=0) {
		$eventresp = jEvent::notify('HfnuSearchEngineRun',
									array(	'string'=>$string,
											'page'=>$page,
											'limit'=>$limit
										  )
									);

		$result = array('datas'=>'','total'=>0);
		foreach($eventresp->getResponse() as $rep){
			if(!isset($rep['SearchEngineResult']) ) {
				$result['datas'] = array();
				$result['total'] = 0;
			} else {
				$result['datas'][] = (array) $rep['SearchEngineResult'];
				$result['total'] = (int) $rep['SearchEngineResultTotal'];

			}
		}
		return $result;
	}
	/**
	 * Search in forums
	 * @param string $string the request to search
	 * @param integer $id_forum the id forum in which to make the request
	 * @return recordset
	 */
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
	/**
	 * Search in Author
	 * @param string $string the request to search
	 * @param string $param the id forum in which to make the request
	 * @return recordset
	 */

	function searchInAuthors($string,$param='') {
		$dao = jDao::get('havefnubb~posts');
		$records = $dao->findByAuthor($string);
		$result = array();
		foreach ($records as $record)
			$result[] = (array) $record;

		return $result;
	}
}
