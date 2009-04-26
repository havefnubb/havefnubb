<?php
/**
* @package      downloads
* @subpackage
* @author       foxmask
* @contributor foxmask
* @copyright    2008 foxmask
* @link
* @licence  http://www.gnu.org/licenses/gpl.html GNU General Public Licence, see LICENCE file
*/

class feedsCtrl extends jController {
    /**
    *
    */
    private $dlDao = 'downloads~downloads';
    
    public $pluginParams = array(
        '*'=>array('auth.required'=>false)
    );
    
    function lastest() {
        $from = (string) $this->param('dir');
        if (!$from) return;
        
        return $this->rss('lastest',$from);
    }
    
    function mostpopular() {
        $from = (string) $this->param('dir');
        if (!$from) return;
        
        return $this->rss('popular',$from);
    }
    
    private function rss($what,$from) {

        if ($what != 'popular' and $what != 'lastest') exit;
        
        jClasses::inc('download_config');
        $config = downloadConfig::getConfig();

        $rep = $this->getResponse('rss2.0');
        
        $rep->infos->title = jLocale::get('feeds.'.$what.'.downloads');
        $rep->infos->webSiteUrl= $config->getValue('website.url');
        $rep->infos->copyright = $config->getValue('website.copyright');
        $rep->infos->description = $config->getValue('website.description');
        $rep->infos->updated = '2007-06-08 12:00:00';
        $rep->infos->published = '2007-06-08 12:00:00';
        $rep->infos->ttl=$config->getValue('website.ttl');

        $feedsDao = jDao::get($this->dlDao);
        $first = true;
        
        $findThat = 'find'.ucfirst($what);
        $list = $feedsDao->$findThat($from);
            
        foreach($list as $feeds){

            if($first){
                $rep->infos->updated = $feeds->dl_date;
                $rep->infos->published = $feeds->dl_date;
                $first=false;
            }
         
            $url = jUrl::get('downloads~default:view', array('dir'=>$feeds->dl_path,'url'=>$feeds->dl_url));
         
            $item = $rep->createItem($feeds->dl_name, $url, $feeds->dl_date);
         
            $item->authorName = $config->getValue('website.author');
                  
            $item->content = $feeds->dl_desc;
            $item->contentType='html';
            
            $item->id =$feeds->id;
         
            $rep->addItem($item);
        }
        
        return $rep;    
    }

}

