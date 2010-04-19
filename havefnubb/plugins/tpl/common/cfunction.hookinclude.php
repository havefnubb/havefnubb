<?php
/**
 * Plugin from smarty project and adapted for jtpl
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author
 * @copyright  2001-2003 ispi of Lincoln, Inc.
 * @link http://smarty.php.net/
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * function plugin :  generate an event, and listeners are supposed to return template selector, to include
 * these template
 *
 * <pre>{hookinclude 'the_event', $parameters}</pre>
 * @param jTplCompiler $compiler the template compiler
 * @param array $param   0=>$the_event the event name, 1=>event parameters
 * @return string the php code corresponding to the function content
 */
function jtpl_cfunction_common_hookinclude($compiler, $param=array()) {
    if(!$compiler->trusted) {
        $compiler->doError1('errors.tplplugin.untrusted.not.available','hookinclude');
        return '';
    }
    if(count($param) == 1 ||count($param) == 2){
        return '
        $hookincevents = jEvent::notify('.$param[0].','.$param[1].')->getResponse();
        foreach ($hookincevents as $hookincevent) $t->display($hookincevent);';
    }else{
        $compiler->doError2('errors.tplplugin.cfunction.bad.argument.number','hookinclude','1-2');
        return '';
    }
}
