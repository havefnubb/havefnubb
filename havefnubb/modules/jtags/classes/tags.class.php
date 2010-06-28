<?php
/**
* @package      sharecode
* @subpackage   jTags
* @author       Bastien Jaillot
* @copyright    2008 Bastien Jaillot
* @link         http://forge.jelix.org/projects/sharecode/
* @licence      http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/

class tags {

    protected $dao_tags = "jtags~tags";
    protected $dao_object_tags = "jtags~objects_tags";



    function getTagsBySubject($scope, $id) {
        $factory_objects_tags = jDao::get($this->dao_object_tags);
        $daotags = $factory_objects_tags->tagsBySubject($scope, $id);
        $tags = array();
        foreach ($daotags as $t) {
            $tags[] = $t->tag_name;
        }
        return $tags;
    }


    function saveTagsBySubject($tags, $scope, $id) {

        $factory_tags = jDao::get($this->dao_tags);
        $factory_objects_tags = jDao::get($this->dao_object_tags);

        // each time we submit a form we :
        // 1) get all the sc_tags_tagged records of the corresponding id
        // 2) check if the tag is still use  in sc_tags (nbuse > 1)
        // 2.1) if yes ; then nbuse-- of this tag
        // 2.2) if no ; remove the tag from sc_tags + sc_tags_tagged

        $cond = jDao::createConditions();
        //find all subject id
        $cond->addCondition('tt_subject_id','=',$id);
        // 1)
        $tagsToDelete = $factory_objects_tags->findBy($cond);
        foreach($tagsToDelete as $delete) {
            //2)
            $myTag = $factory_tags->get($delete->tag_id);

            if ($myTag->nbuse > 1) {
                // 2.1)
                // delete the tag from the tag_id found earlier
                $myTag->nbuse--;
                $factory_tags->update($myTag);
            }
                // 2.2)
            else {
                $factory_tags->delete($delete->tag_id);
                $factory_objects_tags->delete($delete->tt_id);
            }
        }

        foreach($tags as $t) {
            $t = trim($t);
            if ($t == "")  continue;
            if ($tag = $factory_tags->tagExiste($t)) {
                $idTag = $tag->tag_id;
                $tag->nbuse++;
                $factory_tags->update($tag);
            }
            else {
                $idTag = $this->createTag($t);
            }

            $objectTags = jDao::get($this->dao_object_tags);
            if ( ! $objectTags->tagAndsubjectExists($idTag,$id)) {

                // insertion dans objects_tags
                $snippets_tag = jDao::createRecord($this->dao_object_tags);
                $snippets_tag->tag_id = $idTag;
                $snippets_tag->tt_scope_id = $scope;
                $snippets_tag->tt_subject_id = $id;

                $factory_objects_tags->insert($snippets_tag);
            }
        }
        jZone::clear("jtags~tagscloud");
        jZone::clear("jtags~tagsbyobject", array("scope"=>$scope, "id"=>$id));
    }

    function getJsonAll() {
        $factory_tags = jDao::get($this->dao_tags);
        $tags = $factory_tags->findAll();
        $newtags = "[";
        foreach($tags as $t) {
            $newtags .= '"'.$t->tag_name.'"'. ", ";
        }
        $newtags .= "]";
        return $newtags;
    }

    function getSubjectsByTags($tags, $scope) {

        // Get tags
        $tags = explode(',', $tags);

        // Count tags
        $tags_cnter = count($tags);

        // Get all tags_tagged for the given tags
        $ttag_fact = jDao::get($this->dao_object_tags);
        $conditions = jDao::createConditions();

        $conditions->addCondition('tt_scope_id', '=', $scope);
        $conditions->startGroup('OR');
        foreach ($tags as $tag) {
            $conditions->addCondition('tag_name', '=', trim($tag));
        }
        $conditions->endGroup();

        $ttags = $ttag_fact->findBy($conditions);

        // We have to count the apparition of each subject_id
        $ttags_cnter = array();
        foreach ($ttags as $ttag) {
            // Init
            if (!isset($ttags_cnter[$ttag->tt_subject_id])) {
                $ttags_cnter[$ttag->tt_subject_id] = 0;
            }

            // Count
            $ttags_cnter[$ttag->tt_subject_id]++;
        }

        // Keep only subject_id which appear $tags_cnter times
        return array_keys($ttags_cnter, $tags_cnter);
    }

    function setResponsesHeaders() {
        global $gJCoord;
        $gJCoord->response->addCSSLink('/js/jquery-autocomplete/jquery.autocomplete.css');

        $gJCoord->response->addJSLink("/js/jquery-autocomplete/jquery.autocomplete.pack.js");
        $gJCoord->response->addJSLink('/js/jquery-autocomplete/jquery.autocomplete.pack.js');
        $gJCoord->response->addJSLink('/js/jquery-autocomplete/lib/jquery.bgiframe.min.js');
        $gJCoord->response->addJSLink('/js/jquery-autocomplete/lib/jquery.ajaxQueue.js');
        $gJCoord->response->addJSLink('/js/jquery-autocomplete/lib/jquery.dimensions.js');

        $newtags = $this->getJsonAll();

        $gJCoord->response->addJSCode('
            var tags = '.$newtags.';
            JQ = jQuery.noConflict(true);
            JQ(document).ready(function(){
                JQ("#jform1_tags").autocomplete(tags, {
                    width: 300,
                    multiple: true,
                    matchContains: true
                });
            });
        ');
    }

    protected function createTag($tag_name) {
        $factory_tags = jDao::get($this->dao_tags);
        $newTag = jDao::createRecord($this->dao_tags);
        $newTag->tag_name = $tag_name;
        $newTag->nbuse = 1;
        $factory_tags->insert($newTag);
        return $newTag->getPk();
    }
}
