<?php
/**
* @package   havefnubb
* @subpackage hfnusearch
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://havefnubb.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class hfnusearchZone extends jZone {
    protected $_tplname='zone.hfnusearch';
    
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