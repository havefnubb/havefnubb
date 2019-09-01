<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Laurent Jouanneau
* @contributor Julien Issler
* @copyright   2006-2012 Laurent Jouanneau
* @copyright   2008 Julien Issler
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jFilter{
	private function _construct(){}
	static public function usePhpFilter(){
		return true;
	}
	static public function isInt($val,$min=null,$max=null){
		if(filter_var($val,FILTER_VALIDATE_INT)===false)return false;
		if($min!==null&&intval($val)< $min)return false;
		if($max!==null&&intval($val)> $max)return false;
		return true;
	}
	static public function isHexInt($val,$min=null,$max=null){
		if(filter_var($val,FILTER_VALIDATE_INT,FILTER_FLAG_ALLOW_HEX)===false)return false;
		if($min!==null&&intval($val,16)< $min)return false;
		if($max!==null&&intval($val,16)> $max)return false;
		return true;
	}
	static public function isBool($val){
		return in_array($val,array('true','false','1','0','TRUE','FALSE','on','off'));
	}
	static public function isFloat($val,$min=null,$max=null){
		if(filter_var($val,FILTER_VALIDATE_FLOAT)===false)return false;
		if($min!==null&&floatval($val)< $min)return false;
		if($max!==null&&floatval($val)> $max)return false;
		return true;
	}
	static public function isUrl($url,$schemeRequired=false,
							$hostRequired=false,$pathRequired=false,
							$queryRequired=false){
		$res=@parse_url($url);
		if($res===false)return false;
		if($schemeRequired&&!isset($res['scheme']))return false;
		if($hostRequired&&!isset($res['host']))return false;
		if($pathRequired&&!isset($res['path']))return false;
		if($queryRequired&&!isset($res['query']))return false;
		return true;
	}
	static public function isIPv4($val){
		return filter_var($val,FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==false;
	}
	static public function isIPv6($val){
		return filter_var($val,FILTER_VALIDATE_IP,FILTER_FLAG_IPV6)!==false;
	}
	static public function isEmail($val){
		return filter_var($val,FILTER_VALIDATE_EMAIL)!==false;
	}
	const INVALID_HTML=1;
	const BAD_SAVE_HTML=2;
	static public function cleanHtml($html,$isXhtml=false){
		$charset=jApp::config()->charset;
		$doc=new DOMDocument('1.0',$charset);
		$foot='</body></html>';
		if(strpos($html,"\r")!==false){
			$html=str_replace("\r\n","\n",$html);
			$html=str_replace("\r","\n",$html);
		}
			$head='<html><head><meta http-equiv="Content-Type" content="text/html; charset='.$charset.'"/><title></title></head><body>';
			if(!@$doc->loadHTML($head.$html.$foot)){
				return jFilter::INVALID_HTML;
			}
		$items=$doc->getElementsByTagName('script');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('applet');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('base');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('basefont');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('frame');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('frameset');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('noframes');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('isindex');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('iframe');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		$items=$doc->getElementsByTagName('noscript');
		foreach($items as $item){
			$item->parentNode->removeChild($item);
		}
		self::cleanAttr($doc->getElementsByTagName('body')->item(0));
		$doc->formatOutput=true;
		if($isXhtml){
		$result=$doc->saveXML();
		}
		else{
		$result=$doc->saveHTML();
		}
		if(!preg_match('!<body>(.*)</body>!smU',$result,$m))
			return jFilter::BAD_SAVE_HTML;
		return $m[1];
	}
	static protected function cleanAttr($node){
		$child=$node->firstChild;
		while($child){
			if($child->nodeType==XML_ELEMENT_NODE){
				$attrs=$child->attributes;
				foreach($attrs as $attr){
					if(strtolower(substr($attr->localName,0,2))=='on')
						$child->removeAttributeNode($attr);
					else if(strtolower($attr->localName)=='href'){
						if(preg_match("/^([a-z\-]+)\:.*/i",trim($attr->nodeValue),$m)){
							if(!preg_match('/^http|https|mailto|ftp|irc|about|news/i',$m[1]))
								$child->removeAttributeNode($attr);
						}
					}
				}
				self::cleanAttr($child);
			}
			$child=$child->nextSibling;
		}
	}
}
