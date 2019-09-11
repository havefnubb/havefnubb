<?php
/**
* @package   havefnubb
* @subpackage havefnubb
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Forum List DataSource that handles a dropdwon list
*/
class hfnuforumaccess implements jIFormsDatasource {
    /**
     * @var integer $formId id of the form
     */
    protected $formId = 0;
    /**
     * @var array $data
     */
    protected $data = array();
    function __construct($id)  {
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

    /**
     * @param object $form object of the form
     * @return array the data
     */
    public function getData($form) {
        return ($this->data);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getLabel($key) {
        if(isset($this->data[$key]))
            return $this->data[$key];
        else
          return null;
    }
}
