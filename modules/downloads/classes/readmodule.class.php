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
class readmodule { 

    public static function readModuleXml(){
        
        global $gJConfig; 
        $moduleList = $gJConfig->_modulesPathList;
        $ns =  'jelixmodule'; 
 	    $nsURL = 'http://jelix.org/ns/module/1.0';
                          
        $moduleInfos = array(); 
                 
        $doc = new DOMDocument; 
        $doc->Load($moduleList['downloads'] .'/module.xml'); 
         
        $xpath  = new DOMXPath($doc); 
        $xpath->registerNamespace($ns,$nsURL); 
 
        $query = '//'.$ns.':info/@id'; 
        $entries = $xpath->query($query); 
         
        $moduleId =  $entries->item(0)->nodeValue; 
         
        $query = '//'.$ns.':info/@name'; 
        $entries = $xpath->query($query); 
     
        $moduleName =  $entries->item(0)->nodeValue; 
    
        $query = '//'.$ns.':version/@stability'; 
        $entries = $xpath->query($query); 
 
        $versionStability = $entries->item(0)->nodeValue; 
 
        $query = '//'.$ns.':version/text()'; 
        $entries = $xpath->query($query); 
         
        $versionNumber = $entries->item(0)->nodeValue; 
 
        $query = '//'.$ns.':label/text()'; 
        $entries = $xpath->query($query); 
         
        $label = $entries->item(0)->nodeValue; 

        $query = '//'.$ns.':description/text()'; 
        $entries = $xpath->query($query); 
         
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
         
        $notes = $entries->item(0)->nodeValue; 
             
        $moduleInfos = array( 
                        'id'=>$moduleId, 
                        'version'=>$versionStability . ' ' . $versionNumber, 
                        'label'=>$label, 
                        'desc'=>$desc, 
                        'creators'=>$creators, 
                        'notes'=>$notes);      
     
        return $moduleInfos; 

    }
}    