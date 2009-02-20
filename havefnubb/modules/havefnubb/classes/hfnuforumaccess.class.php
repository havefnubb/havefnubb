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
    $data = array();
    $dao = jDao::get('havefnubb~forum');
    $recs = $dao->findAll();
    foreach ($recs as $rec) {
      if ( jAcl2::check('hfnu.forum.view') ) {
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