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
function jtpl_function_html_social_networks($tpl, $params) {
	$socialNetwork	=  new jIniFileModifier(JELIX_APP_CONFIG_PATH.'social.network.ini.php');
	echo '<div class="social-network">';
	if ( $socialNetwork->getValue('twitter')) {
		social_twitter($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
	}
	if ( $socialNetwork->getValue('diff')) {
		social_digg($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
	}
	if ( $socialNetwork->getValue('delicious')) {
		social_delicious($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
	}
	if ( $socialNetwork->getValue('facebook')) {	
		social_facebook($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
	}
	if ( $socialNetwork->getValue('reddit')) {
		social_reddit($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
	}
	if ( $socialNetwork->getValue('netvibes')) {
		social_netvibes($params['imgpath'],$params['jurl'], $params['jurlparams'], $params['title']);
	}
	echo "</div>";
}

function social_delicious($imgPath, $jurl, $jurlParams, $title) {
	$params = array('width'=>32,'height'=>32,'alt'=>'delicious');
	$deliciousUrl = 'http://del.icio.us/post?';
	$deliciousUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$deliciousUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$deliciousUrl.'" title="delicious" >'.make_image($imgPath.'/Delicious.png',$params).'</a>';
}

function social_facebook($imgPath, $jurl, $jurlParams, $title) {
	$params = array('width'=>32,'height'=>32,'alt'=>'facebook');
	$facebookUrl = 'http://www.facebook.com/sharer.php?';
	$facebookUrl .= 'u=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	echo '<a href="'.$facebookUrl.'" title="facebook" >'.make_image($imgPath.'/Facebook.png',$params).'</a>';
}

function social_reddit($imgPath, $jurl, $jurlParams, $title) {
	$params = array('width'=>32,'height'=>32,'alt'=>'reddit');
	$redditUrl = 'http://reddit.com/submit?';
	$redditUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$redditUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$redditUrl.'" title="reddit" >'.make_image($imgPath.'/Reddit.png',$params).'</a>';
}

function social_twitter($imgPath, $jurl, $jurlParams, $title) {
	$params = array('width'=>32,'height'=>32,'alt'=>'twitter');
	$twitterUrl = 'http://twitter.com/timeline/home?status=';
	$twitterUrl .= urlencode('I Read '.$title.' ').'http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	echo '<a href="'.$twitterUrl.'" title="twitter" >'.make_image($imgPath.'/Twitter.png',$params).'</a>';
}

function social_digg($imgPath, $jurl, $jurlParams, $title) {
	$params = array('width'=>32,'height'=>32,'alt'=>'digg');
	$diggUrl = 'http://digg.com/submit?phase=2&amp;';
	$diggUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$diggUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$diggUrl.'" title="digg" >'.make_image($imgPath.'/Digg.png',$params).'</a>';
}

function social_netvibes($imgPath, $jurl, $jurlParams, $title) {
	$params = array('width'=>32,'height'=>32,'alt'=>'netvibes');
	$netvibesUrl = 'http://www.netvibes.com/share?';
	$netvibesUrl .= 'url=http://'.$_SERVER['SERVER_NAME'].urlencode(jUrl::get($jurl, $jurlParams));
	$netvibesUrl .= '&amp;title='.urlencode($title);
	echo '<a href="'.$netvibesUrl.'" title="netvibes" >'.make_image($imgPath.'/Netvibes.png',$params).'</a>';
}



function make_image($src,$params) {

	$att = jImageModifier::get($src, $params, false);

    // alt attribute is required (xhtml/html4 spec)
    if (!array_key_exists('alt',$att))
        $att['alt']='';

    // generating hmtl tag img
    $img = '<img';
    foreach( $att as $key => $val ) {
        if( !empty($val) )
            $img .=  ' '.$key.'="'.htmlspecialchars($val).'"';
    }
    $img .= ' />';
	return $img;
}