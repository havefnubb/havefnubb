<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Lepeltier kévin
 * @contributor Dominique Papin
 * @copyright   2008 Lepeltier kévin
 * @copyright   2008 Dominique Papin
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_cfunction_html_swfbiscuit($tpl,$params){
	$sortie='
        $src = '.$params[0].';
        $options = '.$params[1].';
        $params = '.$params[2].';
        $flashvar = '.$params[3].';

        $att = \'\';
        $atts = array(\'id\'=>\'\', \'class\'=>\'\');
        $atts = array_intersect_key($options, $atts);
        foreach( $atts as $key => $val )
            if( !empty($val) )
                $att .= \' \'.$key.\'="\'.$val.\'"\';

        echo "\n".\'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="\'.$options[\'width\'].\'" height="\'.$options[\'height\'].\'"\'.$att.\'>\'."\n";
        echo "    ".\'<param name="movie" value="\'.$src.\'?\';
        if( count($flashvar) ) foreach($flashvar as $key => $val)
            echo \'&\'.$key.\'=\'.$val;
        echo \'" />\'."\n";
        if( count($params) ) foreach($params as $key => $val)
            echo "    ".\'<param name="\'.$key.\'" value="\'.$val.\'" />\'."\n";
        echo "    ".\'<embed \';
        if( count($params) ) foreach($params as $key => $val)
            echo $key.\'="\'.$val.\'" \';
        echo \' src="\'.$src.\'?\';
        if( count($flashvar) ) foreach($flashvar as $key => $val)
            echo \'&\'.$key.\'=\'.$val;
        echo \'" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="\'.$options[\'width\'].\'" height="\'.$options[\'height\'].\'"></embed>\'."\n";
        echo \'</object>\';
        ';
	return $sortie;
}
