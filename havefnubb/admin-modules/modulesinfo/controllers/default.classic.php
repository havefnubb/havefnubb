<?php
/**
* @package   havefnubb
* @subpackage modulesinfo
* @author    FoxMaSk
* @contributor Laurent Jouanneau
* @copyright 2008-2011 FoxMaSk, 2010 Laurent Jouanneau
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * This controller display the content of the module.xml file
 */
class defaultCtrl extends jController {
    /**
     * @var plugins to manage the behavior of the controller
     */
    public $pluginParams = array(
        '*' => array('auth.required'=>true,
                    'banuser.check'=>true,
                    'jacl2.right'=>'modulesinfo.access'
          ),
    );

    function index() {
        $rep = $this->getResponse('html');
        $rep->body->assignZone('MAIN','modules');
        $rep->body->assign('selectedMenuItem','modules');
        return $rep;
    }
}
