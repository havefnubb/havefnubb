<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

/**
 * Configuration file for TCPDF.
 * @author Nicola Asuni
 * @copyright 2004-2009 Nicola Asuni - Tecnick.com S.r.l (www.tecnick.com) Via Della Pace, 11 - 09044 - Quartucciu (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @package com.tecnick.tcpdf
 * @version 4.0.014
 * @link http://tcpdf.sourceforge.net
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @since 2004-10-27
 */
if(!defined('K_TCPDF_EXTERNAL_CONFIG')){
	if((!isset($_SERVER['DOCUMENT_ROOT']))OR(empty($_SERVER['DOCUMENT_ROOT']))){
		if(isset($_SERVER['SCRIPT_FILENAME'])){
			$_SERVER['DOCUMENT_ROOT']=str_replace('\\','/',substr($_SERVER['SCRIPT_FILENAME'],0,0-strlen($_SERVER['PHP_SELF'])));
		}elseif(isset($_SERVER['PATH_TRANSLATED'])){
			$_SERVER['DOCUMENT_ROOT']=str_replace('\\','/',substr(str_replace('\\\\','\\',$_SERVER['PATH_TRANSLATED']),0,0-strlen($_SERVER['PHP_SELF'])));
		}else{
			$_SERVER['DOCUMENT_ROOT']='/var/www';
		}
	}
	$k_path_main=str_replace('\\','/',realpath(substr(dirname(__FILE__),0,0-strlen('config'))));
	if(substr($k_path_main,-1)!='/'){
		$k_path_main.='/';
	}
	define('K_PATH_MAIN',$k_path_main);
	if(isset($_SERVER['HTTP_HOST'])AND(!empty($_SERVER['HTTP_HOST']))){
		if(isset($_SERVER['HTTPS'])AND(!empty($_SERVER['HTTPS']))AND strtolower($_SERVER['HTTPS'])!='off'){
			$k_path_url='https://';
		}else{
			$k_path_url='http://';
		}
		$k_path_url.=$_SERVER['HTTP_HOST'];
		$k_path_url.=str_replace('\\','/',substr($_SERVER['PHP_SELF'],0,-24));
	}
	define('K_PATH_URL',$k_path_url);
	define('K_PATH_FONTS',K_PATH_MAIN.'fonts/');
	define('K_PATH_CACHE',K_PATH_MAIN.'cache/');
	define('K_PATH_URL_CACHE',K_PATH_URL.'cache/');
	define('K_PATH_IMAGES',K_PATH_MAIN.'images/');
	define('K_BLANK_IMAGE',K_PATH_IMAGES.'_blank.png');
	define('PDF_PAGE_FORMAT','A4');
	define('PDF_PAGE_ORIENTATION','P');
	define('PDF_CREATOR','TCPDF');
	define('PDF_AUTHOR','TCPDF');
	define('PDF_HEADER_TITLE','TCPDF Example');
	define('PDF_HEADER_STRING',"by Nicola Asuni - Tecnick.com\nwww.tcpdf.org");
	define('PDF_HEADER_LOGO','tcpdf_logo.jpg');
	define('PDF_HEADER_LOGO_WIDTH',30);
	define('PDF_UNIT','mm');
	define('PDF_MARGIN_HEADER',5);
	define('PDF_MARGIN_FOOTER',10);
	define('PDF_MARGIN_TOP',27);
	define('PDF_MARGIN_BOTTOM',25);
	define('PDF_MARGIN_LEFT',15);
	define('PDF_MARGIN_RIGHT',15);
	define('PDF_FONT_NAME_MAIN','helvetica');
	define('PDF_FONT_SIZE_MAIN',10);
	define('PDF_FONT_NAME_DATA','helvetica');
	define('PDF_FONT_SIZE_DATA',8);
	define('PDF_IMAGE_SCALE_RATIO',4);
	define('HEAD_MAGNIFICATION',1.1);
	define('K_CELL_HEIGHT_RATIO',1.25);
	define('K_TITLE_MAGNIFICATION',1.3);
	define('K_SMALL_RATIO',2/3);
}
?>

