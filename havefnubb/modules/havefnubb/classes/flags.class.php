<?php
/**
 * @package   havefnubb
 * @subpackage havefnubb
 * @author    FoxMaSk
 * @copyright 2008-2011 FoxMaSk
 * @link      http://havefnubb.org
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */
/**
 * DataSource to get the list of flags of all the countries
 */
class flags implements jIFormsDatasource {
    /**
     * @var integer $formId id of the form
     */
    protected $formId = 0;
    /**
     * @var array $data
     */
    protected $data = array();

    function __construct($id) {

        $data = jClasses::getService('havefnubb~country')->getCountries();

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
