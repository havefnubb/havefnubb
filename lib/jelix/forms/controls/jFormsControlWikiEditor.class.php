<?php
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor 
* @copyright   2006-2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 *
 * @package     jelix
 * @subpackage  forms
 * @since 1.1
 */
class jFormsControlWikiEditor extends jFormsControl {
    public $type='wikieditor';
    public $rows=5;
    public $cols=40;
    public $config='default';
    function __construct($ref){
        $this->ref = $ref;
        $this->datatype = new jDatatypeHtml();
    }
}
