<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


class hfnuforumaccess implements jIFormDatasource
{
  protected $formId = 0; 
  protected $data = array();  

  function __construct($id)  
  {
    // " jump to " will display all the forum except :
    // 1) the current one
    // 2) the ones the access are not granted
    $data = array();
    $dao = jDao::get('havefnubb~forum');
    $recs = $dao->findAll();
    foreach ($recs as $rec) {
      if ( $rec->id_forum != $id and jAcl2::check('hfnu.forum.view','forum'.$rec->id_forum) ) {
            $data[$rec->id_forum] = $rec->forum_name;
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