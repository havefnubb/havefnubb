<?php
/**
* @package     jelix
* @subpackage  jtpl_plugin
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
// plugin that display the info about all your webserver + php + database versions
//    /!\ to be used in your backend  !
function jtpl_function_html_hfnutoolbar($tpl, $id, $path)
{
    $str = "<script type=\"text/javascript\">"."\n".
        "//<![CDATA["."\n".
        "if (document.getElementById) {"."\n".
        "  var tb = new hfnutoolBar(document.getElementById('".$id."'),'".$path."');"."\n".
        "  tb.btPre('".jLocale::get('hfnutoolbar.block.source.code')."');"."\n".
        "  tb.btList('".jLocale::get('hfnutoolbar.list')."');"."\n".
        "  tb.btBquote('".jLocale::get('hfnutoolbar.quote')."');  "."\n".
        "  tb.btStrong('".jLocale::get('hfnutoolbar.strong')."');"."\n".
        "  tb.btEm('".jLocale::get('hfnutoolbar.em')."');  "."\n".
        "  tb.btLink('".jLocale::get('hfnutoolbar.link')."','".jLocale::get('hfnutoolbar.enter.the.link.name')."','".jLocale::get('hfnutoolbar.enter.the.link.url')."');"."\n".
        "  tb.btPara('".jLocale::get('hfnutoolbar.para')."');"."\n".
        "  tb.btDef('".jLocale::get('hfnutoolbar.definition')."','Entrez le mot','Entrez sa définition');"."\n".
        "  tb.btFullLink('".jLocale::get('hfnutoolbar.link')."','".jLocale::get('hfnutoolbar.enter.the.link.name')."','".jLocale::get('hfnutoolbar.enter.the.link.url')."','".jLocale::get('hfnutoolbar.enter.the.link.lang')."');  "."\n".
        "  tb.btLineBreak('".jLocale::get('hfnutoolbar.breakline')."');"."\n".
        "  tb.btImgLink('".jLocale::get('hfnutoolbar.image')."',"."\n".
        "               '".jLocale::get('hfnutoolbar.enter.the.link.image')."',"."\n".
        "               '".jLocale::get('hfnutoolbar.enter.the.alt.image')."',"."\n".
        "               '".jLocale::get('hfnutoolbar.enter.the.position.image')."',"."\n".
        "               '".jLocale::get('hfnutoolbar.enter.the.position.image')."'"."\n".
        "               );  "."\n".
        "  tb.btCode('".jLocale::get('hfnutoolbar.source.code')."');"."\n".
        "  tb.btHr('".jLocale::get('hfnutoolbar.horiz.line')."');"."\n".
        "  tb.draw();"."\n".
        "}"."\n".
        "//]]>"."\n".
    "</script>";
    
    echo $str;

}