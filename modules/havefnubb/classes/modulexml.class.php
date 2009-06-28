<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// Class to parse the module.xml file
class modulexml {
    public static $ns     =  'jelixmodule';
    public static $nsURL  = 'http://jelix.org/ns/module/1.0'; 
    
    public static function parse($theModuleName) { 
        global $gJConfig;
        
        $moduleList = $gJConfig->_modulesPathList;

        if (! array_key_exists($theModuleName,$moduleList) ) return false;
        
        $ns =  'jelixmodule'; 
 	    $nsURL = 'http://jelix.org/ns/module/1.0';
                          
        $moduleInfos = array(); 
                 
        $doc = new DOMDocument; 
        $doc->Load($moduleList[$theModuleName] .'/module.xml'); 
         
        $xpath  = new DOMXPath($doc); 
        $xpath->registerNamespace($ns,$nsURL); 
 
        $query = '//'.$ns.':info/@id'; 
        $entries = $xpath->query($query);          
        $moduleId =  $entries->item(0)->nodeValue; 
         
        $query = '//'.$ns.':info/@name'; 
        $entries = $xpath->query($query); 
        $moduleName =  $entries->item(0)->nodeValue; 

        $query = '//'.$ns.':info/@createdate'; 
        $entries = $xpath->query($query); 
        $moduleDateCreated =  $entries->item(0)->nodeValue; 
    
        $query = '//'.$ns.':version/@stability'; 
        $entries = $xpath->query($query);  
        $versionStability = $entries->item(0)->nodeValue; 
 
        $query = '//'.$ns.':version/text()'; 
        $entries = $xpath->query($query);          
        $versionNumber = $entries->item(0)->nodeValue; 
 
        $label = 'N/A';
        $query = '//'.$ns.":label[@lang='".$gJConfig->locale."']/text()";
        $entries = $xpath->query($query); 
        if ( ! is_null($entries->item(0))) 
            $label = $entries->item(0)->nodeValue; 

        $desc = 'N/A';
        $query = '//'.$ns.":description[@lang='".$gJConfig->locale."']/text()";
        $entries = $xpath->query($query);
                if ( ! is_null($entries->item(0)))  
            $desc = $entries->item(0)->nodeValue; 
        
        $creators = array(); 
        $query = '//'.$ns.':creator'; 
         
        $entries = $xpath->query($query); 
 
        foreach ($entries as $entry) { 
            $creatorName = ''; 
            $creatorNickname = ''; 
            $creatorEmail = ''; 
            $creatorActive = ''; 
            if ($entry->hasAttribute('name')) 
                $creatorName = $entry->getAttribute('name'); 
            else { 
                die("fichier module.xml invalide"); 
            } 
            if ($entry->hasAttribute('nickname')) 
                $creatorNickname = $entry->getAttribute('nickname'); 
            if ($entry->hasAttribute('email')) 
                $creatorEmail = $entry->getAttribute('email');

            if ($entry->hasAttribute('active')) 
                $creatorActive = $entry->getAttribute('active'); 
                 
            $creators[] = array('name'=>$creatorName, 
                                'nickname'=>$creatorNickname, 
                                'email'=>$creatorEmail, 
                                'active'=>$creatorActive); 
        } 
        
        $query = '//'.$ns.':notes'; 
        $entries = $xpath->query($query);
        $notes = 'N/A';
        if ( ! is_null($entries->item(0))) 
            $notes = $entries->item(0)->nodeValue; 

        $updateURL = '';
        $query = '//'.$ns.':updateURL/text()'; 
        $entries = $xpath->query($query);
        $updateURL = $entries->item(0)->nodeValue;
        
        $homepageURL = '';
        $query = '//'.$ns.':homepageURL/text()'; 
        $entries = $xpath->query($query);
        $homepageURL = $entries->item(0)->nodeValue;        

        $licence = '';
        $query = '//'.$ns.':licence/text()'; 
        $entries = $xpath->query($query);
        $licence = $entries->item(0)->nodeValue;

        $licenceURL = '';
        $query = '//'.$ns.':licence/@URL'; 
        $entries = $xpath->query($query);
        $licenceURL = $entries->item(0)->nodeValue;


        $copyright = '';
        $query = '//'.$ns.':copyright/text()'; 
        $entries = $xpath->query($query);
        $copyright = $entries->item(0)->nodeValue;
        
        $moduleInfos = array(
                        'name'=>$moduleName,
                        'id'=>$moduleId, 
                        'version'=>$versionStability . ' ' . $versionNumber,
                        'dateCreate'=>$moduleDateCreated,
                        'label'=>$label, 
                        'desc'=>$desc, 
                        'creators'=>$creators, 
                        'notes'=>$notes,
                        'updateURL'=>$updateURL,
                        'homepageURL'=>$homepageURL,
                        'licence'=>$licence,
                        'licenceURL'=>$licenceURL,
                        'copyright'=>$copyright
                        );      
     
        return $moduleInfos; 
         
    }         
    
    public static function moduleInfo($file,$info) {

        self::$ns     =  'jelixmodule'; 
        self::$nsURL  = 'http://jelix.org/ns/module/1.0'; 
        
        $doc = new DOMDocument; 
        $doc->Load($file); 
 		                 
        $xpath  = new DOMXPath($doc); 
        $xpath->registerNamespace($ns,$nsURL); 
 		            
        $query = '//'.$ns.':'.$info; 
        $entries = $xpath->query($query);
        $value = 'N/A';
        if (!is_null($entries->item(0))) 
            $value = $entries->item(0)->nodeValue; 
 		                 
 		return $value; 
    }
}

