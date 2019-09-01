<?php
/**
* @package     havefnubb
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * function that display the nickname of the admin that censored the post
 */
function jtpl_function_html_censored_by($tpl, $id) {
    $user = jDao::get('havefnubb~member')->getById($id);
    $str = jLocale::get('havefnubb~main.censored.by');
    if ($user->nickname != '')
        echo $str . ' ' . $user->nickname;
    else
        echo $str  . ' ' . $user->login ;
}
