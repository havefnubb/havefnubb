<?php
/**
* @package      sharecode
* @subpackage   jTags
* @author       Bastien Jaillot
* @copyright    2008 Bastien Jaillot
* @link         http://forge.jelix.org/projects/sharecode/
* @link         http://www.vinch.be/blog/2007/01/28/comment-creer-un-nuage-de-tags-en-phpmysql/
* @licence      http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
*/

class tagscloudZone extends jZone {

    protected $_tplname='tagscloud';
    protected $_useCache = true;
    

    protected function _prepareTpl(){
        $matfactory = jDao::get("jtags~tags");
        
        $destination = $this->getParam("destination", null);
        
        $tags = $matfactory->findAll();
        
        define("MIN_SIZE", 0.5);
        define("MAX_SIZE", 2);

        $min = $max = 0;
        
        foreach ($tags as $t) {
            if ($t->nbuse < $min) $min = $t->nbuse;
            if ($t->nbuse > $max) $max = $t->nbuse;
        }

        $min_size = MIN_SIZE;
        $max_size = MAX_SIZE;

        $size = array();
        foreach ($tags as $t) {
            $size[$t->tag_id] = intval($min_size + (($t->nbuse - $min) * (($max_size - $min_size) / ($max - $min))));
        }
        
        
        $nbObjects = $matfactory->countAll();
        
        $this->_tpl->assign(compact('tags', 'size', 'nbObjects', 'destination'));
    }
}
?>
