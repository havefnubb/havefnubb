<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author      Bastien Jaillot
* @contributor Dominique Papin, Lepeltier kévin (the author of the original plugin)
* @contributor geekbay, Brunto, Laurent Jouanneau
* @copyright   2007-2008 Lepeltier kévin, 2008 Dominique Papin, 2008 Bastien Jaillot, 2009 geekbay, 2010 Brunto, 2011 Laurent Jouanneau
* @link       http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
class jImageModifier{
	static protected $transformParams=array('width','height','maxwidth','maxheight','zoom','alignh',
											'alignv','ext','quality','shadow','scolor','sopacity','sblur',
											'soffset','sangle','background','omo');
	static protected $attributeParams=array('alt','class','id','style','longdesc','name','ismap','usemap',
											'title','dir','lang','onclick','ondblclick','onmousedown',
											'onmouseup','onmouseover','onmousemove','onmouseout','onkeypress',
											'onkeydown','onkeyup','width','height');
	static function get($src,$params=array(),$sendCachePath=true,$config=null){
		global $gJConfig;
		$basePath=$gJConfig->urlengine['basePath'];
		if(strpos($src,$basePath)===0){
			$src=substr($src,strlen($basePath));
		}
		if(empty($params['ext'])){
			$path_parts=pathinfo($src);
			if(isset($path_parts['extension']))
				$ext=strtolower($path_parts['extension']);
		}else{
			$ext=strtolower($params['ext']);
		}
		$chaine=$src;
		foreach($params as $key=>$value){
			if(in_array($key,jImageModifier::$transformParams)){
				$chaine.=$key.$value;
			}
			if(in_array($key,jImageModifier::$attributeParams)){
				$att[$key]=$value;
			}
		}
		$cacheName=md5($chaine).'.'.$ext;
		global $gJConfig;
		list($srcPath,$srcUri,$cachePath,$cacheUri)=self::computeUrlFilePath($config);
		$pendingTransforms=($chaine!==$src);
		if($pendingTransforms&&is_file($srcPath.$src)&&!is_file($cachePath.$cacheName)){
			self::transformAndCache($srcPath.$src,$cachePath,$cacheName,$params);
		}
		if(!is_file($cachePath.$cacheName)){
			$att['src']=$srcUri.$src;
			$att['style']=empty($att['style'])?'':$att['style'];
			if(!empty($params['width']))$att['style'].='width:'.$params['width'].'px;';
			else if(!empty($params['maxwidth']))$att['style'].='width:'.$params['maxwidth'].'px;';
			if(!empty($params['height']))$att['style'].='height:'.$params['height'].'px;';
			else if(!empty($params['maxheight']))$att['style'].='height:'.$params['maxheight'].'px;';
		}else{
			$att['src']=$cacheUri.$cacheName;
		}
		if($sendCachePath)
			$att['cache_path']=$cachePath.$cacheName;
		return $att;
	}
	static public function computeUrlFilePath($config=null){
		global $gJConfig;
		$basePath=$gJConfig->urlengine['basePath'];
		if(!$config)
			$config=& $gJConfig->imagemodifier;
		if($config['src_url']&&$config['src_path']){
			$srcUri=$config['src_url'];
			if($srcUri[0]!='/'&&strpos($srcUri,'http:')!==0)
				$srcUri=$basePath.$srcUri;
			$srcPath=str_replace(array('www:','app:'),
									array(JELIX_APP_WWW_PATH,JELIX_APP_PATH),
									$config['src_path']);
		}
		else{
			$srcUri=$GLOBALS['gJCoord']->request->getServerURI().$basePath;
			$srcPath=JELIX_APP_WWW_PATH;
		}
		if($config['cache_path']&&$config['cache_url']){
			$cacheUri=$config['cache_url'];
			if($cacheUri[0]!='/'&&strpos($cacheUri,'http:')!==0)
				$cacheUri=$basePath.$cacheUri;
			$cachePath=str_replace(array('www:','app:'),
									array(JELIX_APP_WWW_PATH,JELIX_APP_PATH),
									$config['cache_path']);
		}
		else{
			$cachePath=JELIX_APP_WWW_PATH.'cache/images/';
			$cacheUri=$GLOBALS['gJCoord']->request->getServerURI().$basePath.'cache/images/';
		}
		return array($srcPath,$srcUri,$cachePath,$cacheUri);
	}
	static protected $mimes=array('gif'=>'image/gif','png'=>'image/png',
						'jpeg'=>'image/jpeg','jpg'=>'image/jpeg','jpe'=>'image/jpeg',
						'xpm'=>'image/x-xpixmap','xbm'=>'image/x-xbitmap','wbmp'=>'image/vnd.wap.wbmp');
	static protected function transformAndCache($srcFs,$cachePath,$cacheName,$params){
		$path_parts=pathinfo($srcFs);
		$mimeType=self::$mimes[strtolower($path_parts['extension'])];
		$quality=(!empty($params['quality']))?  $params['quality'] : 100;
		switch($mimeType){
			case 'image/gif'			: $image=imagecreatefromgif($srcFs);break;
			case 'image/jpeg'			: $image=imagecreatefromjpeg($srcFs);break;
			case 'image/png'			: $image=imagecreatefrompng($srcFs);break;
			case 'image/vnd.wap.wbmp'	: $image=imagecreatefromwbmp($srcFs);break;
			case 'image/image/x-xbitmap' : $image=imagecreatefromxbm($srcFs);break;
			case 'image/x-xpixmap'		: $image=imagecreatefromxpm($srcFs);break;
			default					: return;
		}
		if(!empty($params['maxwidth'])&&!empty($params['maxheight'])){
			$origWidth=imagesx($image);
			$origHeight=imagesy($image);
			$constWidth=$params['maxwidth'];
			$constHeight=$params['maxheight'];
			$ratio=imagesx($image)/imagesy($image);
			if($origWidth < $constWidth&&$origHeight < $constHeight){
				$params['width']=$origWidth;
				$params['height']=$origHeight;
			}else{
				$ratioHeight=$constWidth/$ratio;
				$ratioWidth=$constHeight*$ratio;
				if($ratioWidth > $constWidth){
					$constHeight=$ratioHeight;
				}
				else if($ratioHeight > $constHeight){
					$constWidth=$ratioWidth;
				}
				$params['width']=$constWidth;
				$params['height']=$constHeight;
			}
		}
		if(!empty($params['width'])||!empty($params['height'])){
			$ancienimage=$image;
			$resampleheight=imagesy($ancienimage);
			$resamplewidth=imagesx($ancienimage);
			$posx=0;
			$posy=0;
			if(empty($params['width'])){
				$finalheight=$params['height'];
				$finalwidth=$finalheight*imagesx($ancienimage)/imagesy($ancienimage);
			}else if(empty($params['height'])){
				$finalwidth=$params['width'];
				$finalheight=$finalwidth*imagesy($ancienimage)/imagesx($ancienimage);
			}else{
				$finalwidth=$params['width'];
				$finalheight=$params['height'];
				if(!empty($params['omo'])&&$params['omo']=='true'){
					if($params['width']>=$params['height']){
						$resampleheight=($resamplewidth*$params['height'])/$params['width'];
					}else{
						$resamplewidth=($resampleheight*$params['width'])/$params['height'];
					}
				}
			}
			if(!empty($params['zoom'])){
				$resampleheight/=100/$params['zoom'];
				$resamplewidth/=100/$params['zoom'];
			}
			$posx=imagesx($ancienimage)/2 -$resamplewidth/2;
			$posy=imagesy($ancienimage)/2 -$resampleheight/2;
			if(!empty($params['alignh'])){
				if($params['alignh']=='left')$posx=0;
				else if($params['alignh']=='right')$posx=-($resamplewidth - imagesx($ancienimage));
				else if($params['alignh']!='center')$posx=-$params['alignh'];
			}
			if(!empty($params['alignv'])){
				if($params['alignv']=='top')$posy=0;
				else if($params['alignv']=='bottom')$posy=-($resampleheight - imagesy($ancienimage));
				else if($params['alignv']!='center')$posy=-$params['alignv'];
			}
			$image=imagecreatetruecolor($finalwidth,$finalheight);
			imagesavealpha($image,true);
			$tp=imagecolorallocatealpha($image,0,0,0,127);
			imagefill($image,0,0,$tp);
			imagecopyresampled($image,$ancienimage,0,0,$posx,$posy,imagesx($image),imagesy($image),$resamplewidth,$resampleheight);
		}
		if(!empty($params['shadow']))
			$image=self::createShadow($image,$params);
		if(!empty($params['background'])){
			$params['background']=str_replace('#','',$params['background']);
			$rgb=array(0,0,0);
			for($x=0;$x<3;$x++)$rgb[$x]=hexdec(substr($params['background'],(2*$x),2));
			$fond=imagecreatetruecolor(imagesx($image),imagesy($image));
			imagefill($fond,0,0,imagecolorallocate($fond,$rgb[0],$rgb[1],$rgb[2]));
			imagecopy($fond,$image,0,0,0,0,imagesx($image),imagesy($image));
			$image=$fond;
		}
		jFile::createDir($cachePath);
		switch($mimeType){
			case 'image/gif'  : imagegif($image,$cachePath.$cacheName);break;
			case 'image/jpeg' : imagejpeg($image,$cachePath.$cacheName,$quality);break;
			default			: imagepng($image,$cachePath.$cacheName);
		}
		@imagedestroy($image);
	}
	static protected function createShadow($image,$params){
		$leng=isset($params['soffset'])?$params['soffset']:10;
		$angle=isset($params['sangle'])?$params['sangle']:135;
		$flou=isset($params['sblur'])?$params['sblur']:10;
		$opac=isset($params['sopacity'])?$params['sopacity']:20;
		$color=isset($params['scolor'])?$params['scolor']:'#000000';
		$color=str_replace('#','',$color);
		$rgb=array(0,0,0);
		if(strlen($color)==6)
			for($x=0;$x<3;$x++)
				$rgb[$x]=hexdec(substr($color,(2*$x),2));
		else if(strlen($color)==3)
			for($x=0;$x<3;$x++)
				$rgb[$x]=hexdec(substr($color,(2*$x),1));
		$coeffs=array(array(1),
						array(1,1),
						array(1,2,1),
						array(1,3,3,1),
						array(1,4,6,4,1),
						array(1,5,10,10,5,1),
						array(1,6,15,20,15,6,1),
						array(1,7,21,35,35,21,7,1),
						array(1,8,28,56,70,56,28,8,1),
						array(1,9,36,84,126,126,84,36,9,1),
						array(1,10,45,120,210,252,210,120,45,10,1),
						array(1,11,55,165,330,462,462,330,165,55,11,1));
		$sum=pow(2,$flou);
		$demi=$flou/2;
		$temp1=imagecreatetruecolor(imagesx($image)+$flou,imagesy($image)+$flou);
		imagesavealpha($temp1,true);
		$tp=imagecolorallocatealpha($temp1,0,0,0,127);
		imagefill($temp1,0,0,$tp);
		for($i=0;$i < imagesx($temp1);$i++)
		for($j=0;$j < imagesy($temp1);$j++){
			$ig=$i-$demi;$jg=$j-$demi;$suma=0;
			for($k=0;$k<=$flou;$k++){
				$ik=$ig-$demi+$k;
				if($jg<0||$jg>imagesy($temp1)-$flou-1)$alpha=127;
				else if($ik<0||$ik>imagesx($temp1)-$flou-1)$alpha=127;
				else $alpha=(imagecolorat($image,$ik,$jg)& 0x7F000000)>>24;
				$suma+=$alpha*$coeffs[$flou][$k];
			}
			$c=imagecolorallocatealpha($temp1,0,0,0,$suma/$sum);
			imagesetpixel($temp1,$i,$j,$c);
		}
		$x=cos(deg2rad($angle))*$leng;
		$y=sin(deg2rad($angle))*$leng;
		$temp2=imagecreatetruecolor(imagesx($temp1)+abs($x),imagesy($temp1)+abs($y));
		imagesavealpha($temp2,true);
		$tp=imagecolorallocatealpha($temp2,0,0,0,127);
		imagefill($temp2,0,0,$tp);
		$x1=$x<0?0:$x;
		$y1=$y<0?0:$y;
		for($i=0;$i < imagesx($temp1);$i++)
		for($j=0;$j < imagesy($temp1);$j++){
			$suma=0;
			for($k=0;$k<=$flou;$k++){
				$jk=$j-$demi+$k;
				if($jk<0||$jk>imagesy($temp1)-1)$alpha=127;
				else $alpha=(imagecolorat($temp1,$i,$jk)& 0x7F000000)>>24;
				$suma+=$alpha*$coeffs[$flou][$k];
			}
			$alpha=127-((127-($suma/$sum))/(100/$opac));
			$c=imagecolorallocatealpha($temp2,$rgb[0],$rgb[1],$rgb[2],$alpha < 0 ? 0 : $alpha > 127 ? 127 : $alpha);
			imagesetpixel($temp2,$i+$x1,$j+$y1,$c);
		}
		imagedestroy($temp1);
		$x=$x>0?0:$x;
		$y=$y>0?0:$y;
		imagecopy($temp2,$image,$demi-$x,$demi-$y,0,0,imagesx($image),imagesy($image));
		return $temp2;
	}
}
