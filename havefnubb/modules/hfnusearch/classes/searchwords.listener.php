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
   
   /* action to do (updateSearchIndex) when occurs each of the following event :
   * - onHfnuPostAfterSave, 
   * - onHfnuPostAfterInsert, 
   * - onHfnuPostAfterSaveReply
   */
   function onHfnuPostAfterSave ($event) {
        $id = $event->getParam('id_post');
		$this->updateSearchIndex($id);
    }

   function onHfnuPostAfterInsert ($event) {
        $id = $event->getParam('id_post');
		$this->updateSearchIndex($id);        
   }
   
   function onHfnuPostAfterSaveReply ($event) {
		$id = $event->getParam('id_post');
		$this->updateSearchIndex($id);
    }
   
   function onHfnuPostAfterDelete ($event) {
        $id = $event->getParam('id_post');
        
        $service = jClasses::getService('hfnusearch~search_index');
        $service->delete($id);
   }
   
   private function updateSearchIndex($id) {
		if ($id == 0) die("no!");
		//get the post !
        $dao = jDao::get('havefnubb~posts');
        $rec = $dao->get($id);
        // update the SearchWords table !
        jClasses::inc('hfnusearch~search_index');
        $service = new search_index();
        $service->subject = $rec->subject;
        $service->message = $rec->message;
        $service->updateIndex($id);   
   }
}
