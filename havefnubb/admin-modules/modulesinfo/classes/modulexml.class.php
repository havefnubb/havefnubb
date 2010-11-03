<?php
/**
 * @package   havefnubb
 * @subpackage modulesinfo
 * @author    FoxMaSk
 * @contributor Laurent Jouanneau
 * @copyright 2008 FoxMaSk, 2010 Laurent Jouanneau
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

class moduleInfo {
    public $id='';
    public $name='';
    public $creationDate='';
    
    public $versionDate='';
    public $version = '';
    public $label = '';
    public $description = '';
    
    public $creators = array();
    public $contributors = array();
    public $notes = '';
    public $homepageURL = '';
    public $updateURL = '';
    public $license = '';
    public $licenseURL ='';
}

/**
* Class to parse the module.xml file of each module
*/
class modulexml {
    
    public function getList() {
        global $gJConfig;
        $list = array();
        foreach ($gJConfig->_modulesPathList as $name=>$path) {
            $info = $this->parse($name, $path);
            if ($info)
                $list[$name] = $info;
        }
        return $list;
    }
    
    public function getModule($name) {
        global $gJConfig;

        if (! array_key_exists($theModuleName,$gJConfig->_modulesPathList) ) return false;
        return $this->parse($name, $gJConfig->_modulesPathList[$name]);
    }
    
    /**
     * parse the module.xml file
     * @return moduleInfo module informations
     */
    protected function parse($name, $modulePath) {
        global $gJConfig;
        $file = $modulePath.'/module.xml';
        if (!file_exists($file))
            return null;
        
        $module = new moduleInfo();

        $ns =  'jelixmodule';
        $nsURL = 'http://jelix.org/ns/module/1.0';

        $doc = new DOMDocument;
        $doc->Load($file);

        $xpath  = new DOMXPath($doc);
        $xpath->registerNamespace($ns,$nsURL);

        $query = '//'.$ns.':info/@id';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->id = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':info/@name';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->name = $entries->item(0)->nodeValue;
        else
            $module->name = $name;

        $query = '//'.$ns.':info/@createdate';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->creationDate = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':version/@date';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->versionDate = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':version/text()';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->version = $entries->item(0)->nodeValue;

        $query = '//'.$ns.":label[@lang='".$gJConfig->locale."']/text()";
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->label = $entries->item(0)->nodeValue;

        $query = '//'.$ns.":description[@lang='".$gJConfig->locale."']/text()";
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->description = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':creator';
        $entries = $xpath->query($query);
        if ($entries) {
            foreach ($entries as $entry) {
                $creatorName = '';
                $creatorNickname = '';
                $creatorEmail = '';
                $creatorActive = '';
                if ($entry->hasAttribute('name'))
                    $creatorName = $entry->getAttribute('name');
                else {
                    continue;
                }
                if ($entry->hasAttribute('nickname'))
                    $creatorNickname = $entry->getAttribute('nickname');
                if ($entry->hasAttribute('email'))
                    $creatorEmail = $entry->getAttribute('email');
    
                if ($entry->hasAttribute('active'))
                    $creatorActive = $entry->getAttribute('active');
    
                $module->creators[] = array('name'=>$creatorName,
                                    'nickname'=>$creatorNickname,
                                    'email'=>$creatorEmail,
                                    'active'=>$creatorActive);
            }
        }

        $query = '//'.$ns.':notes';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->notes = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':updateURL/text()';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->updateURL = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':homepageURL/text()';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->homepageURL = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':license/text()';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->license = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':license/@URL';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->licenseURL = $entries->item(0)->nodeValue;

        $query = '//'.$ns.':copyright/text()';
        $entries = $xpath->query($query);
        if ($entries && $entries->item(0))
            $module->copyright = $entries->item(0)->nodeValue;

        return $module;
    }
}
