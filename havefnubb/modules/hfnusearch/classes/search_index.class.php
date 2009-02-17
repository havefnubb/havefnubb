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
	* function getWords
	* split the search request sentence in several words
	* and add the weight for each case
	*
	* @return array $words array of the words
	*/  
	
    function getWords() {
        global $HfnuConfig;
        // body
        $longText =  str_repeat(' '.$this->message, $HfnuConfig->getValue('search_content_weight'));
    
        // title
        $longText .= str_repeat(' '.$this->subject, $HfnuConfig->getValue('search_subject_weight'));
        
        $words = str_word_count(strtolower($longText),1);

		// @TODO : implement stem algorihtm
        
        // cleanning the phrase of unuseful words
        $cleaner = jClasses::getService('hfnusearch~cleaner');                      
        $words = $cleaner->removeStopwords($words);        
        
        $words = array_count_values($words);
        
        return $words;        
    }
    
	/* 
	* function updateIndex
	* manages the content of the search_words table
	* for one post, we update all the words in search_words table
	*
	* @param $id id of the post to update
	*/
    function updateIndex($id) {
        if ($id == 0 ) die ("no!");
        // 1) remove all the words of the current post
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
}