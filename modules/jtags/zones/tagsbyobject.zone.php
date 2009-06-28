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

class tagsbyobjectZone extends jZone {
    protected $_tplname='tagsbyobject';
    protected $_useCache = true;
    
    protected function _prepareTpl(){
        $id = $this->getParam('id', false);
        $scope = $this->getParam('scope', false);
        
        if (!$id || !$scope) {
            throw new Exception(jLocale::get("jtags~tags.error.parametermissing"));
        }
        $tags = jClasses::getService("jtags~tags")->getTagsBySubject($scope, $id);
        
        
        $this->_tpl->assign(compact('tags'));
    }
}
?>
