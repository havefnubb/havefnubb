<?php
/**
 * @package     jelix
 * @subpackage  jtpl_plugin
 * @author      Olivier Demah
 * @copyright  2009
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * share_delicious plugin :  display a picture of delicous + generate an url to share the current content on delicious
 *
 * @param jTpl $tpl template engine
 * @param string $network on which we want to share
 * @param array  $params contains :
 * imgpath = path to image of the social network
 * jurl = url in jurl format
 * jurlparams = params of jUrl
 * title = title to share
 */
function jtpl_function_html_social_networks($tpl, $network, $params) {
	echo '<div class="social-network">';
	switch ($network) {
		case 'twitter':			
			social_twitter($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			break;				
		case 'digg':			
			social_digg($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			break;		
		case 'delicious':			
			social_delicious($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			break;
		case 'facebook':			
			social_facebook($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			break;
		case 'reddit':			
			social_reddit($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			break;
		case 'netvibes':
			social_netvibes($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			break;
		case 'all' :
			social_twitter($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			social_delicious($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			social_facebook($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			social_reddit($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			social_digg($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			social_netvibes($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
			break;
	}
	echo "</div>";
}

function social_delicious($imgPath, $jurl, $jurlParams, $title) {
	$deliciousUrl = 'http://del.icio.us/post?';
	$deliciousUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$deliciousUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$deliciousUrl.'" title="delicious" >'.make_image($imgPath.'/icontexto-user-web20-delicious.png').'</a>';
}

function social_facebook($imgPath, $jurl, $jurlParams, $title) {
	$facebookUrl = 'http://www.facebook.com/sharer.php?';
	$facebookUrl .= 'u=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	echo '<a href="'.$facebookUrl.'" title="facebook" >'.make_image($imgPath.'/icontexto-user-web20-facebook.png').'</a>';
}

function social_reddit($imgPath, $jurl, $jurlParams, $title) {
	$redditUrl = 'http://reddit.com/submit?';
	$redditUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$redditUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$redditUrl.'" title="reddit" >'.make_image($imgPath.'/icontexto-user-web20-reddit.png').'</a>';
}

function social_twitter($imgPath, $jurl, $jurlParams, $title) {
	$twitterUrl = 'http://twitter.com/timeline/home?';
	$twitterUrl .= urlencode('status=view '.$title.' http://'.$_SERVER['SERVER_NAME'].jUrl::get($jurl, $jurlParams));
	echo '<a href="'.$twitterUrl.'" title="twitter" >'.make_image($imgPath.'/icontexto-user-web20-twitter.png').'</a>';
}

function social_digg($imgPath, $jurl, $jurlParams, $title) {
	$diggUrl = 'http://digg.com/submit?phase=2&amp;url=';
	$diggUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$diggUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$diggUrl.'" title="digg" >'.make_image($imgPath.'/icontexto-user-web20-digg.png').'</a>';
}

function social_netvibes($imgPath, $jurl, $jurlParams, $title) {
	$netvibesUrl = 'http://www.netvibes.com/share?';
	$netvibesUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$netvibesUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$netvibesUrl.'" title="netvibes" >'.make_image($imgPath.'/icontexto-user-web20-netvibes.png').'</a>';
}



function make_image($src) {

$att = jImageModifier::get($src, $params=array(), false);

    // alt attribute is required (xhtml/html4 spec)
    if (!array_key_exists('alt',$att))
        $att['alt']='';

    // generating hmtl tag img
    $img = '<img';
    foreach( $att as $key => $val ) {
        if( !empty($val) )
            $img .=  ' '.$key.'="'.htmlspecialchars($val).'"';
    }
    $img .= '/>';
	return $img;
}