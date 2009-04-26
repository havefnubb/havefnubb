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

class defaultCtrl extends jController {
    /**
    *
    */  
    public $pluginParams = array(
        '*'=>array('auth.required'=>false),        
     );
       
    // Public : list all the enabled Downloads for a given " path " !
    function index() {
        
        $dir = (string) $this->param('dir');       
        if (empty($dir)) $dir = 'files';

        $rep = $this->getResponse('html');
        
        $rep->addLink(jUrl::get('downloads~feeds:lastest',array('dir'=>$dir)),
                       'alternate',
                       'application/rss+xml',
                       jLocale::get('downloads~common.lastest.downloads')
                      );
        $rep->addLink(jUrl::get('downloads~feeds:mostpopular',array('dir'=>$dir)),
                       'alternate',
                       'application/rss+xml',
                       jLocale::get('downloads~common.most.popular.downloads')
                       );
        
        $offset = 0;
        if ( $this->param('offset') > 0 )
            $offset = (int) $this->param('offset');
            
        
        $rep->title = jLocale::get('index.public.title.page'); 
        $rep->body->assign("MAIN", jZone::get('downloads~list', array('dir'=>$dir,'offset'=>$offset) ) ) ; 
        return $rep;    
    }
    
    // Public : view the selected Download (by its url) for a given " path " !
    function view() {
        $url = (string) $this->param('url');
        $dir = (string) $this->param('dir');
        if (empty($dir)) $dir = 'files';
        
        $rep = $this->getResponse('html');
        
        $dao = jDao::get('downloads~downloads');
        $theDownload = $dao->getByUrlAndPath($url,$dir);

        if (! $theDownload ) {
            $rep = $this->getResponse('redirect');
            $rep->action='jelix~error:notfound';
            return $rep;             
        }
        jClasses::inc('download_files');        
        $filesize = downloadFiles::getFileSize($theDownload->dl_filename,$theDownload->dl_path);

        if ($filesize == '' ) {
            jMessage::add('Fichier vide ou inexistant','public_msg');            
            $rep = $this->getResponse('redirect');
            $rep->action='jelix~error:notfound';
            return $rep;             
        }
        
        $rep->title = jLocale::get('index.public.title.page');
        $rep->body->assign("MAIN", jZone::get('downloads~view',array('dir'=>$dir,
                                                                     'filesize'=>$filesize,
                                                                     'data'=>$theDownload))); 
        return $rep;    
    }
    
    // Public : let's dl the file 
    function dl() {
        // load the config file
        jClasses::inc('download_config');        
        $config = downloadConfig::getConfig();
        // get the Antileech stuff   
        jClasses::inc('download_files'); 
        //config says : 
        if (
            // ... we dont allow leecher !
            $config->getValue('allow.external.links') == 0 and 
            // ... and we found one !
                     
            downloadFiles::antileech() == true) {                
            // so go away
            $rep = $this->getResponse('redirect');
            $rep->action='jelix~error:notfound';
            return $rep;
        }

        $id = (string) $this->param('id');
        $dao = jDao::get('downloads~downloads');
        $theDownload = $dao->get($id);            
        $file = $config->getValue('commons.upload.dir').DIRECTORY_SEPARATOR.$theDownload->dl_path.DIRECTORY_SEPARATOR.$theDownload->dl_filename;
        
        if (file_exists($file)) {
            $rep = $this->getResponse('binary');
            $rep->outputFileName = $theDownload->dl_filename;
            $rep->fileName = $file;
    
            $theDownload->dl_count = $theDownload->dl_count +1;
            
            $dao->update($theDownload);
        } else {
            $rep = $this->getResponse('redirect');
            $rep->action='jelix~error:notfound';
        }
        return $rep;
    }    
}

