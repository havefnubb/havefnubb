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
 * Class that manage the search engine
 */
class search_index {
	/**
	 *@var string $message
	 */
	public $message 	= '';
	/**
	 *@var string $subject
	 */
	public $subject 	= '';
	/**
	 *@var integer $id
	 */
	public $id 			= '';
	/**
	 *@var string $dataSource
	 */
	public $dataSource 	= '';

	/*
	public function __construct($id='',$dataSource='',$subject='',$message='') {
	$this->id 	= $id;
	$this->dataSource= $dataSource;
	$this->message 	= $message;
	$this->subject 	= $subject;
	}*/
	/**
	* split the search request sentence in several words
	* and add the weight for each case
	*
	* @return array $words list of the words
	*/
	function getWords() {
		$HfnuSearchConfig  =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');
		// body
		$longText =  str_repeat(' '.$this->message, $HfnuSearchConfig->getValue('search_content_weight'));
		// title
		$longText .= str_repeat(' '.$this->subject, $HfnuSearchConfig->getValue('search_subject_weight'));

		$cleaner = jClasses::getService('hfnusearch~cleaner');
		$stemmedWords = $cleaner->stemPhrase($longText);

		$words = array_count_values($stemmedWords);

		return $words;
	}

	/**
	* manages the content of the search_words table
	* for one post, we update all the words in search_words table
	* @return void
	*/
	function searchEngineUpdate() {
		if ($this->id == '' or $this->dataSource == '') return;

		// 1) remove all the words of the current datasource in the table of the search engine
		$dao = jDao::get('hfnusearch~searchwords');
		$dao->deleteByIdDataSource($this->id,$this->dataSource);
		// 2) add all the words of the current post
		foreach ($this->getWords() as $word => $weight) {
			$record = jDao::createRecord('hfnusearch~searchwords');
			$record->id     = $this->id;
			$record->words  = $word;
			$record->weight = $weight;
			$record->datasource = $this->dataSource;
			$dao->insert($record);
		}
	}

	/**
	* delete the words stored in the engine
	*/
	function searchEngineDelete() {
		if ($this->id == '' or $this->dataSource == '') return;
		// 1) remove all the words of the current post in the table of the search engine
		$dao = jDao::get('hfnusearch~searchwords');
		$dao->deleteByIdDataSource($this->id,$this->dataSource);
	}

	/**
	* reindexing of the search engine from the Daos define in the search config file
	* @return integer record count
	*/
	function searchEngineReindexing() {
		set_time_limit(0);
		//1) open the config file
		$HfnuSearchConfig  =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');
		//2) get the dao we want to read
		$dataSource = $HfnuSearchConfig->getValue('dao');
		//3) build an array with each one
		$dataSources = preg_split('/,/',$dataSource);
		foreach ($dataSources as $ds) {
			// due to a bug in jelix parser of ini file, we drop the ~ for nothing and
			// will be able to find the datasource in our config file
			$dsCfg = str_replace('~','',$ds);
			//4) get a factory of the current DAO
			$dao = jDao::get($ds);
			$this->dataSource = $ds;
			//5) get all the record
			$records = $dao->findAll();
			foreach ($records as $rec ) {
			//6) get the columns we want to read and inject their data in the engine
			$indexSubject = $HfnuSearchConfig->getValue('index_subject',$dsCfg);
			$indexMessage = $HfnuSearchConfig->getValue('index_message',$dsCfg);
			$subject = $indexSubject != '' ? $rec->$indexSubject : '';
			$message = $indexMessage != '' ? $rec->$indexMessage : '';
			$this->message 	= $subject;
			$this->subject 	= $message;
			$this->id 	= $rec->id;
			//7) lets update the engine !
			$this->searchEngineUpdate();
			}
		}
		return $records->rowCount();
	}

	/**
	 * default searchEngineRun methode which make a search from the engine by querying the table define in the dao of the hfnusearch.ini.php file
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
		//1) open the config file
		$HfnuSearchConfig  =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');
		//2) get the dao we want to read
		$dataSource = $HfnuSearchConfig->getValue('dao');
		//3) build an array with each one
		$dataSources = preg_split('/,/',$dataSource);
		foreach ($dataSources as $ds) {
			// due to a bug in jelix parser of ini file, we drop the ~ for nothing and
			// will be able to find the datasource in our config file
			$dsCfg = str_replace('~','',$ds);
			//4) get a factory of the current DAO
			$dao = jDao::get($ds);
			//5) get all the record

			$conditions = jDao::createConditions();
			if (count($words) > 0 )
			$conditions->startGroup('OR');

			//getting the column name on which we need to make the query
			$indexSubject = $HfnuSearchConfig->getValue('index_subject',$dsCfg);
			$indexMessage = $HfnuSearchConfig->getValue('index_message',$dsCfg);

			foreach ($words as $word) {
			$conditions->addCondition($indexSubject,'=',$word);
			$conditions->addCondition($indexMessage,'=',$word);
			}
			$record = $dao->findBy($conditions);

			foreach ($record as $rec)
			$event->Add(array('SearchEngineResult'=>$rec));
		}
	}
}
