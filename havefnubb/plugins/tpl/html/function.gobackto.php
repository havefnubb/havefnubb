<?php
/**
 * @package     havefnubb
 * @subpackage  jtpl_plugin
 * @author      Olivier Demah
  * @copyright  2009 Olivier Demah
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
/**
 * function gobackto plugin : display a link that leads the user to the previous visited page
 * Adds a link to go back to the previous visited page
 *
 * {gobackto 'string to display'}
 * @param jTpl $tpl template engine
 * @param string $msg string displayed before the name of the visited page formated like jLocale expects to receive it
 */
function jtpl_function_html_gobackto($tpl,$msg='') {
    global $gJCoord;
    $plugin = $gJCoord->getPlugin('history', true);
    if($plugin === null){
        return;
    }

    $config = & $plugin->config;
    if (!isset($config['session_name'])
        || $config['session_name'] == ''){
        $config['session_name'] = 'HISTORY';
    }

    if( !isset($_SESSION[$config['session_name']]) ) {
        return;
    }

    $leng = count($_SESSION[$config['session_name']]);
    $page = $_SESSION[$config['session_name']][$leng -1];
    echo '<a href="'.jUrl::get($page['action'], $page['params'], jUrl::XMLSTRING).'" '.($page['title']!=''?'title="'.$page['title'].'"':'').'>';
    if ($msg != '') echo jLocale::get($msg) . ' ' ;
    echo $_SESSION[$config['session_name']][$leng - 1]['label'];
    echo '</a>';
}
