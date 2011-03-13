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
function cmpTagsCloud ($a, $b) {
    $n = strtolower($a->tag_name);
    $m = strtolower($b->tag_name);
    if ($n == $m)
        return 0;
    if ($n > $m)
        return 1;
    return -1;
    
}



class tagscloudZone extends jZone {
    const MIN_SIZE = 1;
    const MAX_SIZE = 10;

    protected $_tplname='tagscloud';
    protected $_useCache = false;


    protected function _prepareTpl(){
        $tagsfactory = jDao::get("jtags~tags");
        $destination = $this->param("destination", null);
        $maxcount = $this->param('maxcount',0);

        $min = $max = 0;
        if ($maxcount > 0) {
            $tagsList = $tagsfactory->findLimit($maxcount);
            $tags = array();
            foreach ($tagsList as $t) {
                if ($t->nbuse < $min) $min = $t->nbuse;
                if ($t->nbuse > $max) $max = $t->nbuse;
                $tags[] = $t;
            }
            usort($tags, 'cmpTagsCloud');
            $nbObjects = count($tags);
        }
        else {
            $tags = $tagsfactory->findAll();
            $nbObjects = $tags->rowCount();
            foreach ($tags as $t) {
                if ($t->nbuse < $min) $min = $t->nbuse;
                if ($t->nbuse > $max) $max = $t->nbuse;
            }
        }

        if ($max == $min || ($max == 0 && $min == 0)) {
            $coeff = 1;
        }
        else {
            $coeff = ((self::MAX_SIZE - self::MIN_SIZE) / ($max - $min));
        }
        $size = array();
        foreach ($tags as $t) {
            $size[$t->tag_id] = intval(self::MIN_SIZE + (($t->nbuse - $min) * $coeff));
        }

        $this->_tpl->assign(compact('tags', 'size', 'nbObjects', 'destination'));
    }
}
