<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008-2011 FoxMaSk
* @link      https://havefnubb.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
 * Zone to Handle form of the SearchEngine
 */
class hfnusearchZone extends jZone {
    /**
     *@var string $_tplname the template name used by the zone
     */
    protected $_tplname='zone.hfnusearch';
    /**
     * function to manage data before assigning to the template of its zone
     */
    protected function _prepareTpl(){
        $url = jUrl::get('hfnusearch~default:queryajax');

        $javascript =
        "<script type=\"text/javascript\">"."\n".
        "//<![CDATA["."\n".
        "$().ready(function() {"."\n".
        "\t"."$(\"#hfnu_q\").autocomplete('".$url."', {"."\n".
                "\t\t"."width: 300,"."\n".
                "\t\t"."multiple: true,"."\n".
                "\t\t"."matchContains: true,"."\n".
                //"\t\t"."formatItem: formatItem,"."\n".
                //"\t\t"."formatResult: formatResult"."\n".
        "\t"."});"."\n".
        "});"."\n".
        "//]]>"."\n".
        "</script>"."\n";

        $this->_tpl->assign('javascript',$javascript);
    }
}
