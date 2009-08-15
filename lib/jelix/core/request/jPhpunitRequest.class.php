<?php
/**
* @package     jelix
* @subpackage  core_request
* @author      Yannick Le Guédart
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

require_once (JELIX_LIB_CORE_PATH.'request/jCmdLineRequest.class.php');
require_once ('PHPUnit/Framework/TestCase.php');

class jPhpunitRequest extends jCmdLineRequest
{
    public $type = 'phpunit';

    public $defaultResponseType = 'text';

        protected function _initParams ()
        {
                require 'PHPUnit/TextUI/Command.php';

                define('PHPUnit_MAIN_METHOD', 'PHPUnit_TextUI_Command::main');

                PHPUnit_TextUI_Command::main();
        }
}