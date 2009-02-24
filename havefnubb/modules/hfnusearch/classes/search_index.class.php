<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class search_index {
    
    public $message = '';
    public $subject = '';    

	/* 
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
    
	/* 
	* manages the content of the search_words table
	* for one post, we update all the words in search_words table
	*
	* @param integer $id to update
	*/
    function searchEngineUpdate($id) {
        if ($id == 0 ) return;
        // 1) remove all the words of the current post in the table of the search engine
        $dao = jDao::get('hfnusearch~searchwords');
        $dao->delete($id);
        // 2) add all the words of the current post
        foreach ($this->getWords() as $word => $weight) {
            $record = jDao::createRecord('hfnusearch~searchwords');
            $record->id     = $id;
            $record->words  = $word;
            $record->weight = $weight;
            $dao->insert($record);
        }        
    }

	/* 
	* delete the words stored in the engine
	*
	* @param integer $id to delete
	*/
    function searchEngineDelete($id) {
        if ($id == 0 ) return;
        // 1) remove all the words of the current post in the table of the search engine
        $dao = jDao::get('hfnusearch~searchwords');
        $dao->delete($id);
    }
	
	/*
	* reindexing of the search engine
	*/
	function searchEngineReindexing() {
		
		$HfnuSearchConfig  =  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php');
        $dao = jDao::get($HfnuSearchConfig->getValue('dao'));
		$records = $dao->findAll();	
		foreach ($records as $rec ) {			
			$this->message = $rec->message;
			$this->subject = $rec->subject;
			$this->searchEngineUpdate($rec->id_post);
		}
		return $records->rowCount();
	}
	
	
}