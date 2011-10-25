<?php
/**
* @package     havefnubb
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * function that store the activity of the members presence
 */
function jtpl_function_html_iamhere($tpl) {
    // $tpl->_templateName contains : module~template.name
    if (jAuth::isConnected()) {
        $rec = jDao::get('iamhere~pages')->getByTemplateName($tpl->_templateName);
        //is this template is in the database ?
        // yes
        if ($rec !== false) {
            $dao = jDao::get('iamhere~pages_visitors');
            // does this member already somewhere on the website ?
            $alreadyHere = $dao->getByLogin(jAuth::getUserSession()->login);
            // no , let add him
            if ($alreadyHere === false) {
                $record = jDao::createRecord('iamhere~pages_visitors');
                $record->id_pages = $rec->id_pages;
                $record->login = jAuth::getUserSession()->login;
                $dao->insert($record);
            }
            // yes, update the page where he is
            else {
                jDao::get('iamhere~pages_visitors')->updateMyPage(jAuth::getUserSession()->login,
                                                                  $rec->id_pages
                                                                  );
            }
        }
        // no, do nothing until the admin has added this one to the database
    }
}
