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
     * default searchEngineRun methode which make a search from the engine by querying the table define in the dao of the hfnusearch.ini.php file
     * @param object $event
     */
    function searchEngineRun ($event) {
        $cleaner = jClasses::getService('hfnusearch~cleaner');
        $words = $cleaner->stemPhrase($event->getParam('string'));
        $page       = (int) $event->getParam('page');
        $limit      = (int) $event->getParam('limit');
        $id_forum   = (int) $event->getParam('id_forum');

        // no words ; go back with nothing :P
        if (!$words) {
            return array('count'=>0,'result'=>array());
        }
        //1) open the config file
        $HfnuSearchConfig  =  parse_ini_file(JELIX_APP_CONFIG_PATH.'havefnu.search.ini.php', true);
        //2) get the dao we want to read
        $dataSource = $HfnuSearchConfig->getValue('dao');
        //3) build an array with each one
        $dataSources = preg_split('/,/',$dataSource);
        foreach ($dataSources as $ds) {
            //4) get a factory of the current DAO
            $dao = jDao::get($ds);

            //getting the column name on which we need to make the query
            $indexSubject = $HfnuSearchConfig[$ds]['index_subject'];
            $indexMessage = $HfnuSearchConfig[$ds]['index_message'];

            //5) get all the record
            $conditions = jDao::createConditions();

            if ($id_forum > 0) {
                $conditions->addCondition('id_forum','=',$id_forum);
                $conditions->startGroup('OR');
            }
            else
                $conditions->startGroup('AND');

            foreach ($words as $word) {
                $conditions->addCondition($indexSubject,'LIKE','%'.$word.'%');
                $conditions->addCondition($indexMessage,'LIKE','%'.$word.'%');
            }
            $conditions->endGroup();

            $allRecord = $dao->findBy($conditions);
            if ($page > 0 and $limit > 0)
                $record = $dao->findBy($conditions, $page, $limit);
            else
                $record = $allRecord;

            foreach ($record as $rec) {
                if ($rec->status == 'hidden' and jAcl2::check('hfnu.admin.post') )
                    $event->Add(array('SearchEngineResult'=>$rec,
                                  'SearchEngineResultTotal'=>$allRecord->rowCount()
                                  )
                        );
                elseif ($rec->status != 'hidden' and jAcl2::check('hfnu.forum.view','forum'.$rec->id_forum))
                    $event->Add(array('SearchEngineResult'=>$rec,
                                  'SearchEngineResultTotal'=>$allRecord->rowCount()
                                  )
                        );
            }
        }
    }
}
