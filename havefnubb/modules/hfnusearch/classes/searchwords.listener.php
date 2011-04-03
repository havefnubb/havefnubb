<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
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
        $id = $event->getParam('id');
        $ds = $event->getParam('datasource');

        $strId = '';
        if (is_array($id)) {

            for ($i = 0 ; $i < count($id) ; $i ++)
                $strId .= $id[$i];
        }
        else $strId = $id;

        // 1) get the column definition we whish to index in the search engine
        $HfnuSearchConfig  =  parse_ini_file(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php', true);

        //getting the column name on which we need to make the query
        $indexSubject = $HfnuSearchConfig[$ds]['index_subject'];
        $indexMessage = $HfnuSearchConfig[$ds]['index_message'];

        // 2) get the Datas we just added
        $dao = jDao::get($ds);
        $rec = $dao->get($id);

        $subject = $indexSubject != '' ? $rec->$indexSubject : '';
        $message = $indexMessage != '' ? $rec->$indexMessage : '';

        // 3) get the service and initialize the needed properties
        jClasses::inc('hfnusearch~search_index');
        $service = new search_index( $strId, $ds, $subject, $message);

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
        $service = new search_index( $strId, $ds);
        $service->searchEngineDelete();
    }

    function onHfnuSearchEngineRun ($event) {
        $HfnuSearchConfig  =  parse_ini_file(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php', true);

        $cleaner = jClasses::getService('hfnusearch~cleaner');
        $words = $cleaner->stemPhrase($event->getParam('string'));
        $nb_words = count($words);
        // no words ; go back with nothing :P
        if (!$words) {
            return array('count'=>0,'result'=>array());
        }

        $service = jClasses::getService($HfnuSearchConfig['classToPerformSearchEngine']);
        $result = $service->searchEngineRun($event);
    }
}
