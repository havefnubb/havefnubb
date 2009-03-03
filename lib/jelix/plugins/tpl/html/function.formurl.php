<?php
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Loic Mathaud
* @contributor Laurent Jouanneau
* @copyright  2005-2006 Loic Mathaud, 2008 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
 * function plugin :  write the url corresponding to the given jelix action
 *
 * @param jTpl $tpl template engine
 * @param string $selector selector action
 * @param array $params parameters for the url
 */
function jtpl_function_html_formurl($tpl, $selector, $params=array())
{
    $url = jUrl::get($selector, $params, 2); // retourne le jurl correspondant
    echo $url->getPath();
}

