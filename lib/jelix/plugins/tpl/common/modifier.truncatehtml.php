<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage jtpl_plugin
 * @author		Didier Huguet
 * @copyright 2008 Didier Huguet
 * @link http://snipplr.com/view.php?codeview&id=3618
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
function jtpl_modifier_common_truncatehtml($string,$nbChar=200,$etcPattern='...')
{
	if($nbChar==0)
		return '';
	$string=preg_replace('#<!--(.+)-->#isU','',$string);
	$string=str_replace('<!--','&lteq;!--',$string);
	if(strlen($string)< $nbChar)
		return $string;
	$html=preg_replace('/\s+?(\S+)?$/','',substr($string,0,$nbChar+1));
	$html=substr($html,0,$nbChar);
	$html=strrpos($html,"<")> strrpos($html,">")? rtrim(substr($string,0,strrpos($html,"<"))). $etcPattern : rtrim($html). $etcPattern;
	$openedtags=array();
	preg_match_all("#<([a-z]+)( .*)?(?!/)>#iU",$html,$result);
		foreach($result[2] as $key=>$value){
		if(!preg_match('#/$#',$value))
			$openedtags[]=$result[1][$key];
		}
	preg_match_all("#</([a-z]+)>#iU",$html,$result);
	$closedtags=$result[1];
	$len_opened=count($openedtags);
	if(count($closedtags)==$len_opened){
		return $html;
	}
	$openedtags=array_reverse($openedtags);
	for($i=0;$i < $len_opened;$i++){
		if(!in_array($openedtags[$i],$closedtags)){
			$html.="</" . $openedtags[$i] . ">";
		}else{
			unset($closedtags[array_search($openedtags[$i],$closedtags)]);
		}
	}
	return $html;
}
