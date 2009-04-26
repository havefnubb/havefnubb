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
class listdir implements jIFormDatasource
{
  protected $formId = 0; 
  protected $data = array();  

  function __construct($id)
  {
    $dao = jDao::get('downloads~downloads_users');
    $login = jAuth::getUserSession ()->login;
    $uploadPaths = $dao->findByLogin($login);    
    $data = array();
    if ($uploadPaths->rowCount() > 0 ) {
      jClasses::inc('download_config');
      $config = downloadConfig::getConfig();
      // does this directory exist on the filesystem ?
      if (!is_dir($config->getValue('commons.upload.dir'))) {
        die('the common upload dir defined in the downloads.module.ini.php does not exist on the filesystem');
      }
      foreach($uploadPaths as $uploadPath) {
        $dlPath = $config->getValue('commons.upload.dir').DIRECTORY_SEPARATOR.$uploadPath->path;
        // le repertoire existe-t-il ?
        if (!is_dir($dlPath))
        {        
            continue;
        }
        else {
            
            $data[$uploadPath->path] = $uploadPath->path;
    
        }
      }
    }
    $this->formId = $id;
    $this->data = $data;
        
  }
 
  public function getData($form)
  {
    return ($this->data);
  }
 
  public function getLabel($key)
  {
    if(isset($this->data[$key]))
      return $this->data[$key];
    else
      return null;
  }
 
}