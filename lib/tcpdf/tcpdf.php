<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

/**
 * This is a PHP class for generating PDF documents without requiring external extensions.<br>
 * TCPDF project (http://www.tcpdf.org) was originally derived in 2002 from the Public Domain FPDF class by Olivier Plathey (http://www.fpdf.org), but now is almost entirely rewritten.<br>
 * <h3>TCPDF main features are:</h3>
 * <ul>
 * <li>no external libraries are required for the basic functions;</li>
 * <li>supports all ISO page formats;</li>
 * <li>supports UTF-8 Unicode and Right-To-Left languages;</li>
 * <li>supports document encryption;</li>
 * <li>includes methods to publish some XHTML code;</li>
 * <li>includes graphic (geometric) and transformation methods;</li>
 * <li>includes bookmarks;</li>
 * <li>includes Javascript and forms support;</li>
 * <li>includes a method to print various barcode formats;</li>
 * <li>supports TrueTypeUnicode, TrueType, Type1 and CID-0 fonts;</li>
 * <li>supports custom page formats, margins and units of measure;</li>
 * <li>includes methods for page header and footer management;</li>
 * <li>supports automatic page break;</li>
 * <li>supports automatic page numbering and page groups;</li>
 * <li>supports automatic line break and text justification;
 * <li>supports JPEG and PNG images whitout GD library and all images supported by GD: GD, GD2, GD2PART, GIF, JPEG, PNG, BMP, XBM, XPM;</li>
 * <li>supports stroke and clipping mode for text;</li>
 * <li>supports clipping masks;</li>
 * <li>supports Grayscale, RGB and CMYK colors and transparency;</li>
 * <li>supports links and annotations;</li>
 * <li>supports page compression (requires zlib extension);</li>
 * <li>supports PDF user's rights.</li>
 * </ul>
 * Tools to encode your unicode fonts are on fonts/utils directory.</p>
 * @package com.tecnick.tcpdf
 * @abstract Class for generating PDF files on-the-fly without requiring external extensions.
 * @author Nicola Asuni
 * @copyright 2004-2008 Nicola Asuni - Tecnick.com S.r.l (www.tecnick.com) Via Della Pace, 11 - 09044 - Quartucciu (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link http://www.tcpdf.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @version 4.3.006
 */
require_once(dirname(__FILE__).'/config/tcpdf_config.php');
require_once(dirname(__FILE__).'/unicode_data.php');
require_once(dirname(__FILE__).'/htmlcolors.php');
require_once(dirname(__FILE__).'/barcodes.php');
if(!class_exists('TCPDF', false)){
	define('PDF_PRODUCER','TCPDF 4.3.006 (http://www.tcpdf.org)');
	class TCPDF{
		protected $page;
		protected $n;
		protected $offsets;
		protected $buffer;
		protected $pages = array();
		protected $state;
		protected $compress;
		protected $CurOrientation;
		protected $pagedim = array();
		protected $k;
		protected $fwPt;
		protected $fhPt;
		protected $wPt;
		protected $hPt;
		protected $w;
		protected $h;
		protected $lMargin;
		protected $tMargin;
		protected $rMargin;
		protected $bMargin;
		protected $cMargin;
		protected $oldcMargin;
		protected $x;
		protected $y;
		protected $lasth;
		protected $LineWidth;
		protected $CoreFonts;
		protected $fonts = array();
		protected $FontFiles = array();
		protected $diffs = array();
		protected $images = array();
		protected $PageAnnots = array();
		protected $links = array();
		protected $FontFamily;
		protected $FontStyle;
		protected $FontAscent;
		protected $FontDescent;
		protected $underline;
		protected $CurrentFont;
		protected $FontSizePt;
		protected $FontSize;
		protected $DrawColor;
		protected $FillColor;
		protected $TextColor;
		protected $ColorFlag;
		protected $ws;
		protected $AutoPageBreak;
		protected $PageBreakTrigger;
		protected $InFooter = false;
		protected $ZoomMode;
		protected $LayoutMode;
		protected $title;
		protected $subject;
		protected $author;
		protected $keywords;
		protected $creator;
		protected $AliasNbPages;
		protected $img_rb_x;
		protected $img_rb_y;
		protected $imgscale = 1;
		protected $isunicode = false;
		protected $PDFVersion = '1.7';
		protected $header_margin;
		protected $footer_margin;
		protected $original_lMargin;
		protected $original_rMargin;
		protected $header_font;
		protected $footer_font;
		protected $l;
		protected $barcode = false;
		protected $print_header = true;
		protected $print_footer = true;
		protected $header_logo = '';
		protected $header_logo_width = 30;
		protected $header_title = '';
		protected $header_string = '';
		protected $default_table_columns = 4;
		protected $HREF;
		protected $fontlist = array();
		protected $fgcolor;
		protected $listordered = array();
		protected $listcount = array();
		protected $listnum = 0;
		protected $listindent;
		protected $bgcolor;
		protected $tempfontsize = 10;
		protected $lispacer = '';
		protected $encoding = 'UTF-8';
		protected $internal_encoding;
		protected $rtl = false;
		protected $tmprtl = false;
		protected $encrypted;
		protected $Uvalue;
		protected $Ovalue;
		protected $Pvalue;
		protected $enc_obj_id;
		protected $last_rc4_key;
		protected $last_rc4_key_c;
		protected $padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
		protected $encryption_key;
		protected $outlines = array();
		protected $OutlineRoot;
		protected $javascript = '';
		protected $n_js;
		protected $linethrough;
		protected $ur;
		protected $ur_document;
		protected $ur_annots;
		protected $ur_form;
		protected $ur_signature;
		protected $dpi = 72;
		protected $newpagegroup;
		protected $pagegroups;
		protected $currpagegroup;
		protected $visibility = 'all';
		protected $n_ocg_print;
		protected $n_ocg_view;
		protected $extgstates;
		protected $jpeg_quality;
		protected $cell_height_ratio = K_CELL_HEIGHT_RATIO;
		protected $viewer_preferences;
		protected $PageMode;
		protected $gradients = array();
		protected $intmrk = array();
		protected $footerpos = array();
		protected $footerlen = array();
		protected $newline = true;
		protected $endlinex = 0;
		protected $linestyleWidth = '';
		protected $linestyleCap = '0 J';
		protected $linestyleJoin = '0 j';
		protected $linestyleDash = '[] 0 d';
		protected $openMarkedContent = false;
		protected $htmlvspace = 0;
		protected $spot_colors = array();
		protected $lisymbol = '-';
		protected $epsmarker = 'x#!#EPS#!#x';
		protected $transfmatrix = array();
		protected $booklet = false;
		protected $feps = 0.001;
		protected $tagvspaces = array();
		protected $customlistindent = -1;
		protected $opencell = true;
		public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8'){
			if(function_exists('mb_internal_encoding') AND mb_internal_encoding()){
				$this->internal_encoding = mb_internal_encoding();
				mb_internal_encoding('ASCII');
			}
			$this->rtl = $this->l['a_meta_dir']=='rtl' ? true : false;
			$this->tmprtl = false;
			$this->_dochecks();
			$this->isunicode = $unicode;
			$this->page = 0;
			$this->pagedim = array();
			$this->n = 2;
			$this->buffer = '';
			$this->pages = array();
			$this->state = 0;
			$this->fonts = array();
			$this->FontFiles = array();
			$this->diffs = array();
			$this->images = array();
			$this->links = array();
			$this->gradients = array();
			$this->InFooter = false;
			$this->lasth = 0;
			$this->FontFamily = 'helvetica';
			$this->FontStyle = '';
			$this->FontSizePt = 12;
			$this->underline = false;
			$this->linethrough = false;
			$this->DrawColor = '0 G';
			$this->FillColor = '0 g';
			$this->TextColor = '0 g';
			$this->ColorFlag = false;
			$this->ws = 0;
			$this->encrypted = false;
			$this->last_rc4_key = '';
			$this->padding = "\x28\xBF\x4E\x5E\x4E\x75\x8A\x41\x64\x00\x4E\x56\xFF\xFA\x01\x08\x2E\x2E\x00\xB6\xD0\x68\x3E\x80\x2F\x0C\xA9\xFE\x64\x53\x69\x7A";
			$this->CoreFonts = array(
				'courier'=>'Courier',
				'courierB'=>'Courier-Bold',
				'courierI'=>'Courier-Oblique',
				'courierBI'=>'Courier-BoldOblique',
				'helvetica'=>'Helvetica',
				'helveticaB'=>'Helvetica-Bold',
				'helveticaI'=>'Helvetica-Oblique',
				'helveticaBI'=>'Helvetica-BoldOblique',
				'times'=>'Times-Roman',
				'timesB'=>'Times-Bold',
				'timesI'=>'Times-Italic',
				'timesBI'=>'Times-BoldItalic',
				'symbol'=>'Symbol',
				'zapfdingbats'=>'ZapfDingbats'
			);
			$this->setPageUnit($unit);
			$this->setPageFormat($format, $orientation);
			$margin = 28.35 / $this->k;
			$this->SetMargins($margin,$margin);
			$this->cMargin = $margin / 10;
			$this->LineWidth = 0.57 / $this->k;
			$this->linestyleWidth = sprintf('%.2f w',($this->LineWidth * $this->k));
			$this->linestyleCap = '0 J';
			$this->linestyleJoin = '0 j';
			$this->linestyleDash = '[] 0 d';
			$this->SetAutoPageBreak(true,(2 * $margin));
			$this->SetDisplayMode('fullwidth');
			$this->SetCompression(true);
			$this->PDFVersion = '1.7';
			$this->encoding = $encoding;
			$this->HREF = '';
			$this->getFontsList();
			$this->fgcolor = array('R' => 0, 'G' => 0, 'B' => 0);
			$this->bgcolor = array('R' => 255, 'G' => 255, 'B' => 255);
			$this->extgstates = array();
			$this->ur = false;
			$this->ur_document = '/FullSave';
			$this->ur_annots = '/Create/Delete/Modify/Copy/Import/Export';
			$this->ur_form = '/Add/Delete/FillIn/Import/Export/SubmitStandalone/SpawnTemplate';
			$this->ur_signature = '/Modify';
			$this->jpeg_quality = 75;
			$this->utf8Bidi(array(''));
		}
		public function __destruct(){
			if(isset($this->internal_encoding) AND !empty($this->internal_encoding)){
				mb_internal_encoding($this->internal_encoding);
			}
		}
		public function setPageUnit($unit){
			switch(strtolower($unit)){
				case 'pt':{
					$this->k = 1;
					break;
				}
				case 'mm':{
					$this->k = $this->dpi / 25.4;
					break;
				}
				case 'cm':{
					$this->k = $this->dpi / 2.54;
					break;
				}
				case 'in':{
					$this->k = $this->dpi;
					break;
				}
				default :{
					$this->Error('Incorrect unit: '.$unit);
					break;
				}
			}
			if(isset($this->CurOrientation)){
					$this->setPageOrientation($this->CurOrientation);
			}
		}
		public function setPageFormat($format, $orientation='P'){
			if(is_string($format)){
				switch(strtoupper($format)){
					case '4A0':{$format = array(4767.87,6740.79); break;}
					case '2A0':{$format = array(3370.39,4767.87); break;}
					case 'A0':{$format = array(2383.94,3370.39); break;}
					case 'A1':{$format = array(1683.78,2383.94); break;}
					case 'A2':{$format = array(1190.55,1683.78); break;}
					case 'A3':{$format = array(841.89,1190.55); break;}
					case 'A4': default:{$format = array(595.28,841.89); break;}
					case 'A5':{$format = array(419.53,595.28); break;}
					case 'A6':{$format = array(297.64,419.53); break;}
					case 'A7':{$format = array(209.76,297.64); break;}
					case 'A8':{$format = array(147.40,209.76); break;}
					case 'A9':{$format = array(104.88,147.40); break;}
					case 'A10':{$format = array(73.70,104.88); break;}
					case 'B0':{$format = array(2834.65,4008.19); break;}
					case 'B1':{$format = array(2004.09,2834.65); break;}
					case 'B2':{$format = array(1417.32,2004.09); break;}
					case 'B3':{$format = array(1000.63,1417.32); break;}
					case 'B4':{$format = array(708.66,1000.63); break;}
					case 'B5':{$format = array(498.90,708.66); break;}
					case 'B6':{$format = array(354.33,498.90); break;}
					case 'B7':{$format = array(249.45,354.33); break;}
					case 'B8':{$format = array(175.75,249.45); break;}
					case 'B9':{$format = array(124.72,175.75); break;}
					case 'B10':{$format = array(87.87,124.72); break;}
					case 'C0':{$format = array(2599.37,3676.54); break;}
					case 'C1':{$format = array(1836.85,2599.37); break;}
					case 'C2':{$format = array(1298.27,1836.85); break;}
					case 'C3':{$format = array(918.43,1298.27); break;}
					case 'C4':{$format = array(649.13,918.43); break;}
					case 'C5':{$format = array(459.21,649.13); break;}
					case 'C6':{$format = array(323.15,459.21); break;}
					case 'C7':{$format = array(229.61,323.15); break;}
					case 'C8':{$format = array(161.57,229.61); break;}
					case 'C9':{$format = array(113.39,161.57); break;}
					case 'C10':{$format = array(79.37,113.39); break;}
					case 'RA0':{$format = array(2437.80,3458.27); break;}
					case 'RA1':{$format = array(1729.13,2437.80); break;}
					case 'RA2':{$format = array(1218.90,1729.13); break;}
					case 'RA3':{$format = array(864.57,1218.90); break;}
					case 'RA4':{$format = array(609.45,864.57); break;}
					case 'SRA0':{$format = array(2551.18,3628.35); break;}
					case 'SRA1':{$format = array(1814.17,2551.18); break;}
					case 'SRA2':{$format = array(1275.59,1814.17); break;}
					case 'SRA3':{$format = array(907.09,1275.59); break;}
					case 'SRA4':{$format = array(637.80,907.09); break;}
					case 'LETTER':{$format = array(612.00,792.00); break;}
					case 'LEGAL':{$format = array(612.00,1008.00); break;}
					case 'EXECUTIVE':{$format = array(521.86,756.00); break;}
					case 'FOLIO':{$format = array(612.00,936.00); break;}
				}
				$this->fwPt = $format[0];
				$this->fhPt = $format[1];
			} else{
				$this->fwPt = $format[0] * $this->k;
				$this->fhPt = $format[1] * $this->k;
			}
			$this->setPageOrientation($orientation);
		}
		public function setPageOrientation($orientation, $autopagebreak='', $bottommargin=''){
			$orientation = strtoupper($orientation);
			if(($orientation == 'P') OR($orientation == 'PORTRAIT')){
				$this->CurOrientation = 'P';
				$this->wPt = $this->fwPt;
				$this->hPt = $this->fhPt;
			} elseif(($orientation == 'L') OR($orientation == 'LANDSCAPE')){
				$this->CurOrientation = 'L';
				$this->wPt = $this->fhPt;
				$this->hPt = $this->fwPt;
			} else{
				$this->Error('Incorrect orientation: '.$orientation);
			}
			$this->w = $this->wPt / $this->k;
			$this->h = $this->hPt / $this->k;
			if(empty($autopagebreak)){
				if(isset($this->AutoPageBreak)){
					$autopagebreak = $this->AutoPageBreak;
				} else{
					$autopagebreak = true;
				}
			}
			if(empty($bottommargin)){
				if(isset($this->bMargin)){
					$bottommargin = $this->bMargin;
				} else{
					$bottommargin = 2 * 28.35 / $this->k;
				}
			}
			$this->SetAutoPageBreak($autopagebreak, $bottommargin);
			$this->pagedim[$this->page] = array('w' => $this->wPt, 'h' => $this->hPt, 'wk' => $this->w, 'hk' => $this->h, 'tm' => $this->tMargin, 'bm' => $bottommargin, 'lm' => $this->lMargin, 'rm' => $this->rMargin, 'pb' => $autopagebreak, 'or' => $this->CurOrientation, 'olm' => $this->original_lMargin, 'orm' => $this->original_rMargin);
		}
		public function setRTL($enable){
			$this->rtl = $enable ? true : false;
			$this->tmprtl = false;
		}
		public function getRTL(){
			return $this->rtl;
		}
		public function setTempRTL($mode){
			switch($mode){
				case false:
				case 'L':
				case 'R':{
					$this->tmprtl = $mode;
				}
			}
		}
		public function setLastH($h){
			$this->lasth = $h;
		}
		public function getLastH(){
			return $this->lasth;
		}
		public function setImageScale($scale){
			$this->imgscale = $scale;
		}
		public function getImageScale(){
			return $this->imgscale;
		}
		public function getPageWidth(){
			return $this->w;
		}
		public function getPageHeight(){
			return $this->h;
		}
		public function getBreakMargin(){
			return $this->bMargin;
		}
		public function getScaleFactor(){
			return $this->k;
		}
		public function SetMargins($left, $top, $right=-1){
			$this->lMargin = $left;
			$this->tMargin = $top;
			if($right == -1){
				$right = $left;
			}
			$this->rMargin = $right;
		}
		public function SetLeftMargin($margin){
			$this->lMargin=$margin;
			if(($this->page > 0) AND($this->x < $margin)){
				$this->x = $margin;
			}
		}
		public function SetTopMargin($margin){
			$this->tMargin=$margin;
			if(($this->page > 0) AND($this->y < $margin)){
				$this->y = $margin;
			}
		}
		public function SetRightMargin($margin){
			$this->rMargin=$margin;
			if(($this->page > 0) AND($this->x >($this->w - $margin))){
				$this->x = $this->w - $margin;
			}
		}
		public function SetCellPadding($pad){
			$this->cMargin = $pad;
		}
		public function SetAutoPageBreak($auto, $margin=0){
			$this->AutoPageBreak = $auto;
			$this->bMargin = $margin;
			$this->PageBreakTrigger = $this->h - $margin;
		}
		public function SetDisplayMode($zoom, $layout='SinglePage', $mode='UseNone'){
			if(($zoom == 'fullpage') OR($zoom == 'fullwidth') OR($zoom == 'real') OR($zoom == 'default') OR(!is_string($zoom))){
				$this->ZoomMode = $zoom;
			} else{
				$this->Error('Incorrect zoom display mode: '.$zoom);
			}
			switch($layout){
				case 'default':
				case 'single':
				case 'SinglePage':{
					$this->LayoutMode = 'SinglePage';
					break;
				}
				case 'continuous':
				case 'OneColumn':{
					$this->LayoutMode = 'OneColumn';
					break;
				}
				case 'two':
				case 'TwoColumnLeft':{
					$this->LayoutMode = 'TwoColumnLeft';
					break;
				}
				case 'TwoColumnRight':{
					$this->LayoutMode = 'TwoColumnRight';
					break;
				}
				case 'TwoPageLeft':{
					$this->LayoutMode = 'TwoPageLeft';
					break;
				}
				case 'TwoPageRight':{
					$this->LayoutMode = 'TwoPageRight';
					break;
				}
				default:{
					$this->LayoutMode = 'SinglePage';
				}
			}
			switch($mode){
				case 'UseNone':{
					$this->PageMode = 'UseNone';
					break;
				}
				case 'UseOutlines':{
					$this->PageMode = 'UseOutlines';
					break;
				}
				case 'UseThumbs':{
					$this->PageMode = 'UseThumbs';
					break;
				}
				case 'FullScreen':{
					$this->PageMode = 'FullScreen';
					break;
				}
				case 'UseOC':{
					$this->PageMode = 'UseOC';
					break;
				}
				case '':{
					$this->PageMode = 'UseAttachments';
					break;
				}
				default:{
					$this->PageMode = 'UseNone';
				}
			}
		}
		public function SetCompression($compress){
			if(function_exists('gzcompress')){
				$this->compress = $compress;
			} else{
				$this->compress = false;
			}
		}
		public function SetTitle($title){
			$this->title = $title;
		}
		public function SetSubject($subject){
			$this->subject = $subject;
		}
		public function SetAuthor($author){
			$this->author = $author;
		}
		public function SetKeywords($keywords){
			$this->keywords = $keywords;
		}
		public function SetCreator($creator){
			$this->creator = $creator;
		}
		public function Error($msg){
			die('<strong>TCPDF error: </strong>'.$msg);
		}
		public function Open(){
			$this->state = 1;
		}
		public function Close(){
			if($this->state == 3){
				return;
			}
			if($this->page == 0){
				$this->AddPage();
			}
			$this->setFooter();
			$this->_endpage();
			$this->_enddoc();
		}
		public function setPage($pnum, $resetmargins=false){
			if(($pnum > 0) AND($pnum <= count($this->pages))){
				$oldpage = $this->page;
				$this->page = $pnum;
				$this->wPt = $this->pagedim[$this->page]['w'];
				$this->hPt = $this->pagedim[$this->page]['h'];
				$this->w = $this->wPt / $this->k;
				$this->h = $this->hPt / $this->k;
				$this->tMargin = $this->pagedim[$this->page]['tm'];
				$this->bMargin = $this->pagedim[$this->page]['bm'];
				$this->original_lMargin = $this->pagedim[$this->page]['olm'];
				$this->original_rMargin = $this->pagedim[$this->page]['orm'];
				$this->AutoPageBreak = $this->pagedim[$this->page]['pb'];
				$this->CurOrientation = $this->pagedim[$this->page]['or'];
				$this->SetAutoPageBreak($this->AutoPageBreak, $this->bMargin);
				if($resetmargins){
					$this->lMargin = $this->pagedim[$this->page]['olm'];
					$this->rMargin = $this->pagedim[$this->page]['orm'];
					$this->SetY($this->tMargin);
				} else{
					if($this->pagedim[$this->page]['olm'] != $this->pagedim[$oldpage]['olm']){
						$deltam = $this->pagedim[$this->page]['olm'] - $this->pagedim[$this->page]['orm'];
						$this->lMargin += $deltam;
						$this->rMargin -= $deltam;
					}
				}
			} else{
				$this->Error('Wrong page number on setPage() function.');
			}
		}
		public function lastPage($resetmargins=false){
			$this->setPage($this->getNumPages(), $resetmargins);
		}
		public function getPage(){
			return $this->page;
		}
		public function getNumPages(){
			return count($this->pages);
		}
		public function AddPage($orientation='', $format=''){
			if(!isset($this->original_lMargin)){
				$this->original_lMargin = $this->lMargin;
			}
			if(!isset($this->original_rMargin)){
				$this->original_rMargin = $this->rMargin;
			}
			$this->endPage();
			$this->startPage($orientation, $format);
		}
		protected function endPage(){
			if(($this->page == 0) OR(count($this->pages) > $this->page)){
				return;
			}
			$gvars = $this->getGraphicVars();
			$this->setFooter();
			$this->setGraphicVars($gvars);
			$this->_endpage();
		}
		protected function startPage($orientation='', $format=''){
			if(count($this->pages) > $this->page){
				$this->setPage($this->page + 1);
				$this->SetY($this->tMargin);
				return;
			}
			if($this->state == 0){
				$this->Open();
			}
			$this->swapMargins($this->booklet);
			$gvars = $this->getGraphicVars();
			$this->_beginpage($orientation, $format);
			$this->setGraphicVars($gvars);
			$this->intmrk[$this->page] = strlen($this->pages[$this->page]);
			$this->setHeader();
			$this->setGraphicVars($gvars);
			$this->intmrk[$this->page] = strlen($this->pages[$this->page]);
		}
		public function setPageMark(){
			$this->intmrk[$this->page] = strlen($this->pages[$this->page]);
		}
		public function setHeaderData($ln='', $lw=0, $ht='', $hs=''){
			$this->header_logo = $ln;
			$this->header_logo_width = $lw;
			$this->header_title = $ht;
			$this->header_string = $hs;
		}
		public function getHeaderData(){
			$ret = array();
			$ret['logo'] = $this->header_logo;
			$ret['logo_width'] = $this->header_logo_width;
			$ret['title'] = $this->header_title;
			$ret['string'] = $this->header_string;
			return $ret;
		}
		public function setHeaderMargin($hm=10){
			$this->header_margin = $hm;
		}
		public function getHeaderMargin(){
			return $this->header_margin;
		}
		public function setFooterMargin($fm=10){
			$this->footer_margin = $fm;
		}
		public function getFooterMargin(){
			return $this->footer_margin;
		}
		public function setPrintHeader($val=true){
			$this->print_header = $val;
		}
		public function setPrintFooter($val=true){
			$this->print_footer = $val;
		}
		public function getImageRBX(){
			return $this->img_rb_x;
		}
		public function getImageRBY(){
			return $this->img_rb_y;
		}
		public function Header(){
			$ormargins = $this->getOriginalMargins();
			$headerfont = $this->getHeaderFont();
			$headerdata = $this->getHeaderData();
			if(($headerdata['logo']) AND($headerdata['logo'] != K_BLANK_IMAGE)){
				$this->Image(K_PATH_IMAGES.$headerdata['logo'], $this->GetX(), $this->getHeaderMargin(), $headerdata['logo_width']);
				$imgy = $this->getImageRBY();
			} else{
				$imgy = $this->GetY();
			}
			$cell_height = round(($this->getCellHeightRatio() * $headerfont[2]) / $this->getScaleFactor(), 2);
			if($this->getRTL()){
				$header_x = $ormargins['right'] +($headerdata['logo_width'] * 1.1);
			} else{
				$header_x = $ormargins['left'] +($headerdata['logo_width'] * 1.1);
			}
			$this->SetTextColor(0, 0, 0);
			$this->SetFont($headerfont[0], 'B', $headerfont[2] + 1);
			$this->SetX($header_x);
			$this->Cell(0, $cell_height, $headerdata['title'], 0, 1, '', 0, '', 0);
			$this->SetFont($headerfont[0], $headerfont[1], $headerfont[2]);
			$this->SetX($header_x);
			$this->MultiCell(0, $cell_height, $headerdata['string'], 0, '', 0, 1, '', '', true, 0, false);
			$this->SetLineStyle(array('width' => 0.85 / $this->getScaleFactor(), 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
			$this->SetY(1 + max($imgy, $this->GetY()));
			if($this->getRTL()){
				$this->SetX($ormargins['right']);
			} else{
				$this->SetX($ormargins['left']);
			}
			$this->Cell(0, 0, '', 'T', 0, 'C');
		}
		public function Footer(){
			$cur_y = $this->GetY();
			$ormargins = $this->getOriginalMargins();
			$this->SetTextColor(0, 0, 0);
			$line_width = 0.85 / $this->getScaleFactor();
			$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
			$barcode = $this->getBarcode();
			if(!empty($barcode)){
				$this->Ln();
				$barcode_width = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right'])/3);
				$this->write1DBarcode($barcode, 'C128B', $this->GetX(), $cur_y + $line_width, $barcode_width,(($this->getFooterMargin() / 3) - $line_width), 0.3, '', '');
			}
			$pagenumtxt = $this->l['w_page'].' '.$this->PageNoFormatted().' / '.$this->getAliasNbPages();
			$this->SetY($cur_y);
			if($this->getRTL()){
				$this->SetX($ormargins['right']);
				$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
			} else{
				$this->SetX($ormargins['left']);
				$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'R');
			}
		}
		protected function setHeader(){
			if($this->print_header){
				$lasth = $this->lasth;
				$this->_out('q');
				$this->rMargin = $this->original_rMargin;
				$this->lMargin = $this->original_lMargin;
				$this->cMargin = 0;
				if($this->rtl){
					$this->SetXY($this->original_rMargin, $this->header_margin);
				} else{
					$this->SetXY($this->original_lMargin, $this->header_margin);
				}
				$this->SetFont($this->header_font[0], $this->header_font[1], $this->header_font[2]);
				$this->Header();
				if($this->rtl){
					$this->SetXY($this->original_rMargin, $this->tMargin);
				} else{
					$this->SetXY($this->original_lMargin, $this->tMargin);
				}
				$this->_out('Q');
				$this->lasth = $lasth;
			}
		}
		protected function setFooter(){
			$this->InFooter = true;
			$this->footerpos[$this->page] = strlen($this->pages[$this->page]);
			if($this->print_footer){
				$lasth = $this->lasth;
				$this->_out('q');
				$this->rMargin = $this->original_rMargin;
				$this->lMargin = $this->original_lMargin;
				$this->cMargin = 0;
				$footer_y = $this->h - $this->footer_margin;
				if($this->rtl){
					$this->SetXY($this->original_rMargin, $footer_y);
				} else{
					$this->SetXY($this->original_lMargin, $footer_y);
				}
				$this->SetFont($this->footer_font[0], $this->footer_font[1] , $this->footer_font[2]);
				$this->Footer();
				if($this->rtl){
					$this->SetXY($this->original_rMargin, $this->tMargin);
				} else{
					$this->SetXY($this->original_lMargin, $this->tMargin);
				}
				$this->_out('Q');
				$this->lasth = $lasth;
			}
			$this->footerlen[$this->page] = strlen($this->pages[$this->page]) - $this->footerpos[$this->page];
			$this->InFooter = false;
		}
		public function PageNo(){
			return $this->page;
		}
		public function AddSpotColor($name, $c, $m, $y, $k){
			if(!isset($this->spot_colors[$name])){
				$i = 1 + count($this->spot_colors);
				$this->spot_colors[$name] = array('i' => $i, 'c' => $c, 'm' => $m, 'y' => $y, 'k' => $k);
			}
		}
		public function SetDrawColorArray($color){
			if(isset($color)){
				$color = array_values($color);
				$r = isset($color[0]) ? $color[0] : -1;
				$g = isset($color[1]) ? $color[1] : -1;
				$b = isset($color[2]) ? $color[2] : -1;
				$k = isset($color[3]) ? $color[3] : -1;
				if($r >= 0){
					$this->SetDrawColor($r, $g, $b, $k);
				}
			}
		}
		public function SetDrawColor($col1=0, $col2=-1, $col3=-1, $col4=-1){
			if(!is_numeric($col1)){
				$col1 = 0;
			}
			if(!is_numeric($col2)){
				$col2 = -1;
			}
			if(!is_numeric($col3)){
				$col3 = -1;
			}
			if(!is_numeric($col4)){
				$col4 = -1;
			}
			if(($col2 == -1) AND($col3 == -1) AND($col4 == -1)){
				$this->DrawColor = sprintf('%.3f G', $col1/255);
			} elseif($col4 == -1){
				$this->DrawColor = sprintf('%.3f %.3f %.3f RG', $col1/255, $col2/255, $col3/255);
			} else{
				$this->DrawColor = sprintf('%.3f %.3f %.3f %.3f K', $col1/100, $col2/100, $col3/100, $col4/100);
			}
			if($this->page > 0){
				$this->_out($this->DrawColor);
			}
		}
		public function SetDrawSpotColor($name, $tint=100){
			if(!isset($this->spot_colors[$name])){
				$this->Error('Undefined spot color: '.$name);
			}
			$this->DrawColor = sprintf('/CS%d CS %.3f SCN', $this->spot_colors[$name]['i'], $tint/100);
			if($this->page > 0){
				$this->_out($this->DrawColor);
			}
		}
		public function SetFillColorArray($color){
			if(isset($color)){
				$color = array_values($color);
				$r = isset($color[0]) ? $color[0] : -1;
				$g = isset($color[1]) ? $color[1] : -1;
				$b = isset($color[2]) ? $color[2] : -1;
				$k = isset($color[3]) ? $color[3] : -1;
				if($r >= 0){
					$this->SetFillColor($r, $g, $b, $k);
				}
			}
		}
		public function SetFillColor($col1=0, $col2=-1, $col3=-1, $col4=-1){
			if(!is_numeric($col1)){
				$col1 = 0;
			}
			if(!is_numeric($col2)){
				$col2 = -1;
			}
			if(!is_numeric($col3)){
				$col3 = -1;
			}
			if(!is_numeric($col4)){
				$col4 = -1;
			}
			if(($col2 == -1) AND($col3 == -1) AND($col4 == -1)){
				$this->FillColor = sprintf('%.3f g', $col1/255);
				$this->bgcolor = array('G' => $col1);
			} elseif($col4 == -1){
				$this->FillColor = sprintf('%.3f %.3f %.3f rg', $col1/255, $col2/255, $col3/255);
				$this->bgcolor = array('R' => $col1, 'G' => $col2, 'B' => $col3);
			} else{
				$this->FillColor = sprintf('%.3f %.3f %.3f %.3f k', $col1/100, $col2/100, $col3/100, $col4/100);
				$this->bgcolor = array('C' => $col1, 'M' => $col2, 'Y' => $col3, 'K' => $col4);
			}
			$this->ColorFlag =($this->FillColor != $this->TextColor);
			if($this->page > 0){
				$this->_out($this->FillColor);
			}
		}
		public function SetFillSpotColor($name, $tint=100){
			if(!isset($this->spot_colors[$name])){
				$this->Error('Undefined spot color: '.$name);
			}
			$this->FillColor = sprintf('/CS%d cs %.3f scn', $this->spot_colors[$name]['i'], $tint/100);
			$this->ColorFlag =($this->FillColor != $this->TextColor);
			if($this->page > 0){
				$this->_out($this->FillColor);
			}
		}
		public function SetTextColorArray($color){
			if(isset($color)){
				$color = array_values($color);
				$r = isset($color[0]) ? $color[0] : -1;
				$g = isset($color[1]) ? $color[1] : -1;
				$b = isset($color[2]) ? $color[2] : -1;
				$k = isset($color[3]) ? $color[3] : -1;
				if($r >= 0){
					$this->SetTextColor($r, $g, $b, $k);
				}
			}
		}
		public function SetTextColor($col1=0, $col2=-1, $col3=-1, $col4=-1){
			if(!is_numeric($col1)){
				$col1 = 0;
			}
			if(!is_numeric($col2)){
				$col2 = -1;
			}
			if(!is_numeric($col3)){
				$col3 = -1;
			}
			if(!is_numeric($col4)){
				$col4 = -1;
			}
			if(($col2 == -1) AND($col3 == -1) AND($col4 == -1)){
				$this->TextColor = sprintf('%.3f g', $col1/255);
				$this->fgcolor = array('G' => $col1);
			} elseif($col4 == -1){
				$this->TextColor = sprintf('%.3f %.3f %.3f rg', $col1/255, $col2/255, $col3/255);
				$this->fgcolor = array('R' => $col1, 'G' => $col2, 'B' => $col3);
			} else{
				$this->TextColor = sprintf('%.3f %.3f %.3f %.3f k', $col1/100, $col2/100, $col3/100, $col4/100);
				$this->fgcolor = array('C' => $col1, 'M' => $col2, 'Y' => $col3, 'K' => $col4);
			}
			$this->ColorFlag =($this->FillColor != $this->TextColor);
		}
		public function SetTextSpotColor($name, $tint=100){
			if(!isset($this->spot_colors[$name])){
				$this->Error('Undefined spot color: '.$name);
			}
			$this->TextColor = sprintf('/CS%d cs %.3f scn', $this->spot_colors[$name]['i'], $tint/100);
			$this->ColorFlag =($this->FillColor != $this->TextColor);
			if($this->page > 0){
				$this->_out($this->TextColor);
			}
		}
		public function GetStringWidth($s, $fontname='', $fontstyle='', $fontsize=0){
			return $this->GetArrStringWidth($this->utf8Bidi($this->UTF8StringToArray($s), $this->tmprtl), $fontname, $fontstyle, $fontsize);
		}
		public function GetArrStringWidth($sa, $fontname='', $fontstyle='', $fontsize=0){
			if(!empty($fontname)){
				$prev_FontFamily = $this->FontFamily;
				$prev_FontStyle = $this->FontStyle;
				$prev_FontSizePt = $this->FontSizePt;
				$this->SetFont($fontname, $fontstyle, $fontsize);
			}
			$w = 0;
			foreach($sa as $char){
				$w += $this->GetCharWidth($char);
			}
			if(!empty($fontname)){
				$this->SetFont($prev_FontFamily, $prev_FontStyle, $prev_FontSizePt);
			}
			return $w;
		}
		public function GetCharWidth($char){
			$cw = &$this->CurrentFont['cw'];
			if(isset($cw[$char])){
				$w = $cw[$char];
			} elseif(isset($this->CurrentFont['dw'])){
				$w = $this->CurrentFont['dw'];
			} elseif(isset($this->CurrentFont['desc']['MissingWidth'])){
				$w = $this->CurrentFont['desc']['MissingWidth'];
			} else{
				$w = 500;
			}
			return($w * $this->FontSize / 1000);
		}
		public function GetNumChars($s){
			if(($this->CurrentFont['type'] == 'TrueTypeUnicode') OR($this->CurrentFont['type'] == 'cidfont0')){
				return count($this->UTF8StringToArray($s));
			}
			return strlen($s);
		}
		protected function getFontsList(){
			$fontsdir = opendir($this->_getfontpath());
			while(($file = readdir($fontsdir)) !== false){
				if(substr($file, -4) == '.php'){
						array_push($this->fontlist, strtolower(basename($file, '.php')));
				}
			}
			closedir($fontsdir);
		}
		public function AddFont($family, $style='', $file=''){
			if(empty($family)){
				if(!empty($this->FontFamily)){
					$family = $this->FontFamily;
				} else{
					$this->Error('Empty font family');
				}
			}
			$family = strtolower($family);
			if((!$this->isunicode) AND($family == 'arial')){
				$family = 'helvetica';
			}
			if(($family == 'symbol') OR($family == 'zapfdingbats')){
				$style = '';
			}
			$tempstyle = strtoupper($style);
			$style = '';
			if(strpos($tempstyle, 'U') !== false){
				$this->underline = true;
			} else{
				$this->underline = false;
			}
			if(strpos($tempstyle, 'D') !== false){
				$this->linethrough = true;
			} else{
				$this->linethrough = false;
			}
			if(strpos($tempstyle, 'B') !== false){
				$style .= 'B';
			}
			if(strpos($tempstyle, 'I') !== false){
				$style .= 'I';
			}
			$fontkey = $family.$style;
			$font_style = $style.($this->underline ? 'U' : '').($this->linethrough ? 'D' : '');
			$fontdata = array('fontkey' => $fontkey, 'family' => $family, 'style' => $font_style);
			if(isset($this->fonts[$fontkey])){
				return $fontdata;
			}
			if($file == ''){
				$file = str_replace(' ', '', $family).strtolower($style).'.php';
			}
			if(!file_exists($this->_getfontpath().$file)){
				$file = str_replace(' ', '', $family).'.php';
			}
			if(isset($type)){
				unset($type);
			}
			if(isset($cw)){
				unset($cw);
			}
			include($this->_getfontpath().$file);
			if((!isset($type)) OR(!isset($cw))){
				$this->Error('Could not include font definition file');
			}
			$i = count($this->fonts) + 1;
			if($type == 'cidfont0'){
				$styles = array('' => '', 'B' => ',Bold', 'I' => ',Italic', 'BI' => ',BoldItalic');
				foreach($styles as $skey => $qual){
					$sname = $name.$qual;
					$sfontkey = $family.$skey;
					$this->fonts[$sfontkey] = array('i' => $i, 'type' => $type, 'name' => $sname, 'desc' => $desc, 'cidinfo' => $cidinfo, 'up' => $up, 'ut' => $ut, 'cw' => $cw, 'dw' => $dw, 'enc' => $enc);
					$i = count($this->fonts) + 1;
				}
				$file = '';
			} elseif($type == 'core'){
				$def_width = $cw[ord('"')];
				$this->fonts[$fontkey] = array('i' => $i, 'type' => 'core', 'name' => $this->CoreFonts[$fontkey], 'up' => -100, 'ut' => 50, 'cw' => $cw, 'dw' => $def_width);
			} elseif(($type == 'TrueType') OR($type == 'Type1')){
				if(!isset($file)){
					$file = '';
				}
				if(!isset($enc)){
					$enc = '';
				}
				$this->fonts[$fontkey] = array('i' => $i, 'type' => $type, 'name' => $name, 'up' => $up, 'ut' => $ut, 'cw' => $cw, 'file' => $file, 'enc' => $enc, 'desc' => $desc);
			} elseif($type == 'TrueTypeUnicode'){
				$this->fonts[$fontkey] = array('i' => $i, 'type' => $type, 'name' => $name, 'desc' => $desc, 'up' => $up, 'ut' => $ut, 'cw' => $cw, 'enc' => $enc, 'file' => $file, 'ctg' => $ctg);
			} else{
				$this->Error('Unknow font type');
			}
			if(isset($diff) AND(!empty($diff))){
				$d = 0;
				$nb = count($this->diffs);
				for($i=1; $i <= $nb; $i++){
					if($this->diffs[$i] == $diff){
						$d = $i;
						break;
					}
				}
				if($d == 0){
					$d = $nb + 1;
					$this->diffs[$d] = $diff;
				}
				$this->fonts[$fontkey]['diff'] = $d;
			}
			if(!empty($file)){
				if((strcasecmp($type,'TrueType') == 0) OR(strcasecmp($type,'TrueTypeUnicode') == 0)){
					$this->FontFiles[$file] = array('length1' => $originalsize);
				} elseif($type != 'core'){
					$this->FontFiles[$file] = array('length1' => $size1, 'length2' => $size2);
				}
			}
			return $fontdata;
		}
		public function SetFont($family, $style='', $size=0){
			if($size == 0){
				$size = $this->FontSizePt;
			}
			$fontdata =  $this->AddFont($family, $style);
			$this->FontFamily = $fontdata['family'];
			$this->FontStyle = $fontdata['style'];
			$this->CurrentFont = &$this->fonts[$fontdata['fontkey']];
			$this->SetFontSize($size);
		}
		public function SetFontSize($size){
			$this->FontSizePt = $size;
			$this->FontSize = $size / $this->k;
			if(isset($this->CurrentFont['desc']['Ascent']) AND($this->CurrentFont['desc']['Ascent'] > 0)){
				$this->FontAscent = $this->CurrentFont['desc']['Ascent'] * $this->FontSize / 1000;
			} else{
				$this->FontAscent = 0.8 * $this->FontSize;
			}
			if(isset($this->CurrentFont['desc']['Descent']) AND($this->CurrentFont['desc']['Descent'] > 0)){
				$this->FontDescent = - $this->CurrentFont['desc']['Descent'] * $this->FontSize / 1000;
			} else{
				$this->FontDescent = 0.2 * $this->FontSize;
			}
			if(($this->page > 0) AND(isset($this->CurrentFont['i']))){
				$this->_out(sprintf('BT /F%d %.2f Tf ET', $this->CurrentFont['i'], $this->FontSizePt));
			}
		}
		public function AddLink(){
			$n = count($this->links) + 1;
			$this->links[$n] = array(0, 0);
			return $n;
		}
		public function SetLink($link, $y=0, $page=-1){
			if($y == -1){
				$y = $this->y;
			}
			if($page == -1){
				$page = $this->page;
			}
			$this->links[$link] = array($page, $y);
		}
		public function Link($x, $y, $w, $h, $link, $spaces=0){
			$this->Annotation($x, $y, $w, $h, $link, array('Subtype'=>'Link'), $spaces);
		}
		public function Annotation($x, $y, $w, $h, $text, $opt=array('Subtype'=>'Text'), $spaces=0){
			if(isset($this->transfmatrix)){
				$maxid = count($this->transfmatrix) - 1;
				for($i=$maxid; $i >= 0; $i--){
					$ctm = $this->transfmatrix[$i];
					if(isset($ctm['a'])){
						$x = $x * $this->k;
						$y =($this->h - $y) * $this->k;
						$w = $w * $this->k;
						$h = $h * $this->k;
						$xt = $x;
						$yt = $y;
						$x1 =($ctm['a'] * $xt) +($ctm['c'] * $yt) + $ctm['e'];
						$y1 =($ctm['b'] * $xt) +($ctm['d'] * $yt) + $ctm['f'];
						$xt = $x + $w;
						$yt = $y;
						$x2 =($ctm['a'] * $xt) +($ctm['c'] * $yt) + $ctm['e'];
						$y2 =($ctm['b'] * $xt) +($ctm['d'] * $yt) + $ctm['f'];
						$xt = $x;
						$yt = $y - $h;
						$x3 =($ctm['a'] * $xt) +($ctm['c'] * $yt) + $ctm['e'];
						$y3 =($ctm['b'] * $xt) +($ctm['d'] * $yt) + $ctm['f'];
						$xt = $x + $w;
						$yt = $y - $h;
						$x4 =($ctm['a'] * $xt) +($ctm['c'] * $yt) + $ctm['e'];
						$y4 =($ctm['b'] * $xt) +($ctm['d'] * $yt) + $ctm['f'];
						$x = min($x1, $x2, $x3, $x4);
						$y = max($y1, $y2, $y3, $y4);
						$w =(max($x1, $x2, $x3, $x4) - $x) / $this->k;
						$h =($y - min($y1, $y2, $y3, $y4)) / $this->k;
						$x = $x / $this->k;
						$y = $this->h -($y / $this->k);
					}
				}
			}
			$this->PageAnnots[$this->page][] = array('x' => $x, 'y' => $y, 'w' => $w, 'h' => $h, 'txt' => $text, 'opt' => $opt, 'numspaces' => $spaces);
		}
		public function Text($x, $y, $txt, $stroke=0, $clip=false){
			if($this->rtl){
				$s = $this->utf8Bidi($this->UTF8StringToArray($txt), $this->tmprtl);
				$l = $this->GetArrStringWidth($s);
				$xr = $this->w - $x - $this->GetArrStringWidth($s);
			} else{
				$xr = $x;
			}
			$opt = '';
			if(($stroke > 0) AND(!$clip)){
				$opt .= '1 Tr '.intval($stroke).' w ';
			} elseif(($stroke > 0) AND $clip){
				$opt .= '5 Tr '.intval($stroke).' w ';
			} elseif($clip){
				$opt .= '7 Tr ';
			}
			$s = sprintf('BT %.2f %.2f Td %s(%s) Tj ET 0 Tr', $xr * $this->k,($this->h-$y) * $this->k, $opt, $this->_escapetext($txt));
			if($this->underline AND($txt!='')){
				$s .= ' '.$this->_dounderline($xr, $y, $txt);
			}
			if($this->linethrough AND($txt!='')){
				$s .= ' '.$this->_dolinethrough($xr, $y, $txt);
			}
			if($this->ColorFlag AND(!$clip)){
				$s='q '.$this->TextColor.' '.$s.' Q';
			}
			$this->_out($s);
		}
		public function AcceptPageBreak(){
			return $this->AutoPageBreak;
		}
		protected function checkPageBreak($h){
			if((($this->y + $h) > $this->PageBreakTrigger) AND(!$this->InFooter) AND($this->AcceptPageBreak())){
				$rs = '';
				$x = $this->x;
				$ws = $this->ws;
				if($ws > 0){
					$this->ws = 0;
					$rs .= '0 Tw';
				}
				$this->AddPage($this->CurOrientation);
				if($ws > 0){
					$this->ws = $ws;
					$rs .= sprintf('%.3f Tw', $ws * $k);
				}
				$this->_out($rs);
				$this->y = $this->tMargin;
				$oldpage = $this->page - 1;
				if($this->rtl){
					if($this->pagedim[$this->page]['orm'] != $this->pagedim[$oldpage]['orm']){
						$this->x = $x -($this->pagedim[$this->page]['orm'] - $this->pagedim[$oldpage]['orm']);
					} else{
						$this->x = $x;
					}
				} else{
					if($this->pagedim[$this->page]['olm'] != $this->pagedim[$oldpage]['olm']){
						$this->x = $x +($this->pagedim[$this->page]['olm'] - $this->pagedim[$oldpage]['olm']);
					} else{
						$this->x = $x;
					}
				}
				return true;
			}
			return false;
		}
		public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0){
			$min_cell_height = $this->FontSize * $this->cell_height_ratio;
			if($h < $min_cell_height){
				$h = $min_cell_height;
			}
			$this->checkPageBreak($h);
			$this->_out($this->getCellCode($w, $h, $txt, $border, $ln, $align, $fill, $link, $stretch));
		}
		protected function getCellCode($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0){
			$rs = '';
			$min_cell_height = $this->FontSize * $this->cell_height_ratio;
			if($h < $min_cell_height){
				$h = $min_cell_height;
			}
			$k = $this->k;
			if(empty($w) OR($w <= 0)){
				if($this->rtl){
					$w = $this->x - $this->lMargin;
				} else{
					$w = $this->w - $this->rMargin - $this->x;
				}
			}
			$s = '';
			if(($fill == 1) OR($border == 1)){
				if($fill == 1){
					$op =($border == 1) ? 'B' : 'f';
				} else{
					$op = 'S';
				}
				if($this->rtl){
					$xk =(($this->x  - $w) * $k);
				} else{
					$xk =($this->x * $k);
				}
				$s .= sprintf('%.2f %.2f %.2f %.2f re %s ', $xk,(($this->h - $this->y) * $k),($w * $k),(-$h * $k), $op);
			}
			if(is_string($border)){
				$lm = $this->LineWidth / 2;
				$x = $this->x;
				$y = $this->y;
				if(strpos($border,'L') !== false){
					if($this->rtl){
						$xk =($x - $w) * $k;
					} else{
						$xk = $x * $k;
					}
					$s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $xk,(($this->h - $y + $lm) * $k), $xk,(($this->h -($y + $h + $lm)) * $k));
				}
				if(strpos($border,'T') !== false){
					if($this->rtl){
						$xk =($x - $w + $lm) * $k;
						$xwk =($x - $lm) * $k;
					} else{
						$xk =($x - $lm) * $k;
						$xwk =($x + $w + $lm) * $k;
					}
					$s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $xk,(($this->h - $y) * $k), $xwk,(($this->h - $y) * $k));
				}
				if(strpos($border,'R') !== false){
					if($this->rtl){
						$xk = $x * $k;
					} else{
						$xk =($x + $w) * $k;
					}
					$s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $xk,(($this->h - $y + $lm) * $k), $xk,(($this->h -($y + $h + $lm))* $k));
				}
				if(strpos($border,'B') !== false){
					if($this->rtl){
						$xk =($x - $w + $lm) * $k;
						$xwk =($x - $lm) * $k;
					} else{
						$xk =($x - $lm) * $k;
						$xwk =($x + $w + $lm) * $k;
					}
					$s .= sprintf('%.2f %.2f m %.2f %.2f l S ', $xk,(($this->h -($y + $h)) * $k), $xwk,(($this->h -($y + $h)) * $k));
				}
			}
			if($txt != ''){
				$width = $this->GetStringWidth($txt);
				$ratio =($w -(2 * $this->cMargin)) / $width;
				if(($stretch > 0) AND(($ratio < 1) OR(($ratio > 1) AND(($stretch % 2) == 0)))){
					if($stretch > 2){
						$char_space =(($w - $width -(2 * $this->cMargin)) * $this->k) / max($this->GetNumChars($txt)-1,1);
						$rs .= sprintf('BT %.2f Tc ET ', $char_space);
					} else{
						$horiz_scale = $ratio * 100.0;
						$rs .= sprintf('BT %.2f Tz ET ', $horiz_scale);
					}
					$align = '';
					$width = $w -(2 * $this->cMargin);
				} else{
					$stretch == 0;
				}
				if($align == 'L'){
					if($this->rtl){
						$dx = $w - $width - $this->cMargin;
					} else{
						$dx = $this->cMargin;
					}
				} elseif($align == 'R'){
					if($this->rtl){
						$dx = $this->cMargin;
					} else{
						$dx = $w - $width - $this->cMargin;
					}
				} elseif($align == 'C'){
					$dx =($w - $width) / 2;
				} elseif($align == 'J'){
					if($this->rtl){
						$dx = $w - $width - $this->cMargin;
					} else{
						$dx = $this->cMargin;
					}
				} else{
					$dx = $this->cMargin;
				}
				if($this->ColorFlag){
					$s .= 'q '.$this->TextColor.' ';
				}
				$txt2 = $this->_escapetext($txt);
				if($this->rtl){
					$xdk =($this->x - $dx - $width) * $k;
				} else{
					$xdk =($this->x + $dx) * $k;
				}
				if($align == 'J'){
					$ns = substr_count($txt, ' ');
					if(($this->CurrentFont['type'] == 'TrueTypeUnicode') OR($this->CurrentFont['type'] == 'cidfont0')){
						$width = $this->GetStringWidth(str_replace(' ', '', $txt));
						$spacewidth =($w - $width -(2 * $this->cMargin)) /($ns?$ns:1) / $this->FontSize / $this->k;
						$txt2 = str_replace(chr(0).' ', ') '.(-2830 * $spacewidth).' (', $txt2);
					} else{
						$width = $this->GetStringWidth($txt);
						$spacewidth =(($w - $width -(2 * $this->cMargin)) /($ns?$ns:1)) * $this->k;
						$rs .= sprintf('BT %.3f Tw ET ', $spacewidth);
					}
				}
				$basefonty = $this->y +($h/2) +($this->FontSize/3);
				$s .= sprintf('BT %.2f %.2f Td [(%s)] TJ ET', $xdk,(($this->h - $basefonty) * $k), $txt2);
				if($this->rtl){
					$xdx = $this->x - $dx - $width;
				} else{
					$xdx = $this->x + $dx;
				}
				if($this->underline){
					$s .= ' '.$this->_dounderline($xdx, $basefonty, $txt);
				}
				if($this->linethrough){
					$s .= ' '.$this->_dolinethrough($xdx, $basefonty, $txt);
				}
				if($this->ColorFlag){
					$s .= ' Q';
				}
				if($link){
					$this->Link($xdx, $this->y +(($h - $this->FontSize)/2), $width, $this->FontSize, $link, substr_count($txt, chr(32)));
				}
			}
			if($s){
				$rs .= $s;
				if($stretch > 2){
					$rs .= ' BT 0 Tc ET';
				} elseif($stretch > 0){
					$rs .= ' BT 100 Tz ET';
				}
			}
			if(!(($this->CurrentFont['type'] == 'TrueTypeUnicode') OR($this->CurrentFont['type'] == 'cidfont0')) AND($align == 'J')){
				$rs .= ' BT 0 Tw ET';
			}
			$this->lasth = $h;
			if($ln > 0){
				$this->y += $h;
				if($ln == 1){
					if($this->rtl){
						$this->x = $this->w - $this->rMargin;
					} else{
						$this->x = $this->lMargin;
					}
				}
			} else{
				if($this->rtl){
					$this->x -= $w;
				} else{
					$this->x += $w;
				}
			}
			$gstyles = ''.$this->linestyleWidth.' '.$this->linestyleCap.' '.$this->linestyleJoin.' '.$this->linestyleDash.' '.$this->DrawColor.' '.$this->FillColor."\n";
			$rs = $gstyles.$rs;
			return $rs;
		}
		public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false){
			if(empty($this->lasth) OR $reseth){
				$this->lasth = $this->FontSize * $this->cell_height_ratio;
			}
			if(!empty($y)){
				$this->SetY($y);
			} else{
				$y = $this->GetY();
			}
			$this->checkPageBreak($h);
			$y = $this->GetY();
			$startpage = $this->page;
			if(!empty($x)){
				$this->SetX($x);
			} else{
				$x = $this->GetX();
			}
			if(empty($w) OR($w <= 0)){
				if($this->rtl){
					$w = $this->x - $this->lMargin;
				} else{
					$w = $this->w - $this->rMargin - $this->x;
				}
			}
			$lMargin = $this->lMargin;
			$rMargin = $this->rMargin;
			if($this->rtl){
				$this->SetRightMargin($this->w - $this->x);
				$this->SetLeftMargin($this->x - $w);
			} else{
				$this->SetLeftMargin($this->x);
				$this->SetRightMargin($this->w - $this->x - $w);
			}
			$starty = $this->y;
			if($this->cMargin <($this->LineWidth / 2)){
				$this->cMargin =($this->LineWidth / 2);
			}
			if(($this->lasth - $this->FontSize) < $this->LineWidth){
				$this->y += $this->LineWidth / 2;
			}
			$this->y += $this->cMargin;
			if($ishtml){
				$this->writeHTML($txt, true, 0, $reseth, true, $align);
				$nl = 1;
			} else{
				$nl = $this->Write($this->lasth, $txt, '', 0, $align, true, $stretch, false);
			}
			$this->y += $this->cMargin;
			if(($this->lasth - $this->FontSize) < $this->LineWidth){
				$this->y += $this->LineWidth / 2;
			}
			$currentY = $this->GetY();
			$endpage = $this->page;
			if($endpage > $startpage){
				for($page=$startpage; $page <= $endpage; $page++){
					$this->setPage($page);
					if($page == $startpage){
						$this->y = $starty;
						$h = $this->getPageHeight() - $starty - $this->getBreakMargin();
						$cborder = $border ? 'LTR' : 0;
					} elseif($page == $endpage){
						$this->y = $this->tMargin;
						$h = $currentY - $this->tMargin;
						$cborder = $border ? 'LRB' : 0;
					} else{
						$this->y = $this->tMargin;
						$h = $this->getPageHeight() - $this->tMargin - $this->getBreakMargin();
						$cborder = $border ? 'LR' : 0;
					}
					$nx = $x;
					if($page > $startpage){
						if(($this->rtl) AND($this->pagedim[$page]['orm'] != $this->pagedim[$startpage]['orm'])){
							$nx = $x +($this->pagedim[$page]['orm'] - $this->pagedim[$startpage]['orm']);
						} elseif((!$this->rtl) AND($this->pagedim[$page]['olm'] != $this->pagedim[$startpage]['olm'])){
							$nx = $x +($this->pagedim[$page]['olm'] - $this->pagedim[$startpage]['olm']);
						}
					}
					$this->SetX($nx);
					if(!$this->opencell){
						$cborder = $border;
					}
					$ccode = $this->getCellCode($w, $h, '', $cborder, 1, '', $fill);
					if($cborder OR $fill){
						$pstart = substr($this->pages[$this->page], 0, $this->intmrk[$this->page]);
						$pend = substr($this->pages[$this->page], $this->intmrk[$this->page]);
						$this->pages[$this->page] = $pstart.$ccode."\n".$pend;
						$this->intmrk[$this->page] += strlen($ccode."\n");
					}
				}
			} else{
				$h = max($h,($currentY - $y));
				$this->SetY($y);
				$this->SetX($x);
				$ccode = $this->getCellCode($w, $h, '', $border, 1, '', $fill);
				if($border OR $fill){
					$pstart = substr($this->pages[$this->page], 0, $this->intmrk[$this->page]);
					$pend = substr($this->pages[$this->page], $this->intmrk[$this->page]);
					$this->pages[$this->page] = $pstart.$ccode."\n".$pend;
					$this->intmrk[$this->page] += strlen($ccode."\n");
				}
			}
			$currentY = $this->GetY();
			$this->SetLeftMargin($lMargin);
			$this->SetRightMargin($rMargin);
			if($ln > 0){
				$this->SetY($currentY);
				if($ln == 2){
					$this->SetX($x + $w);
				}
			} else{
				$this->setPage($startpage);
				$this->y = $y;
				$this->SetX($x + $w);
			}
			return $nl;
		}
		public function Write($h, $txt, $link='', $fill=0, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false){
			$s = str_replace("\r", '', $txt);
			if(preg_match(K_RE_PATTERN_ARABIC, $s)){
				$arabic = true;
			} else{
				$arabic = false;
			}
			$chrwidth = $this->GetCharWidth('.');
			$chars = $this->UTF8StringToArray($s);
			$nb = count($chars);
			if(($nb == 1) AND preg_match('/[\s]/', $s)){
				if($this->rtl){
					$this->x -= $this->GetStringWidth($s);
				} else{
					$this->x += $this->GetStringWidth($s);
				}
				return;
			}
			$prevx = $this->x;
			$prevy = $this->y;
			if($this->rtl){
				$w = $this->x - $this->lMargin;
			} else{
				$w = $this->w - $this->rMargin - $this->x;
			}
			$wmax = $w -(2 * $this->cMargin);
			$i = 0;
			$j = 0;
			$sep = -1;
			$l = 0;
			$nl = 0;
			$linebreak = false;
			while($i < $nb){
				$c = $chars[$i];
				if($c == 10){
					if($align == 'J'){
						if($this->rtl){
							$talign = 'R';
						} else{
							$talign = 'L';
						}
					} else{
						$talign = $align;
					}
					if($firstline){
						$startx = $this->x;
						$linew = $this->GetArrStringWidth($this->utf8Bidi(array_slice($chars, $j, $i), $this->tmprtl));
						if($this->rtl){
							$this->endlinex = $startx - $linew;
						} else{
							$this->endlinex = $startx + $linew;
						}
						$w = $linew;
						$tmpcmargin = $this->cMargin;
						$this->cMargin = 0;
					}
					$this->Cell($w, $h, $this->UTF8ArrSubString($chars, $j, $i), 0, 1, $talign, $fill, $link, $stretch);
					if($firstline){
						$this->cMargin = $tmpcmargin;
						return($this->UTF8ArrSubString($chars, $i));
					}
					$nl++;
					$j = $i + 1;
					$l = 0;
					$sep = -1;
					if((($this->y + $this->lasth) > $this->PageBreakTrigger) AND(!$this->InFooter)){
						$this->AcceptPageBreak();
					}
					$w = $this->getRemainingWidth();
					$wmax = $w -(2 * $this->cMargin);
				} else{
					if(preg_match('/[\s]/', $this->unichr($c))){
						$sep = $i;
					}
					if((($this->CurrentFont['type'] == 'TrueTypeUnicode') OR($this->CurrentFont['type'] == 'cidfont0')) AND($arabic)){
						$l = $this->GetArrStringWidth($this->utf8Bidi(array_slice($chars, $j, $i-$j+1), $this->tmprtl));
					} else{
						$l += $this->GetCharWidth($c);
					}
					if($l > $wmax){
						if($sep == -1){
							if(($this->rtl AND($this->x <=($this->w - $this->rMargin - $chrwidth)))
								OR((!$this->rtl) AND($this->x >=($this->lMargin + $chrwidth)))){
								$this->Cell($w, $h, '', 0, 1);
								$linebreak = true;
								if($firstline){
									return($this->UTF8ArrSubString($chars, $j));
								}
							} else{
								if($firstline){
									$startx = $this->x;
									$linew = $this->GetArrStringWidth($this->utf8Bidi(array_slice($chars, $j, $i), $this->tmprtl));
									if($this->rtl){
										$this->endlinex = $startx - $linew;
									} else{
										$this->endlinex = $startx + $linew;
									}
									$w = $linew;
									$tmpcmargin = $this->cMargin;
									$this->cMargin = 0;
								}
								$this->Cell($w, $h, $this->UTF8ArrSubString($chars, $j, $i), 0, 1, $align, $fill, $link, $stretch);
								if($firstline){
									$this->cMargin = $tmpcmargin;
									return($this->UTF8ArrSubString($chars, $i));
								}
								$j = $i;
								$i--;
							}
						} else{
							if($this->rtl AND(!$firstblock)){
								$endspace = 1;
							} else{
								$endspace = 0;
							}
							if($firstline){
								$startx = $this->x;
								$linew = $this->GetArrStringWidth($this->utf8Bidi(array_slice($chars, $j,($sep + $endspace)), $this->tmprtl));
								if($this->rtl){
									$this->endlinex = $startx - $linew;
								} else{
									$this->endlinex = $startx + $linew;
								}
								$w = $linew;
								$tmpcmargin = $this->cMargin;
								$this->cMargin = 0;
							}
							$this->Cell($w, $h, $this->UTF8ArrSubString($chars, $j,($sep + $endspace)), 0, 1, $align, $fill, $link, $stretch);
							if($firstline){
								$this->cMargin = $tmpcmargin;
								return($this->UTF8ArrSubString($chars,($sep + $endspace)));
							}
							$i = $sep;
							$sep = -1;
							$j =($i+1);
						}
						if((($this->y + $this->lasth) > $this->PageBreakTrigger) AND(!$this->InFooter)){
							$this->AcceptPageBreak();
						}
						$w = $this->getRemainingWidth();
						$wmax = $w -(2 * $this->cMargin);
						if($linebreak){
							$linebreak = false;
						} else{
							$nl++;
							$l = 0;
						}
					}
				}
				$i++;
			}
			if($l > 0){
				switch($align){
					case 'J':
					case 'C':{
						$w = $w;
						break;
					}
					case 'L':{
						if($this->rtl){
							$w = $w;
						} else{
							$w = $l;
						}
						break;
					}
					case 'R':{
						if($this->rtl){
							$w = $l;
						} else{
							$w = $w;
						}
						break;
					}
					default:{
						$w = $l;
						break;
					}
				}
				if($firstline){
					$startx = $this->x;
					$linew = $this->GetArrStringWidth($this->utf8Bidi(array_slice($chars, $j, $nb), $this->tmprtl));
					if($this->rtl){
						$this->endlinex = $startx - $linew;
					} else{
						$this->endlinex = $startx + $linew;
					}
					$w = $linew;
					$tmpcmargin = $this->cMargin;
					$this->cMargin = 0;
				}
				$this->Cell($w, $h, $this->UTF8ArrSubString($chars, $j, $nb), 0, $ln, $align, $fill, $link, $stretch);
				if($firstline){
					$this->cMargin = $tmpcmargin;
					return($this->UTF8ArrSubString($chars, $nb));
				}
				$nl++;
			}
			if($firstline){
				return '';
			}
			return $nl;
		}
		protected function getRemainingWidth(){
			if($this->rtl){
				return($this->x - $this->lMargin);
			} else{
				return($this->w - $this->rMargin - $this->x);
			}
		}
		public function UTF8ArrSubString($strarr, $start='', $end=''){
			if(strlen($start) == 0){
				$start = 0;
			}
			if(strlen($end) == 0){
				$end = count($strarr);
			}
			$string = '';
			for($i=$start; $i < $end; $i++){
				$string .= $this->unichr($strarr[$i]);
			}
			return $string;
		}
		public function unichr($c){
			if(!$this->isunicode){
				return chr($c);
			} elseif($c <= 0x7F){
				return chr($c);
			} elseif($c <= 0x7FF){
				return chr(0xC0 | $c >> 6).chr(0x80 | $c & 0x3F);
			} elseif($c <= 0xFFFF){
				return chr(0xE0 | $c >> 12).chr(0x80 | $c >> 6 & 0x3F).chr(0x80 | $c & 0x3F);
			} elseif($c <= 0x10FFFF){
				return chr(0xF0 | $c >> 18).chr(0x80 | $c >> 12 & 0x3F).chr(0x80 | $c >> 6 & 0x3F).chr(0x80 | $c & 0x3F);
			} else{
				return '';
			}
		}
		public function Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign=''){
			if($x === ''){
				$x = $this->x;
			}
			if($y === ''){
				$y = $this->y;
			}
			list($pixw, $pixh) = getimagesize($file);
			if(($w <= 0) AND($h <= 0)){
				$w = $pixw /($this->imgscale * $this->k);
				$h = $pixh /($this->imgscale * $this->k);
			} elseif($w <= 0){
				$w = $h * $pixw / $pixh;
			} elseif($h <= 0){
				$h = $w * $pixh / $pixw;
			}
			$neww = round($w * $this->k * $dpi / $this->dpi);
			$newh = round($h * $this->k * $dpi / $this->dpi);
			if(($neww * $newh) >=($pixw * $pixh)){
				$resize = false;
			}
			if(!isset($this->images[$file])){
				if($type == ''){
					$fileinfo = pathinfo($file);
					if(isset($fileinfo['extension']) AND(!empty($fileinfo['extension']))){
						$type = $fileinfo['extension'];
					} else{
						$this->Error('Image file has no extension and no type was specified: '.$file);
					}
				}
				$type = strtolower($type);
				if($type == 'jpg'){
					$type = 'jpeg';
				}
				$mqr = get_magic_quotes_runtime();
				set_magic_quotes_runtime(0);
				$mtd = '_parse'.$type;
				$gdfunction = 'imagecreatefrom'.$type;
				$info = false;
				if((method_exists($this,$mtd)) AND(!($resize AND function_exists($gdfunction)))){
					$info = $this->$mtd($file);
				}
				if(!$info){
					if(function_exists($gdfunction)){
						$img = $gdfunction($file);
						if($resize){
							$imgr = imagecreatetruecolor($neww, $newh);
							imagecopyresampled($imgr, $img, 0, 0, 0, 0, $neww, $newh, $pixw, $pixh);
							$info = $this->_toJPEG($imgr);
						} else{
							$info = $this->_toJPEG($img);
						}
					} else{
						return;
					}
				}
				if($info === false){
					return;
				}
				set_magic_quotes_runtime($mqr);
				$info['i'] = count($this->images) + 1;
				$this->images[$file] = $info;
			} else{
				$info = $this->images[$file];
			}
			if((($y + $h) > $this->PageBreakTrigger) AND(!$this->InFooter) AND $this->AcceptPageBreak()){
				$this->AddPage($this->CurOrientation);
				$y = $this->GetY() + $this->cMargin;
			}
			$this->img_rb_y = $y + $h;
			if($this->rtl){
				if($palign == 'L'){
					$ximg = $this->lMargin;
					$this->img_rb_x = $ximg + $w;
				} elseif($palign == 'C'){
					$ximg =($this->w - $x - $w) / 2;
					$this->img_rb_x = $ximg + $w;
				} else{
					$ximg = $this->w - $x - $w;
					$this->img_rb_x = $ximg;
				}
			} else{
				if($palign == 'R'){
					$ximg = $this->w - $this->rMargin - $w;
					$this->img_rb_x = $ximg;
				} elseif($palign == 'C'){
					$ximg =($this->w - $x - $w) / 2;
					$this->img_rb_x = $ximg + $w;
				} else{
					$ximg = $x;
					$this->img_rb_x = $ximg + $w;
				}
			}
			$xkimg = $ximg * $this->k;
			$this->_out(sprintf('q %.2f 0 0 %.2f %.2f %.2f cm /I%d Do Q',($w * $this->k),($h * $this->k), $xkimg,(($this->h -($y + $h)) * $this->k), $info['i']));
			if($link){
				$this->Link($ximg, $y, $w, $h, $link, 0);
			}
			switch($align){
				case 'T':{
					$this->y = $y;
					$this->x = $this->img_rb_x;
					break;
				}
				case 'M':{
					$this->y = $y + round($h/2);
					$this->x = $this->img_rb_x;
					break;
				}
				case 'B':{
					$this->y = $this->img_rb_y;
					$this->x = $this->img_rb_x;
					break;
				}
				case 'N':{
					$this->SetY($this->img_rb_y);
					break;
				}
				default:{
					break;
				}
			}
			$this->endlinex = $this->img_rb_x;
		}
		protected function _toJPEG($image){
			$tempname = tempnam(K_PATH_CACHE,'jpg');
			imagejpeg($image, $tempname, $this->jpeg_quality);
			imagedestroy($image);
			$retvars = $this->_parsejpeg($tempname);
			unlink($tempname);
			return $retvars;
		}
		protected function _parsejpeg($file){
			$a = getimagesize($file);
			if(empty($a)){
				$this->Error('Missing or incorrect image file: '.$file);
			}
			if($a[2] != 2){
				$this->Error('Not a JPEG file: '.$file);
			}
			if((!isset($a['channels'])) OR($a['channels'] == 3)){
				$colspace = 'DeviceRGB';
			} elseif($a['channels'] == 4){
				$colspace = 'DeviceCMYK';
			} else{
				$colspace = 'DeviceGray';
			}
			$bpc = isset($a['bits']) ? $a['bits'] : 8;
			$data = file_get_contents($file);
			return array('w' => $a[0], 'h' => $a[1], 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'DCTDecode', 'data' => $data);
		}
		protected function _parsepng($file){
			$f = fopen($file,'rb');
			if(empty($f)){
				$this->Error('Can\'t open image file: '.$file);
			}
			if(fread($f,8) != chr(137).'PNG'.chr(13).chr(10).chr(26).chr(10)){
				$this->Error('Not a PNG file: '.$file);
			}
			fread($f,4);
			if(fread($f,4) != 'IHDR'){
				$this->Error('Incorrect PNG file: '.$file);
			}
			$w = $this->_freadint($f);
			$h = $this->_freadint($f);
			$bpc = ord(fread($f,1));
			if($bpc > 8){
				return false;
			}
			$ct = ord(fread($f,1));
			if($ct == 0){
				$colspace = 'DeviceGray';
			} elseif($ct == 2){
				$colspace = 'DeviceRGB';
			} elseif($ct == 3){
				$colspace = 'Indexed';
			} else{
				return false;
			}
			if(ord(fread($f,1)) != 0){
				return false;
			}
			if(ord(fread($f,1)) != 0){
				return false;
			}
			if(ord(fread($f,1)) != 0){
				return false;
			}
			fread($f,4);
			$parms = '/DecodeParms <</Predictor 15 /Colors '.($ct==2 ? 3 : 1).' /BitsPerComponent '.$bpc.' /Columns '.$w.'>>';
			$pal = '';
			$trns = '';
			$data = '';
			do{
				$n = $this->_freadint($f);
				$type = fread($f,4);
				if($type == 'PLTE'){
					$pal = fread($f,$n);
					fread($f,4);
				} elseif($type == 'tRNS'){
					$t = fread($f,$n);
					if($ct == 0){
						$trns = array(ord(substr($t,1,1)));
					} elseif($ct == 2){
						$trns = array(ord(substr($t,1,1)), ord(substr($t,3,1)), ord(substr($t,5,1)));
					} else{
						$pos = strpos($t,chr(0));
						if($pos !== false){
							$trns = array($pos);
						}
					}
					fread($f, 4);
				} elseif($type == 'IDAT'){
					$data .= fread($f,$n);
					fread($f, 4);
				} elseif($type == 'IEND'){
					break;
				} else{
					fread($f, $n+4);
				}
			}
			while($n);
			if(($colspace == 'Indexed') AND(empty($pal))){
				return false;
			}
			fclose($f);
			return array('w' => $w, 'h' => $h, 'cs' => $colspace, 'bpc' => $bpc, 'f' => 'FlateDecode', 'parms' => $parms, 'pal' => $pal, 'trns' => $trns, 'data' => $data);
		}
		public function Ln($h='', $cell=false){
			if($cell){
				$cellmargin = $this->cMargin;
			} else{
				$cellmargin = 0;
			}
			if($this->rtl){
				$this->x = $this->w - $this->rMargin - $cellmargin;
			} else{
				$this->x = $this->lMargin + $cellmargin;
			}
			if(is_string($h)){
				$this->y += $this->lasth;
			} else{
				$this->y += $h;
			}
			$this->newline = true;
		}
		public function GetX(){
			if($this->rtl){
				return($this->w - $this->x);
			} else{
				return $this->x;
			}
		}
		public function GetAbsX(){
			return $this->x;
		}
		public function GetY(){
			return $this->y;
		}
		public function SetX($x){
			if($this->rtl){
				if($x >= 0){
					$this->x = $this->w - $x;
				} else{
					$this->x = abs($x);
				}
			} else{
				if($x >= 0){
					$this->x = $x;
				} else{
					$this->x = $this->w + $x;
				}
			}
			if($this->x < 0){
				$this->x = 0;
			}
			if($this->x > $this->w){
				$this->x = $this->w;
			}
		}
		public function SetY($y, $resetx=true){
			if($resetx){
				if($this->rtl){
					$this->x = $this->w - $this->rMargin;
				} else{
					$this->x = $this->lMargin;
				}
			}
			if($y >= 0){
				$this->y = $y;
			} else{
				$this->y = $this->h + $y;
			}
			if($this->y < 0){
				$this->y = 0;
			}
			if($this->y > $this->h){
				$this->y = $this->h;
			}
		}
		public function SetXY($x, $y){
			$this->SetY($y);
			$this->SetX($x);
		}
		public function Output($name='doc.pdf', $dest='I'){
			if($this->state < 3){
				$this->Close();
			}
			if(is_bool($dest)){
				$dest = $dest ? 'D' : 'F';
			}
			$dest = strtoupper($dest);
			if($dest != 'F'){
				$name = preg_replace('/[\s]+/', '_', $name);
				$name = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $name);
			}
			switch($dest){
				case 'I':{
					if(ob_get_contents()){
						$this->Error('Some data has already been output, can\'t send PDF file');
					}
					if(php_sapi_name() != 'cli'){
						header('Content-Type: application/pdf');
						if(headers_sent()){
							$this->Error('Some data has already been output to browser, can\'t send PDF file');
						}
						header('Cache-Control: public, must-revalidate, max-age=0');
						header('Pragma: public');
						header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
						header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
						header('Content-Length: '.strlen($this->buffer));
						header('Content-Disposition: inline; filename="'.basename($name).'";');
					}
					echo $this->buffer;
					break;
				}
				case 'D':{
					if(ob_get_contents()){
						$this->Error('Some data has already been output, can\'t send PDF file');
					}
					header('Content-Description: File Transfer');
					if(headers_sent()){
						$this->Error('Some data has already been output to browser, can\'t send PDF file');
					}
					header('Cache-Control: public, must-revalidate, max-age=0');
					header('Pragma: public');
					header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
					header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
					header('Content-Type: application/force-download');
					header('Content-Type: application/octet-stream', false);
					header('Content-Type: application/download', false);
					header('Content-Type: application/pdf', false);
					header('Content-Disposition: attachment; filename="'.basename($name).'";');
					header('Content-Transfer-Encoding: binary');
					header('Content-Length: '.strlen($this->buffer));
					echo $this->buffer;
					break;
				}
				case 'F':{
					$f = fopen($name, 'wb');
					if(!$f){
						$this->Error('Unable to create output file: '.$name);
					}
					fwrite($f, $this->buffer,strlen($this->buffer));
					fclose($f);
					break;
				}
				case 'S':{
					return $this->buffer;
				}
				default:{
					$this->Error('Incorrect output destination: '.$dest);
				}
			}
			return '';
		}
		protected function _dochecks(){
			if(1.1 == 1){
				$this->Error('Don\'t alter the locale before including class file');
			}
			if(sprintf('%.1f', 1.0) != '1.0'){
				setlocale(LC_NUMERIC, 'C');
			}
		}
		protected function _getfontpath(){
			if(!defined('K_PATH_FONTS') AND is_dir(dirname(__FILE__).'/fonts')){
				define('K_PATH_FONTS', dirname(__FILE__).'/fonts/');
			}
			return defined('K_PATH_FONTS') ? K_PATH_FONTS : '';
		}
		protected function _putpages(){
			$nb = count($this->pages);
			if(!empty($this->pagegroups)){
				foreach($this->pagegroups as $k => $v){
					$vs = $this->formatPageNumber($v);
					$vu = $this->UTF8ToUTF16BE($vs, false);
					$alias_a = $this->_escape($k);
					$alias_au = $this->_escape('{'.$k.'}');
					if($this->isunicode){
						$alias_b = $this->_escape($this->UTF8ToLatin1($k));
						$alias_bu = $this->_escape($this->UTF8ToLatin1('{'.$k.'}'));
						$alias_c = $this->_escape($this->utf8StrRev($k, false, $this->tmprtl));
						$alias_cu = $this->_escape($this->utf8StrRev('{'.$k.'}', false, $this->tmprtl));
					}
					for($n = 1; $n <= $nb; $n++){
						$this->pages[$n] = str_replace($alias_au, $vu, $this->pages[$n]);
						if($this->isunicode){
							$this->pages[$n] = str_replace($alias_bu, $vu, $this->pages[$n]);
							$this->pages[$n] = str_replace($alias_cu, $vu, $this->pages[$n]);
							$this->pages[$n] = str_replace($alias_b, $vs, $this->pages[$n]);
							$this->pages[$n] = str_replace($alias_c, $vs, $this->pages[$n]);
						}
						$this->pages[$n] = str_replace($alias_a, $vs, $this->pages[$n]);
					}
				}
			}
			if(!empty($this->AliasNbPages)){
				$nbs = $this->formatPageNumber($nb);
				$nbu = $this->UTF8ToUTF16BE($nbs, false);
				$alias_a = $this->_escape($this->AliasNbPages);
				$alias_au = $this->_escape('{'.$this->AliasNbPages.'}');
				if($this->isunicode){
					$alias_b = $this->_escape($this->UTF8ToLatin1($this->AliasNbPages));
					$alias_bu = $this->_escape($this->UTF8ToLatin1('{'.$this->AliasNbPages.'}'));
					$alias_c = $this->_escape($this->utf8StrRev($this->AliasNbPages, false, $this->tmprtl));
					$alias_cu = $this->_escape($this->utf8StrRev('{'.$this->AliasNbPages.'}', false, $this->tmprtl));
				}
				for($n = 1; $n <= $nb; $n++){
					$this->pages[$n] = str_replace($alias_au, $nbu, $this->pages[$n]);
					if($this->isunicode){
						$this->pages[$n] = str_replace($alias_bu, $nbu, $this->pages[$n]);
						$this->pages[$n] = str_replace($alias_cu, $nbu, $this->pages[$n]);
						$this->pages[$n] = str_replace($alias_b, $nbs, $this->pages[$n]);
						$this->pages[$n] = str_replace($alias_c, $nbs, $this->pages[$n]);
					}
					$this->pages[$n] = str_replace($alias_a, $nbs, $this->pages[$n]);
				}
			}
			$filter =($this->compress) ? '/Filter /FlateDecode ' : '';
			for($n=1; $n <= $nb; $n++){
				$this->pages[$n] = str_replace($this->epsmarker, '', $this->pages[$n]);
				$this->_newobj();
				$this->_out('<</Type /Page');
				$this->_out('/Parent 1 0 R');
				$this->_out(sprintf('/MediaBox [0 0 %.2f %.2f]', $this->pagedim[$n]['w'], $this->pagedim[$n]['h']));
				$this->_out('/Resources 2 0 R');
				$this->_putannots($n);
				$this->_out('/Contents '.($this->n + 1).' 0 R>>');
				$this->_out('endobj');
				$p =($this->compress) ? gzcompress($this->pages[$n]) : $this->pages[$n];
				$this->_newobj();
				$this->_out('<<'.$filter.'/Length '.strlen($p).'>>');
				$this->_putstream($p);
				$this->_out('endobj');
			}
			$this->offsets[1] = strlen($this->buffer);
			$this->_out('1 0 obj');
			$this->_out('<</Type /Pages');
			$kids='/Kids [';
			for($i=0; $i < $nb; $i++){
				$kids .=(3 +(2 * $i)).' 0 R ';
			}
			$this->_out($kids.']');
			$this->_out('/Count '.$nb);
			$this->_out('>>');
			$this->_out('endobj');
		}
		protected function _putannots($n){
			if(isset($this->PageAnnots[$n])){
				$annots = '/Annots [';
				foreach($this->PageAnnots[$n] as $key => $pl){
					$pl['opt'] = array_change_key_case($pl['opt'], CASE_LOWER);
					$a = $pl['x'] * $this->k;
					$b = $this->pagedim[$n]['h'] -($pl['y']  * $this->k);
					$c = $pl['w'] * $this->k;
					$d = $pl['h'] * $this->k;
					$rect = sprintf('%.2f %.2f %.2f %.2f', $a, $b, $a+$c, $b-$d);
					$annots .= "\n";
					$annots .= '<</Type /Annot';
					$annots .= ' /Subtype /'.$pl['opt']['subtype'];
					$annots .= ' /Rect ['.$rect.']';
					$annots .= ' /Contents '.$this->_textstring($pl['txt']);
					$annots .= ' /NM '.$this->_textstring(sprintf('%04u-%04u', $n, $key));
					$annots .= ' /M '.$this->_datastring('D:'.date('YmdHis'));
					if(isset($pl['opt']['f'])){
						$val = 0;
						if(is_array($pl['opt']['f'])){
							foreach($pl['opt']['f'] as $f){
								switch(strtolower($f)){
									case 'invisible':{
										$val += 1 << 0;
										break;
									}
									case 'hidden':{
										$val += 1 << 1;
										break;
									}
									case 'print':{
										$val += 1 << 2;
										break;
									}
									case 'nozoom':{
										$val += 1 << 3;
										break;
									}
									case 'norotate':{
										$val += 1 << 4;
										break;
									}
									case 'noview':{
										$val += 1 << 5;
										break;
									}
									case 'readonly':{
										$val += 1 << 6;
										break;
									}
									case 'locked':{
										$val += 1 << 8;
										break;
									}
									case 'togglenoview':{
										$val += 1 << 9;
										break;
									}
									case 'lockedcontents':{
										$val += 1 << 10;
										break;
									}
									default:{
										break;
									}
								}
							}
						}
						$annots .= ' /F '.intval($val);
					}
					$annots .= ' /Border [';
					if(isset($pl['opt']['border']) AND(count($pl['opt']['border']) >= 3)){
						$annots .= intval($pl['opt']['border'][0]).' ';
						$annots .= intval($pl['opt']['border'][1]).' ';
						$annots .= intval($pl['opt']['border'][2]);
						if(isset($pl['opt']['border'][3]) AND is_array($pl['opt']['border'][3])){
							$annots .= ' [';
							foreach($pl['opt']['border'][3] as $dash){
								$annots .= intval($dash).' ';
							}
							$annots .= ']';
						}
					} else{
						$annots .= '0 0 0';
					}
					$annots .= ']';
					if(isset($pl['opt']['bs']) AND(is_array($pl['opt']['bs']))){
						$annots .= ' /BS <<Type /Border';
						if(isset($pl['opt']['bs']['w'])){
							$annots .= ' /W '.sprintf("%.4f", floatval($pl['opt']['bs']['w']));
						}
						$bstyles = array('S', 'D', 'B', 'I', 'U');
						if(isset($pl['opt']['bs']['s']) AND in_array($pl['opt']['bs']['s'], $bstyles)){
							$annots .= ' /S /'.$pl['opt']['bs']['s'];
						}
						if(isset($pl['opt']['bs']['d']) AND(is_array($pl['opt']['bs']['d']))){
							$annots .= ' /D [';
							foreach($pl['opt']['bs']['d'] as $cord){
								$cord = floatval($cord);
								$annots .= sprintf(" %.4f", $cord);
							}
							$annots .= ']';
						}
						$annots .= '>> ';
					}
					if(isset($pl['opt']['be']) AND(is_array($pl['opt']['be']))){
						$annots .= ' /BE <<';
						$bstyles = array('S', 'C');
						if(isset($pl['opt']['be']['s']) AND in_array($pl['opt']['be']['s'], $markups)){
							$annots .= ' /S /'.$pl['opt']['bs']['s'];
						} else{
							$annots .= ' /S /S';
						}
						if(isset($pl['opt']['be']['i']) AND($pl['opt']['be']['i'] >= 0) AND($pl['opt']['be']['i'] <= 2)){
							$annots .= ' /I '.sprintf(" %.4f", $pl['opt']['be']['i']);
						}
						$annots .= '>>';
					}
					$annots .= ' /C [';
					if(isset($pl['opt']['c']) AND(is_array($pl['opt']['c']))){
						foreach($pl['opt']['c'] as $col){
							$col = intval($col);
							$color = $col <= 0 ? 0 :($col >= 255 ? 1 : $col / 255);
							$annots .= sprintf(" %.4f", $color);
						}
					}
					$annots .= ']';
					$markups = array('text', 'freetext', 'line', 'square', 'circle', 'polygon', 'polyline', 'highlight',  'underline', 'squiggly', 'strikeout', 'stamp', 'caret', 'ink', 'fileattachment', 'sound');
					if(in_array(strtolower($pl['opt']['subtype']), $markups)){
						if(isset($pl['opt']['t']) AND is_string($pl['opt']['t'])){
							$annots .= ' /T '.$this->_textstring($pl['opt']['t']);
						}
						if(isset($pl['opt']['ca'])){
							$annots .= ' /CA '.sprintf("%.4f", floatval($pl['opt']['ca']));
						}
						if(isset($pl['opt']['rc'])){
							$annots .= ' /RC '.$this->_textstring($pl['opt']['rc']);
						}
						$annots .= ' /CreationDate '.$this->_datastring('D:'.date('YmdHis'));
						if(isset($pl['opt']['subj'])){
							$annots .= ' /Subj '.$this->_textstring($pl['opt']['subj']);
						}
					}
					switch(strtolower($pl['opt']['subtype'])){
						case 'text':{
							if(isset($pl['opt']['open'])){
								$annots .= ' /Open '.(strtolower($pl['opt']['open']) == 'true' ? 'true' : 'false');
							}
							$iconsapp = array('Comment', 'Help', 'Insert', 'Key', 'NewParagraph', 'Note', 'Paragraph');
							if(isset($pl['opt']['name']) AND in_array($pl['opt']['name'], $iconsapp)){
								$annots .= ' /Name /'.$pl['opt']['name'];
							} else{
								$annots .= ' /Name /Note';
							}
							$statemodels = array('Marked', 'Review');
							if(isset($pl['opt']['statemodel']) AND in_array($pl['opt']['statemodel'], $statemodels)){
								$annots .= ' /StateModel /'.$pl['opt']['statemodel'];
							} else{
								$pl['opt']['statemodel'] = 'Marked';
								$annots .= ' /StateModel /'.$pl['opt']['statemodel'];
							}
							if($pl['opt']['statemodel'] == 'Marked'){
								$states = array('Accepted', 'Unmarked');
							} else{
								$states = array('Accepted', 'Rejected', 'Cancelled', 'Completed', 'None');
							}
							if(isset($pl['opt']['state']) AND in_array($pl['opt']['state'], $states)){
								$annots .= ' /State /'.$pl['opt']['state'];
							} else{
								if($pl['opt']['statemodel'] == 'Marked'){
									$annots .= ' /State /Unmarked';
								} else{
									$annots .= ' /State /None';
								}
							}
							break;
						}
						case 'link':{
							if(is_string($pl['txt'])){
								$annots .= ' /A <</S /URI /URI '.$this->_datastring($pl['txt']).'>>';
							} else{
								$l = $this->links[$pl['txt']];
								$annots .= sprintf(' /Dest [%d 0 R /XYZ 0 %.2f null]',(1 +(2 * $l[0])),($this->pagedim[$l[0]]['h'] -($l[1] * $this->k)));
							}
							$hmodes = array('N', 'I', 'O', 'P');
							if(isset($pl['opt']['h']) AND in_array($pl['opt']['h'], $hmodes)){
								$annots .= ' /H /'.$pl['opt']['h'];
							} else{
								$annots .= ' /H /I';
							}
							break;
						}
						case 'freetext':{
							$annots .= ' /DA '.$this->_textstring($pl['txt']);
							if(isset($pl['opt']['q']) AND($pl['opt']['q'] >= 0) AND($pl['opt']['q'] <= 2)){
								$annots .= ' /Q '.intval($pl['opt']['q']);
							}
							if(isset($pl['opt']['rc'])){
								$annots .= ' /RC '.$this->_textstring($pl['opt']['rc']);
							}
							if(isset($pl['opt']['ds'])){
								$annots .= ' /DS '.$this->_textstring($pl['opt']['ds']);
							}
							if(isset($pl['opt']['cl']) AND is_array($pl['opt']['cl'])){
								$annots .= ' /CL [';
								foreach($pl['opt']['cl'] as $cl){
									$annots .= sprintf("%.4f ", $cl * $this->k);
								}
								$annots .= ']';
							}
							$tfit = array('FreeTextCallout', 'FreeTextTypeWriter');
							if(isset($pl['opt']['it']) AND in_array($pl['opt']['it'], $tfit)){
								$annots .= ' /IT '.$pl['opt']['it'];
							}
							if(isset($pl['opt']['rd']) AND is_array($pl['opt']['rd'])){
								$l = $pl['opt']['rd'][0] * $this->k;
								$r = $pl['opt']['rd'][1] * $this->k;
								$t = $pl['opt']['rd'][2] * $this->k;
								$b = $pl['opt']['rd'][3] * $this->k;
								$annots .= ' /RD ['.sprintf('%.2f %.2f %.2f %.2f', $l, $r, $t, $b).']';
							}
							break;
						}
						case 'line':{
							break;
						}
						case 'square':{
							break;
						}
						case 'circle':{
							break;
						}
						case 'polygon':{
							break;
						}
						case 'polyline':{
							break;
						}
						case 'highlight':{
							break;
						}
						case 'underline':{
							break;
						}
						case 'squiggly':{
							break;
						}
						case 'strikeout':{
							break;
						}
						case 'stamp':{
							break;
						}
						case 'caret':{
							break;
						}
						case 'ink':{
							break;
						}
						case 'popup':{
							break;
						}
						case 'fileattachment':{
							$iconsapp = array('Graph', 'Paperclip', 'PushPin', 'Tag');
							if(isset($pl['opt']['name']) AND in_array($pl['opt']['name'], $iconsapp)){
								$annots .= ' /Name /'.$pl['opt']['name'];
							} else{
								$annots .= ' /Name /PushPin';
							}
							break;
						}
						case 'sound':{
							$iconsapp = array('Speaker', 'Mic');
							if(isset($pl['opt']['name']) AND in_array($pl['opt']['name'], $iconsapp)){
								$annots .= ' /Name /'.$pl['opt']['name'];
							} else{
								$annots .= ' /Name /Speaker';
							}
							break;
						}
						case 'movie':{
							break;
						}
						case 'widget':{
							break;
						}
						case 'screen':{
							break;
						}
						case 'printermark':{
							break;
						}
						case 'trapnet':{
							break;
						}
						case 'watermark':{
							break;
						}
						case '3d':{
							break;
						}
						default:{
							break;
						}
					}
				$annots .= '>>';
				}
				$annots .= "\n]";
				$this->_out($annots);
			}
		}
		protected function _putfonts(){
			$nf = $this->n;
			foreach($this->diffs as $diff){
				$this->_newobj();
				$this->_out('<</Type /Encoding /BaseEncoding /WinAnsiEncoding /Differences ['.$diff.']>>');
				$this->_out('endobj');
			}
			$mqr = get_magic_quotes_runtime();
			set_magic_quotes_runtime(0);
			foreach($this->FontFiles as $file => $info){
				$this->_newobj();
				$this->FontFiles[$file]['n'] = $this->n;
				$font = file_get_contents($this->_getfontpath().strtolower($file));
				$compressed =(substr($file, -2) == '.z');
				if((!$compressed) AND(isset($info['length2']))){
					$header =(ord($font{0}) == 128);
					if($header){
						$font = substr($font,6);
					}
					if($header AND(ord($font{$info['length1']}) == 128)){
						$font = substr($font, 0, $info['length1']).substr($font,($info['length1'] + 6));
					}
				}
				$this->_out('<</Length '.strlen($font));
				if($compressed){
					$this->_out('/Filter /FlateDecode');
				}
				$this->_out('/Length1 '.$info['length1']);
				if(isset($info['length2'])){
					$this->_out('/Length2 '.$info['length2'].' /Length3 0');
				}
				$this->_out('>>');
				$this->_putstream($font);
				$this->_out('endobj');
			}
			set_magic_quotes_runtime($mqr);
			foreach($this->fonts as $k => $font){
				$this->fonts[$k]['n'] = $this->n + 1;
				$type = $font['type'];
				$name = $font['name'];
				if($type == 'core'){
					$this->_newobj();
					$this->_out('<</Type /Font');
					$this->_out('/BaseFont /'.$name);
					$this->_out('/Subtype /Type1');
					if(($name != 'symbol') AND($name != 'zapfdingbats')){
						$this->_out('/Encoding /WinAnsiEncoding');
					}
					$this->_out('>>');
					$this->_out('endobj');
				} elseif(($type == 'Type1') OR($type == 'TrueType')){
					$this->_newobj();
					$this->_out('<</Type /Font');
					$this->_out('/BaseFont /'.$name);
					$this->_out('/Subtype /'.$type);
					$this->_out('/FirstChar 32 /LastChar 255');
					$this->_out('/Widths '.($this->n + 1).' 0 R');
					$this->_out('/FontDescriptor '.($this->n + 2).' 0 R');
					if($font['enc']){
						if(isset($font['diff'])){
							$this->_out('/Encoding '.($nf + $font['diff']).' 0 R');
						} else{
							$this->_out('/Encoding /WinAnsiEncoding');
						}
					}
					$this->_out('>>');
					$this->_out('endobj');
					$this->_newobj();
					$cw = &$font['cw'];
					$s = '[';
					for($i=32; $i <= 255; $i++){
						$s .= $cw[$i].' ';
					}
					$this->_out($s.']');
					$this->_out('endobj');
					$this->_newobj();
					$s = '<</Type /FontDescriptor /FontName /'.$name;
					foreach($font['desc'] as $k => $v){
						$s .= ' /'.$k.' '.$v;
					}
					$file = $font['file'];
					if($file){
						$s .= ' /FontFile'.($type == 'Type1' ? '' : '2').' '.$this->FontFiles[$file]['n'].' 0 R';
					}
					$this->_out($s.'>>');
					$this->_out('endobj');
				} else{
					$mtd = '_put'.strtolower($type);
					if(!method_exists($this, $mtd)){
						$this->Error('Unsupported font type: '.$type);
					}
					$this->$mtd($font);
				}
			}
		}
		protected function _putcidfont0($font){
			if(isset($font['cidinfo']['uni2cid'])){
				$uni2cid = $font['cidinfo']['uni2cid'];
				$cw = array();
				foreach($font['cw'] as $uni => $width){
					if(isset($uni2cid[$uni])){
						$cw[($uni2cid[$uni] + 31)] = $width;
					} elseif($uni <= 255){
						$cw[$uni] = $width;
					}
				}
				ksort($cw);
				$font = array_merge($font, array('cw' => $cw));
			}
			$name = $font['name'];
			$enc = $font['enc'];
			if($enc){
				$longname = $name.'-'.$enc;
			} else{
				$longname = $name;
			}
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/BaseFont /'.$longname);
			$this->_out('/Subtype /Type0');
			if($enc){
				$this->_out('/Encoding /'.$enc);
			}
			$this->_out('/DescendantFonts ['.($this->n + 1).' 0 R]');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/BaseFont /'.$name);
			$this->_out('/Subtype /CIDFontType0');
			$cidinfo = '/Registry '.$this->_datastring($font['cidinfo']['Registry']);
			$cidinfo .= ' /Ordering '.$this->_datastring($font['cidinfo']['Ordering']);
			$cidinfo .= ' /Supplement '.$font['cidinfo']['Supplement'];
			$this->_out('/CIDSystemInfo <<'.$cidinfo.'>>');
			$this->_out('/FontDescriptor '.($this->n + 1).' 0 R');
			$codes = array_keys($font['cw']);
			$first = current($codes);
			$last = end($codes);
			$this->_out('/DW '.$font['dw']);
			$w = '/W [';
			$ranges = array();
			$currange = 0;
			for($i = $first; $i <= $last; $i++){
				if(isset($font['cw'][$i]) AND(!$currange)){
					$currange = $i - 31;
				} elseif(!isset($font['cw'][$i])){
					$currange = 0;
				}
				if($currange){
					$ranges[$currange][] = $font['cw'][$i];
				}
			}
			foreach($ranges as $k => $ws){
				$w .= ' '.$k.' [ '.implode(' ', $ws).' ]';
			}
			$w .= ' ]';
			$this->_out($w);
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$s = '<</Type /FontDescriptor /FontName /'.$name;
			foreach($font['desc'] as $k => $v){
				$s .= ' /'.$k.' '.$v;
			}
			$this->_out($s.'>>');
			$this->_out('endobj');
		}
		protected function _putimages(){
			$filter =($this->compress) ? '/Filter /FlateDecode ' : '';
			reset($this->images);
			while(list($file, $info) = each($this->images)){
				$this->_newobj();
				$this->images[$file]['n'] = $this->n;
				$this->_out('<</Type /XObject');
				$this->_out('/Subtype /Image');
				$this->_out('/Width '.$info['w']);
				$this->_out('/Height '.$info['h']);
				if(isset($info['masked'])){
					$this->_out('/SMask '.($this->n-1).' 0 R');
				}
				if($info['cs'] == 'Indexed'){
					$this->_out('/ColorSpace [/Indexed /DeviceRGB '.(strlen($info['pal']) / 3 - 1).' '.($this->n + 1).' 0 R]');
				} else{
					$this->_out('/ColorSpace /'.$info['cs']);
					if($info['cs'] == 'DeviceCMYK'){
						$this->_out('/Decode [1 0 1 0 1 0 1 0]');
					}
				}
				$this->_out('/BitsPerComponent '.$info['bpc']);
				if(isset($info['f'])){
					$this->_out('/Filter /'.$info['f']);
				}
				if(isset($info['parms'])){
					$this->_out($info['parms']);
				}
				if(isset($info['trns']) and is_array($info['trns'])){
					$trns='';
					for($i=0; $i < count($info['trns']); $i++){
						$trns .= $info['trns'][$i].' '.$info['trns'][$i].' ';
					}
					$this->_out('/Mask ['.$trns.']');
				}
				$this->_out('/Length '.strlen($info['data']).'>>');
				$this->_putstream($info['data']);
				unset($this->images[$file]['data']);
				$this->_out('endobj');
				if($info['cs'] == 'Indexed'){
					$this->_newobj();
					$pal =($this->compress) ? gzcompress($info['pal']) : $info['pal'];
					$this->_out('<<'.$filter.'/Length '.strlen($pal).'>>');
					$this->_putstream($pal);
					$this->_out('endobj');
				}
			}
		}
		protected function _putspotcolors(){
			foreach($this->spot_colors as $name => $color){
				$this->_newobj();
				$this->spot_colors[$name]['n'] = $this->n;
				$this->_out('[/Separation /'.str_replace(' ', '#20', $name));
				$this->_out('/DeviceCMYK <<');
				$this->_out('/Range [0 1 0 1 0 1 0 1] /C0 [0 0 0 0] ');
				$this->_out(sprintf('/C1 [%.4f %.4f %.4f %.4f] ', $color['c']/100, $color['m']/100, $color['y']/100, $color['k']/100));
				$this->_out('/FunctionType 2 /Domain [0 1] /N 1>>]');
				$this->_out('endobj');
			}
		}
		protected function _putxobjectdict(){
			foreach($this->images as $image){
				$this->_out('/I'.$image['i'].' '.$image['n'].' 0 R');
			}
		}
		protected function _putresourcedict(){
			$this->_out('/ProcSet [/PDF /Text /ImageB /ImageC /ImageI]');
			$this->_out('/Font <<');
			foreach($this->fonts as $font){
				$this->_out('/F'.$font['i'].' '.$font['n'].' 0 R');
			}
			$this->_out('>>');
			$this->_out('/XObject <<');
			$this->_putxobjectdict();
			$this->_out('>>');
			$this->_out('/Properties <</OC1 '.$this->n_ocg_print.' 0 R /OC2 '.$this->n_ocg_view.' 0 R>>');
			$this->_out('/ExtGState <<');
			foreach($this->extgstates as $k => $extgstate){
				$this->_out('/GS'.$k.' '.$extgstate['n'].' 0 R');
			}
			$this->_out('>>');
			if(isset($this->gradients) AND(count($this->gradients) > 0)){
				$this->_out('/Shading <<');
				foreach($this->gradients as $id => $grad){
					$this->_out('/Sh'.$id.' '.$grad['id'].' 0 R');
				}
				$this->_out('>>');
			}
			if(isset($this->spot_colors) AND(count($this->spot_colors) > 0)){
				$this->_out('/ColorSpace <<');
				foreach($this->spot_colors as $color){
					$this->_out('/CS'.$color['i'].' '.$color['n'].' 0 R');
				}
				$this->_out('>>');
			}
		}
		protected function _putresources(){
			$this->_putextgstates();
			$this->_putocg();
			$this->_putfonts();
			$this->_putimages();
			$this->_putspotcolors();
			$this->_putshaders();
			$this->offsets[2] = strlen($this->buffer);
			$this->_out('2 0 obj');
			$this->_out('<<');
			$this->_putresourcedict();
			$this->_out('>>');
			$this->_out('endobj');
			$this->_putjavascript();
			$this->_putbookmarks();
			if($this->encrypted){
				$this->_newobj();
				$this->enc_obj_id = $this->n;
				$this->_out('<<');
				$this->_putencryption();
				$this->_out('>>');
				$this->_out('endobj');
			}
		}
		protected function _putinfo(){
			if(!empty($this->title)){
				$this->_out('/Title '.$this->_textstring($this->title));
			}
			if(!empty($this->author)){
				$this->_out('/Author '.$this->_textstring($this->author));
			}
			if(!empty($this->subject)){
				$this->_out('/Subject '.$this->_textstring($this->subject));
			}
			if(!empty($this->keywords)){
				$this->_out('/Keywords '.$this->_textstring($this->keywords));
			}
			if(!empty($this->creator)){
				$this->_out('/Creator '.$this->_textstring($this->creator));
			}
			if(defined('PDF_PRODUCER')){
				$this->_out('/Producer '.$this->_textstring(PDF_PRODUCER));
			}
			$this->_out('/CreationDate '.$this->_datastring('D:'.date('YmdHis')));
			$this->_out('/ModDate '.$this->_datastring('D:'.date('YmdHis')));
		}
		protected function _putcatalog(){
			$this->_out('/Type /Catalog');
			$this->_out('/Pages 1 0 R');
			if($this->ZoomMode == 'fullpage'){
				$this->_out('/OpenAction [3 0 R /Fit]');
			} elseif($this->ZoomMode == 'fullwidth'){
				$this->_out('/OpenAction [3 0 R /FitH null]');
			} elseif($this->ZoomMode == 'real'){
				$this->_out('/OpenAction [3 0 R /XYZ null null 1]');
			} elseif(!is_string($this->ZoomMode)){
				$this->_out('/OpenAction [3 0 R /XYZ null null '.($this->ZoomMode / 100).']');
			}
			if(isset($this->LayoutMode) AND(!empty($this->LayoutMode))){
				$this->_out('/PageLayout /'.$this->LayoutMode.'');
			}
			if(isset($this->PageMode) AND(!empty($this->PageMode))){
				$this->_out('/PageMode /'.$this->PageMode);
			}
			if(isset($this->l['a_meta_language'])){
				$this->_out('/Lang /'.$this->l['a_meta_language']);
			}
			if(!empty($this->javascript)){
				$this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
			}
			if(count($this->outlines) > 0){
				$this->_out('/Outlines '.$this->OutlineRoot.' 0 R');
				$this->_out('/PageMode /UseOutlines');
			}
			$this->_putviewerpreferences();
			$p = $this->n_ocg_print.' 0 R';
			$v = $this->n_ocg_view.' 0 R';
			$as = '<</Event /Print /OCGs ['.$p.' '.$v.'] /Category [/Print]>> <</Event /View /OCGs ['.$p.' '.$v.'] /Category [/View]>>';
			$this->_out('/OCProperties <</OCGs ['.$p.' '.$v.'] /D <</ON ['.$p.'] /OFF ['.$v.'] /AS ['.$as.']>>>>');
			$this->_putuserrights();
		}
		protected function _putviewerpreferences(){
			$this->_out('/ViewerPreferences<<');
			if($this->rtl){
				$this->_out('/Direction /R2L');
			} else{
				$this->_out('/Direction /L2R');
			}
			if(isset($this->viewer_preferences['HideToolbar']) AND($this->viewer_preferences['HideToolbar'])){
				$this->_out('/HideToolbar true');
			}
			if(isset($this->viewer_preferences['HideMenubar']) AND($this->viewer_preferences['HideMenubar'])){
				$this->_out('/HideMenubar true');
			}
			if(isset($this->viewer_preferences['HideWindowUI']) AND($this->viewer_preferences['HideWindowUI'])){
				$this->_out('/HideWindowUI true');
			}
			if(isset($this->viewer_preferences['FitWindow']) AND($this->viewer_preferences['FitWindow'])){
				$this->_out('/FitWindow true');
			}
			if(isset($this->viewer_preferences['CenterWindow']) AND($this->viewer_preferences['CenterWindow'])){
				$this->_out('/CenterWindow true');
			}
			if(isset($this->viewer_preferences['DisplayDocTitle']) AND($this->viewer_preferences['DisplayDocTitle'])){
				$this->_out('/DisplayDocTitle true');
			}
			if(isset($this->viewer_preferences['NonFullScreenPageMode'])){
				$this->_out('/NonFullScreenPageMode /'.$this->viewer_preferences['NonFullScreenPageMode'].'');
			}
			if(isset($this->viewer_preferences['ViewArea'])){
				$this->_out('/ViewArea /'.$this->viewer_preferences['ViewArea']);
			}
			if(isset($this->viewer_preferences['ViewClip'])){
				$this->_out('/ViewClip /'.$this->viewer_preferences['ViewClip']);
			}
			if(isset($this->viewer_preferences['PrintArea'])){
				$this->_out('/PrintArea /'.$this->viewer_preferences['PrintArea']);
			}
			if(isset($this->viewer_preferences['PrintClip'])){
				$this->_out('/PrintClip /'.$this->viewer_preferences['PrintClip']);
			}
			if(isset($this->viewer_preferences['PrintScaling'])){
				$this->_out('/PrintScaling /'.$this->viewer_preferences['PrintScaling']);
			}
			if(isset($this->viewer_preferences['Duplex']) AND(!empty($this->viewer_preferences['Duplex']))){
				$this->_out('/Duplex /'.$this->viewer_preferences['Duplex']);
			}
			if(isset($this->viewer_preferences['PickTrayByPDFSize'])){
				if($this->viewer_preferences['PickTrayByPDFSize']){
					$this->_out('/PickTrayByPDFSize true');
				} else{
					$this->_out('/PickTrayByPDFSize false');
				}
			}
			if(isset($this->viewer_preferences['PrintPageRange'])){
				$PrintPageRangeNum = '';
				foreach($this->viewer_preferences['PrintPageRange'] as $k => $v){
					$PrintPageRangeNum .= ' '.($v - 1).'';
				}
				$this->_out('/PrintPageRange ['.substr($PrintPageRangeNum,1).']');
			}
			if(isset($this->viewer_preferences['NumCopies'])){
				$this->_out('/NumCopies '.intval($this->viewer_preferences['NumCopies']));
			}
			$this->_out('>>');
		}
		protected function _puttrailer(){
			$this->_out('/Size '.($this->n + 1));
			$this->_out('/Root '.$this->n.' 0 R');
			$this->_out('/Info '.($this->n - 1).' 0 R');
			if($this->encrypted){
				$this->_out('/Encrypt '.$this->enc_obj_id.' 0 R');
				$this->_out('/ID [()()]');
			}
		}
		protected function _putheader(){
			$this->_out('%PDF-'.$this->PDFVersion);
		}
		protected function _enddoc(){
			$this->_putheader();
			$this->_putpages();
			$this->_putresources();
			$this->_newobj();
			$this->_out('<<');
			$this->_putinfo();
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<<');
			$this->_putcatalog();
			$this->_out('>>');
			$this->_out('endobj');
			$o = strlen($this->buffer);
			$this->_out('xref');
			$this->_out('0 '.($this->n + 1));
			$this->_out('0000000000 65535 f ');
			for($i=1; $i <= $this->n; $i++){
				$this->_out(sprintf('%010d 00000 n ',$this->offsets[$i]));
			}
			$this->_out('trailer');
			$this->_out('<<');
			$this->_puttrailer();
			$this->_out('>>');
			$this->_out('startxref');
			$this->_out($o);
			$this->_out('%%EOF');
			$this->state = 3;
		}
		protected function _beginpage($orientation='', $format=''){
			$this->page++;
			$this->pages[$this->page] = '';
			$this->state = 2;
			if(empty($orientation)){
				if(isset($this->CurOrientation)){
					$orientation = $this->CurOrientation;
				} else{
					$orientation = 'P';
				}
			}
			if(!empty($format)){
				$this->setPageFormat($format, $orientation);
			} else{
				$this->setPageOrientation($orientation);
			}
			if($this->rtl){
				$this->x = $this->w - $this->rMargin;
			} else{
				$this->x = $this->lMargin;
			}
			$this->y = $this->tMargin;
			if($this->newpagegroup){
				$n = sizeof($this->pagegroups) + 1;
				$alias = '{nb'.$n.'}';
				$this->pagegroups[$alias] = 1;
				$this->currpagegroup = $alias;
				$this->newpagegroup = false;
			} elseif($this->currpagegroup){
				$this->pagegroups[$this->currpagegroup]++;
			}
		}
		protected function _endpage(){
			$this->setVisibility('all');
			$this->state = 1;
		}
		protected function _newobj(){
			$this->n++;
			$this->offsets[$this->n] = strlen($this->buffer);
			$this->_out($this->n.' 0 obj');
		}
		protected function _dounderline($x, $y, $txt){
			$up = $this->CurrentFont['up'];
			$ut = $this->CurrentFont['ut'];
			$w = $this->GetStringWidth($txt);
			return sprintf('%.2f %.2f %.2f %.2f re f', $x * $this->k,($this->h -($y - $up / 1000 * $this->FontSize)) * $this->k, $w * $this->k, -$ut / 1000 * $this->FontSizePt);
		}
		protected function _dolinethrough($x, $y, $txt){
			$up = $this->CurrentFont['up'];
			$ut = $this->CurrentFont['ut'];
			$w = $this->GetStringWidth($txt);
			return sprintf('%.2f %.2f %.2f %.2f re f', $x * $this->k,($this->h -($y -($this->FontSize/2) - $up / 1000 * $this->FontSize)) * $this->k, $w * $this->k, -$ut / 1000 * $this->FontSizePt);
		}
		protected function _freadint($f){
			$a = unpack('Ni', fread($f,4));
			return $a['i'];
		}
		protected function _escape($s){
			return strtr($s, array(')' => '\\)', '(' => '\\(', '\\' => '\\\\', chr(13) => '\r'));
		}
		protected function _datastring($s){
			if($this->encrypted){
				$s = $this->_RC4($this->_objectkey($this->n), $s);
			}
			return '('. $this->_escape($s).')';
		}
		protected function _textstring($s){
			if($this->isunicode){
				$s = $this->UTF8ToUTF16BE($s, true);
			}
			return $this->_datastring($s);
		}
		protected function _escapetext($s){
			if($this->isunicode){
				if(($this->CurrentFont['type'] == 'core') OR($this->CurrentFont['type'] == 'TrueType') OR($this->CurrentFont['type'] == 'Type1')){
					$s = $this->UTF8ToLatin1($s);
				} else{
					$s = $this->utf8StrRev($s, false, $this->tmprtl);
				}
			}
			return $this->_escape($s);
		}
		protected function _putstream($s){
			if($this->encrypted){
				$s = $this->_RC4($this->_objectkey($this->n), $s);
			}
			$this->_out('stream');
			$this->_out($s);
			$this->_out('endstream');
		}
		protected function _out($s){
			if($this->state == 2){
				if(isset($this->footerlen[$this->page]) AND($this->footerlen[$this->page] > 0)){
					$page = substr($this->pages[$this->page], 0, -$this->footerlen[$this->page]);
					$footer = substr($this->pages[$this->page], -$this->footerlen[$this->page]);
					$this->pages[$this->page] = $page.' '.$s."\n".$footer;
				} else{
					$this->pages[$this->page] .= $s."\n";
				}
			} else{
				$this->buffer .= $s."\n";
			}
		}
		protected function _puttruetypeunicode($font){
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /Type0');
			$this->_out('/BaseFont /'.$font['name'].'');
			$this->_out('/Encoding /Identity-H');
			$this->_out('/DescendantFonts ['.($this->n + 1).' 0 R]');
			$this->_out('/ToUnicode '.($this->n + 2).' 0 R');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /Font');
			$this->_out('/Subtype /CIDFontType2');
			$this->_out('/BaseFont /'.$font['name'].'');
			$this->_out('/CIDSystemInfo '.($this->n + 2).' 0 R');
			$this->_out('/FontDescriptor '.($this->n + 3).' 0 R');
			if(isset($font['desc']['MissingWidth'])){
				$this->_out('/DW '.$font['desc']['MissingWidth'].'');
			}
			$w = '';
			foreach($font['cw'] as $cid => $width){
				$w .= ''.$cid.' ['.$width.'] ';
			}
			$this->_out('/W ['.$w.']');
			$this->_out('/CIDToGIDMap '.($this->n + 4).' 0 R');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Length 345>>');
			$this->_out('stream');
			$this->_out('/CIDInit /ProcSet findresource begin');
			$this->_out('12 dict begin');
			$this->_out('begincmap');
			$this->_out('/CIDSystemInfo');
			$this->_out('<</Registry (Adobe)');
			$this->_out('/Ordering (UCS)');
			$this->_out('/Supplement 0');
			$this->_out('>> def');
			$this->_out('/CMapName /Adobe-Identity-UCS def');
			$this->_out('/CMapType 2 def');
			$this->_out('1 begincodespacerange');
			$this->_out('<0000> <FFFF>');
			$this->_out('endcodespacerange');
			$this->_out('1 beginbfrange');
			$this->_out('<0000> <FFFF> <0000>');
			$this->_out('endbfrange');
			$this->_out('endcmap');
			$this->_out('CMapName currentdict /CMap defineresource pop');
			$this->_out('end');
			$this->_out('end');
			$this->_out('endstream');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Registry (Adobe)');
			$this->_out('/Ordering (UCS)');
			$this->_out('/Supplement 0');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<</Type /FontDescriptor');
			$this->_out('/FontName /'.$font['name']);
			foreach($font['desc'] as $key => $value){
				$this->_out('/'.$key.' '.$value);
			}
			if($font['file']){
				$this->_out('/FontFile2 '.$this->FontFiles[$font['file']]['n'].' 0 R');
			}
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$ctgfile = $this->_getfontpath().strtolower($font['ctg']);
			if(!file_exists($ctgfile)){
				$this->Error('Font file not found: '.$ctgfile);
			}
			$size = filesize($ctgfile);
			$this->_out('<</Length '.$size.'');
			if(substr($ctgfile, -2) == '.z'){
				$this->_out('/Filter /FlateDecode');
			}
			$this->_out('>>');
			$this->_putstream(file_get_contents($ctgfile));
			$this->_out('endobj');
		}
		protected function UTF8StringToArray($str){
			if(!$this->isunicode){
				$strarr = array();
				$strlen = strlen($str);
				for($i=0; $i < $strlen; $i++){
					$strarr[] = ord($str{$i});
				}
				return $strarr;
			}
			$unicode = array();
			$bytes  = array();
			$numbytes  = 1;
			$str .= '';
			$length = strlen($str);
			for($i = 0; $i < $length; $i++){
				$char = ord($str{$i});
				if(count($bytes) == 0){
					if($char <= 0x7F){
						$unicode[] = $char;
						$numbytes = 1;
					} elseif(($char >> 0x05) == 0x06){
						$bytes[] =($char - 0xC0) << 0x06;
						$numbytes = 2;
					} elseif(($char >> 0x04) == 0x0E){
						$bytes[] =($char - 0xE0) << 0x0C;
						$numbytes = 3;
					} elseif(($char >> 0x03) == 0x1E){
						$bytes[] =($char - 0xF0) << 0x12;
						$numbytes = 4;
					} else{
						$unicode[] = 0xFFFD;
						$bytes = array();
						$numbytes = 1;
					}
				} elseif(($char >> 0x06) == 0x02){
					$bytes[] = $char - 0x80;
					if(count($bytes) == $numbytes){
						$char = $bytes[0];
						for($j = 1; $j < $numbytes; $j++){
							$char +=($bytes[$j] <<(($numbytes - $j - 1) * 0x06));
						}
						if((($char >= 0xD800) AND($char <= 0xDFFF)) OR($char >= 0x10FFFF)){
							$unicode[] = 0xFFFD;
						} else{
							$unicode[] = $char;
						}
						$bytes = array();
						$numbytes = 1;
					}
				} else{
					$unicode[] = 0xFFFD;
					$bytes = array();
					$numbytes = 1;
				}
			}
			return $unicode;
		}
		protected function UTF8ToUTF16BE($str, $setbom=true){
			if(!$this->isunicode){
				return $str;
			}
			$unicode = $this->UTF8StringToArray($str);
			return $this->arrUTF8ToUTF16BE($unicode, $setbom);
		}
		protected function UTF8ToLatin1($str){
			global $utf8tolatin;
			if(!$this->isunicode){
				return $str;
			}
			$outstr = '';
			$unicode = $this->UTF8StringToArray($str);
			foreach($unicode as $char){
				if($char < 256){
					$outstr .= chr($char);
				} elseif(array_key_exists($char, $utf8tolatin)){
					$outstr .= chr($utf8tolatin[$char]);
				} elseif($char == 0xFFFD){
				} else{
					$outstr .= '?';
				}
			}
			return $outstr;
		}
		protected function arrUTF8ToUTF16BE($unicode, $setbom=true){
			$outstr = '';
			if($setbom){
				$outstr .= "\xFE\xFF";
			}
			foreach($unicode as $char){
				if($char == 0xFFFD){
					$outstr .= "\xFF\xFD";
				} elseif($char < 0x10000){
					$outstr .= chr($char >> 0x08);
					$outstr .= chr($char & 0xFF);
				} else{
					$char -= 0x10000;
					$w1 = 0xD800 |($char >> 0x10);
					$w2 = 0xDC00 |($char & 0x3FF);
					$outstr .= chr($w1 >> 0x08);
					$outstr .= chr($w1 & 0xFF);
					$outstr .= chr($w2 >> 0x08);
					$outstr .= chr($w2 & 0xFF);
				}
			}
			return $outstr;
		}
		public function setHeaderFont($font){
			$this->header_font = $font;
		}
		public function getHeaderFont(){
			return $this->header_font;
		}
		public function setFooterFont($font){
			$this->footer_font = $font;
		}
		public function getFooterFont(){
			return $this->footer_font;
		}
		public function setLanguageArray($language){
			$this->l = $language;
			$this->rtl = $this->l['a_meta_dir']=='rtl' ? true : false;
		}
		public function getPDFData(){
			if($this->state < 3){
				$this->Close();
			}
			return $this->buffer;
		}
		public function addHtmlLink($url, $name, $fill=0, $firstline=false){
			if($url{0} == '#'){
				$page = intval(substr($url, 1));
				$url = $this->AddLink();
				$this->SetLink($url, 0, $page);
			}
			$prevcolor = $this->fgcolor;
			$this->SetTextColor(0, 0, 255);
			$prevstyle = $this->FontStyle;
			$this->SetFont('', $this->FontStyle.'U');
			$ret = $this->Write($this->lasth, $name, $url, $fill, '', false, 0, $firstline);
			$this->SetFont('', $prevstyle);
			$this->SetTextColorArray($prevcolor);
			return $ret;
		}
		protected function convertHTMLColorToDec($color='#000000'){
			global $webcolor;
			$color = preg_replace('/[\s]*/', '', $color);
			$returncolor = array('R' => 0, 'G' => 0, 'B' => 0);
			if(empty($color)){
				return $returncolor;
			}
			if(substr(strtolower($color), 0, 3) == 'rgb'){
				$codes = substr($color, 4);
				$codes = str_replace(')', '', $codes);
				$returncolor = explode(',', $codes, 3);
				return $returncolor;
			}
			if(substr($color, 0, 1) != '#'){
				if(isset($webcolor[strtolower($color)])){
					$color_code = $webcolor[strtolower($color)];
				} else{
					return $returncolor;
				}
			} else{
				$color_code = substr($color, 1);
			}
			switch(strlen($color_code)){
				case 3:{
					$r = substr($color_code, 0, 1);
					$g = substr($color_code, 1, 1);
					$b = substr($color_code, 2, 1);
					$returncolor['R'] = hexdec($r.$r);
					$returncolor['G'] = hexdec($g.$g);
					$returncolor['B'] = hexdec($b.$b);
					break;
				}
				case 6:{
					$returncolor['R'] = hexdec(substr($color_code, 0, 2));
					$returncolor['G'] = hexdec(substr($color_code, 2, 2));
					$returncolor['B'] = hexdec(substr($color_code, 4, 2));
					break;
				}
			}
			return $returncolor;
		}
		public function pixelsToUnits($px){
			return $px / $this->k;
		}
		public function unhtmlentities($text_to_convert){
			return html_entity_decode($text_to_convert, ENT_QUOTES, $this->encoding);
		}
		protected function _objectkey($n){
			return substr($this->_md5_16($this->encryption_key.pack('VXxx',$n)),0,10);
		}
		protected function _putencryption(){
			$this->_out('/Filter /Standard');
			$this->_out('/V 1');
			$this->_out('/R 2');
			$this->_out('/O ('.$this->_escape($this->Ovalue).')');
			$this->_out('/U ('.$this->_escape($this->Uvalue).')');
			$this->_out('/P '.$this->Pvalue);
		}
		protected function _RC4($key, $text){
			if($this->last_rc4_key != $key){
				$k = str_repeat($key, 256/strlen($key)+1);
				$rc4 = range(0,255);
				$j = 0;
				for($i=0; $i < 256; $i++){
					$t = $rc4[$i];
					$j =($j + $t + ord($k{$i})) % 256;
					$rc4[$i] = $rc4[$j];
					$rc4[$j] = $t;
				}
				$this->last_rc4_key = $key;
				$this->last_rc4_key_c = $rc4;
			} else{
				$rc4 = $this->last_rc4_key_c;
			}
			$len = strlen($text);
			$a = 0;
			$b = 0;
			$out = '';
			for($i=0; $i < $len; $i++){
				$a =($a + 1) % 256;
				$t = $rc4[$a];
				$b =($b + $t) % 256;
				$rc4[$a] = $rc4[$b];
				$rc4[$b] = $t;
				$k = $rc4[($rc4[$a] + $rc4[$b]) % 256];
				$out .= chr(ord($text{$i}) ^ $k);
			}
			return $out;
		}
		protected function _md5_16($str){
			return pack('H*',md5($str));
		}
		protected function _Ovalue($user_pass, $owner_pass){
			$tmp = $this->_md5_16($owner_pass);
			$owner_RC4_key = substr($tmp,0,5);
			return $this->_RC4($owner_RC4_key, $user_pass);
		}
		protected function _Uvalue(){
			return $this->_RC4($this->encryption_key, $this->padding);
		}
		protected function _generateencryptionkey($user_pass, $owner_pass, $protection){
			$user_pass = substr($user_pass.$this->padding,0,32);
			$owner_pass = substr($owner_pass.$this->padding,0,32);
			$this->Ovalue = $this->_Ovalue($user_pass, $owner_pass);
			$tmp = $this->_md5_16($user_pass.$this->Ovalue.chr($protection)."\xFF\xFF\xFF");
			$this->encryption_key = substr($tmp,0,5);
			$this->Uvalue = $this->_Uvalue();
			$this->Pvalue = -(($protection^255)+1);
		}
		public function SetProtection($permissions=array(), $user_pass='', $owner_pass=null){
			$options = array('print' => 4, 'modify' => 8, 'copy' => 16, 'annot-forms' => 32);
			$protection = 192;
			foreach($permissions as $permission){
				if(!isset($options[$permission])){
					$this->Error('Incorrect permission: '.$permission);
				}
				$protection += $options[$permission];
			}
			if($owner_pass === null){
				$owner_pass = uniqid(rand());
			}
			$this->encrypted = true;
			$this->_generateencryptionkey($user_pass, $owner_pass, $protection);
		}
		public function StartTransform(){
			$this->_out('q');
		}
		public function StopTransform(){
			$this->_out('Q');
			if(isset($this->transfmatrix)){
				array_pop($this->transfmatrix);
			}
		}
		public function ScaleX($s_x, $x='', $y=''){
			$this->Scale($s_x, 100, $x, $y);
		}
		public function ScaleY($s_y, $x='', $y=''){
			$this->Scale(100, $s_y, $x, $y);
		}
		public function ScaleXY($s, $x='', $y=''){
			$this->Scale($s, $s, $x, $y);
		}
		public function Scale($s_x, $s_y, $x='', $y=''){
			if($x === ''){
				$x = $this->x;
			}
			if($y === ''){
				$y = $this->y;
			}
			if($this->rtl){
				$x = $this->w - $x;
			}
			if(($s_x == 0) OR($s_y == 0)){
				$this->Error('Please do not use values equal to zero for scaling');
			}
			$y =($this->h - $y) * $this->k;
			$x *= $this->k;
			$s_x /= 100;
			$s_y /= 100;
			$tm[0] = $s_x;
			$tm[1] = 0;
			$tm[2] = 0;
			$tm[3] = $s_y;
			$tm[4] = $x *(1 - $s_x);
			$tm[5] = $y *(1 - $s_y);
			$this->Transform($tm);
		}
		public function MirrorH($x=''){
			$this->Scale(-100, 100, $x);
		}
		public function MirrorV($y=''){
			$this->Scale(100, -100, '', $y);
		}
		public function MirrorP($x='',$y=''){
			$this->Scale(-100, -100, $x, $y);
		}
		public function MirrorL($angle=0, $x='',$y=''){
			$this->Scale(-100, 100, $x, $y);
			$this->Rotate(-2*($angle-90), $x, $y);
		}
		public function TranslateX($t_x){
			$this->Translate($t_x, 0);
		}
		public function TranslateY($t_y){
			$this->Translate(0, $t_y);
		}
		public function Translate($t_x, $t_y){
			if($this->rtl){
				$t_x = -$t_x;
			}
			$tm[0] = 1;
			$tm[1] = 0;
			$tm[2] = 0;
			$tm[3] = 1;
			$tm[4] = $t_x * $this->k;
			$tm[5] = -$t_y * $this->k;
			$this->Transform($tm);
		}
		public function Rotate($angle, $x='', $y=''){
			if($x === ''){
				$x = $this->x;
			}
			if($y === ''){
				$y = $this->y;
			}
			if($this->rtl){
				$x = $this->w - $x;
				$angle = -$angle;
			}
			$y =($this->h - $y) * $this->k;
			$x *= $this->k;
			$tm[0] = cos(deg2rad($angle));
			$tm[1] = sin(deg2rad($angle));
			$tm[2] = -$tm[1];
			$tm[3] = $tm[0];
			$tm[4] = $x +($tm[1] * $y) -($tm[0] * $x);
			$tm[5] = $y -($tm[0] * $y) -($tm[1] * $x);
			$this->Transform($tm);
		}
		public function SkewX($angle_x, $x='', $y=''){
			$this->Skew($angle_x, 0, $x, $y);
		}
		public function SkewY($angle_y, $x='', $y=''){
			$this->Skew(0, $angle_y, $x, $y);
		}
		public function Skew($angle_x, $angle_y, $x='', $y=''){
			if($x === ''){
				$x = $this->x;
			}
			if($y === ''){
				$y = $this->y;
			}
			if($this->rtl){
				$x = $this->w - $x;
				$angle_x = -$angle_x;
			}
			if(($angle_x <= -90) OR($angle_x >= 90) OR($angle_y <= -90) OR($angle_y >= 90)){
				$this->Error('Please use values between -90 and +90 degrees for Skewing.');
			}
			$x *= $this->k;
			$y =($this->h - $y) * $this->k;
			$tm[0] = 1;
			$tm[1] = tan(deg2rad($angle_y));
			$tm[2] = tan(deg2rad($angle_x));
			$tm[3] = 1;
			$tm[4] = -$tm[2] * $y;
			$tm[5] = -$tm[1] * $x;
			$this->Transform($tm);
		}
		protected function Transform($tm){
			$this->_out(sprintf('%.3f %.3f %.3f %.3f %.3f %.3f cm', $tm[0], $tm[1], $tm[2], $tm[3], $tm[4], $tm[5]));
			$this->transfmatrix[] = array('a' => $tm[0], 'b' => $tm[1], 'c' => $tm[2], 'd' => $tm[3], 'e' => $tm[4], 'f' => $tm[5]);
		}
		public function SetLineWidth($width){
			$this->LineWidth = $width;
			$this->linestyleWidth = sprintf('%.2f w',($width * $this->k));
			$this->_out($this->linestyleWidth);
		}
		public function GetLineWidth(){
			return $this->LineWidth;
		}
		public function SetLineStyle($style){
			extract($style);
			if(isset($width)){
				$width_prev = $this->LineWidth;
				$this->SetLineWidth($width);
				$this->LineWidth = $width_prev;
			}
			if(isset($cap)){
				$ca = array('butt' => 0, 'round'=> 1, 'square' => 2);
				if(isset($ca[$cap])){
					$this->linestyleCap = $ca[$cap].' J';
					$this->_out($this->linestyleCap);
				}
			}
			if(isset($join)){
				$ja = array('miter' => 0, 'round' => 1, 'bevel' => 2);
				if(isset($ja[$join])){
					$this->linestyleJoin = $ja[$join].' j';
					$this->_out($this->linestyleJoin);
				}
			}
			if(isset($dash)){
				$dash_string = '';
				if($dash){
					if(ereg('^.+,', $dash)){
						$tab = explode(',', $dash);
					} else{
						$tab = array($dash);
					}
					$dash_string = '';
					foreach($tab as $i => $v){
						if($i){
							$dash_string .= ' ';
						}
						$dash_string .= sprintf("%.2f", $v);
					}
				}
				if(!isset($phase) OR !$dash){
					$phase = 0;
				}
				$this->linestyleDash = sprintf("[%s] %.2f d", $dash_string, $phase);
				$this->_out($this->linestyleDash);
			}
			if(isset($color)){
				$this->SetDrawColorArray($color);
			}
		}
		protected function _outPoint($x, $y){
			if($this->rtl){
				$x = $this->w - $x;
			}
			$this->_out(sprintf("%.2f %.2f m", $x * $this->k,($this->h - $y) * $this->k));
		}
		protected function _outLine($x, $y){
			if($this->rtl){
				$x = $this->w - $x;
			}
			$this->_out(sprintf("%.2f %.2f l", $x * $this->k,($this->h - $y) * $this->k));
		}
		protected function _outRect($x, $y, $w, $h, $op){
			if($this->rtl){
				$x = $this->w - $x - $w;
			}
			$this->_out(sprintf('%.2f %.2f %.2f %.2f re %s', $x*$this->k,($this->h-$y)*$this->k, $w*$this->k, -$h*$this->k, $op));
		}
		protected function _outCurve($x1, $y1, $x2, $y2, $x3, $y3){
			if($this->rtl){
				$x1 = $this->w - $x1;
				$x2 = $this->w - $x2;
				$x3 = $this->w - $x3;
			}
			$this->_out(sprintf("%.2f %.2f %.2f %.2f %.2f %.2f c", $x1 * $this->k,($this->h - $y1) * $this->k, $x2 * $this->k,($this->h - $y2) * $this->k, $x3 * $this->k,($this->h - $y3) * $this->k));
		}
		public function Line($x1, $y1, $x2, $y2, $style=array()){
			if($style){
				$this->SetLineStyle($style);
			}
			$this->_outPoint($x1, $y1);
			$this->_outLine($x2, $y2);
			$this->_out(' S');
		}
		public function Rect($x, $y, $w, $h, $style='', $border_style=array(), $fill_color=array()){
			if(!(false === strpos($style, 'F')) AND isset($fill_color)){
				$this->SetFillColorArray($fill_color);
			}
			switch($style){
				case 'F':{
					$op = 'f';
					$border_style = array();
					$this->_outRect($x, $y, $w, $h, $op);
					break;
				}
				case 'DF':
				case 'FD':{
					if((!$border_style) OR(isset($border_style['all']))){
						$op = 'B';
						if(isset($border_style['all'])){
							$this->SetLineStyle($border_style['all']);
							$border_style = array();
						}
					} else{
						$op = 'f';
					}
					$this->_outRect($x, $y, $w, $h, $op);
					break;
				}
				case 'CNZ':{
					$op = 'W n';
					break;
				}
				case 'CEO':{
					$op = 'W* n';
					break;
				}
				default:{
					$op = 'S';
					if((!$border_style) OR(isset($border_style['all']))){
						if(isset($border_style['all']) AND $border_style['all']){
							$this->SetLineStyle($border_style['all']);
							$border_style = array();
						}
						$this->_outRect($x, $y, $w, $h, $op);
					}
					break;
				}
			}
			if($border_style){
				$border_style2 = array();
				foreach($border_style as $line => $value){
					$lenght = strlen($line);
					for($i = 0; $i < $lenght; $i++){
						$border_style2[$line[$i]] = $value;
					}
				}
				$border_style = $border_style2;
				if(isset($border_style['L']) AND $border_style['L']){
					$this->Line($x, $y, $x, $y + $h, $border_style['L']);
				}
				if(isset($border_style['T']) AND $border_style['T']){
					$this->Line($x, $y, $x + $w, $y, $border_style['T']);
				}
				if(isset($border_style['R']) AND $border_style['R']){
					$this->Line($x + $w, $y, $x + $w, $y + $h, $border_style['R']);
				}
				if(isset($border_style['B']) AND $border_style['B']){
					$this->Line($x, $y + $h, $x + $w, $y + $h, $border_style['B']);
				}
			}
		}
		public function Curve($x0, $y0, $x1, $y1, $x2, $y2, $x3, $y3, $style='', $line_style=array(), $fill_color=array()){
			if(!(false === strpos($style, 'F')) AND isset($fill_color)){
				$this->SetFillColorArray($fill_color);
			}
			switch($style){
				case 'F':{
					$op = 'f';
					$line_style = array();
					break;
				}
				case 'FD':
				case 'DF':{
					$op = 'B';
					break;
				}
				case 'CNZ':{
					$op = 'W n';
					break;
				}
				case 'CEO':{
					$op = 'W* n';
					break;
				}
				default:{
					$op = 'S';
					break;
				}
			}
			if($line_style){
				$this->SetLineStyle($line_style);
			}
			$this->_outPoint($x0, $y0);
			$this->_outCurve($x1, $y1, $x2, $y2, $x3, $y3);
			$this->_out($op);
		}
		public function Polycurve($x0, $y0, $segments, $style='', $line_style=array(), $fill_color=array()){
			if(!(false === strpos($style, 'F')) AND isset($fill_color)){
				$this->SetFillColorArray($fill_color);
			}
			switch($style){
				case 'F':{
					$op = 'f';
					$line_style = array();
					break;
				}
				case 'FD':
				case 'DF':{
					$op = 'B';
					break;
				}
				case 'CNZ':{
					$op = 'W n';
					break;
				}
				case 'CEO':{
					$op = 'W* n';
					break;
				}
				default:{
					$op = 'S';
					break;
				}
			}
			if($line_style){
				$this->SetLineStyle($line_style);
			}
			$this->_outPoint($x0, $y0);
			foreach($segments as $segment){
				list($x1, $y1, $x2, $y2, $x3, $y3) = $segment;
				$this->_outCurve($x1, $y1, $x2, $y2, $x3, $y3);
			}
			$this->_out($op);
		}
		public function Ellipse($x0, $y0, $rx, $ry=0, $angle=0, $astart=0, $afinish=360, $style='', $line_style=array(), $fill_color=array(), $nc=8){
			if($angle){
				$this->StartTransform();
				$this->Rotate($angle, $x0, $y0);
				$this->Ellipse($x0, $y0, $rx, $ry, 0, $astart, $afinish, $style, $line_style, $fill_color, $nc);
				$this->StopTransform();
				return;
			}
			if($rx){
				if(!(false === strpos($style, 'F')) AND isset($fill_color)){
					$this->SetFillColorArray($fill_color);
				}
				switch($style){
					case 'F':{
						$op = 'f';
						$line_style = array();
						break;
					}
					case 'FD':
					case 'DF':{
						$op = 'B';
						break;
					}
					case 'C':{
						$op = 's';
						break;
					}
					case 'CNZ':{
						$op = 'W n';
						break;
					}
					case 'CEO':{
						$op = 'W* n';
						break;
					}
					default:{
						$op = 'S';
						break;
					}
				}
				if($line_style){
					$this->SetLineStyle($line_style);
				}
				if(!$ry){
					$ry = $rx;
				}
				$rx *= $this->k;
				$ry *= $this->k;
				if($nc < 2){
					$nc = 2;
				}
				$astart = deg2rad((float) $astart);
				$afinish = deg2rad((float) $afinish);
				$total_angle = $afinish - $astart;
				$dt = $total_angle / $nc;
				$dtm = $dt / 3;
				$x0 *= $this->k;
				$y0 =($this->h - $y0) * $this->k;
				$t1 = $astart;
				$a0 = $x0 +($rx * cos($t1));
				$b0 = $y0 +($ry * sin($t1));
				$c0 = -$rx * sin($t1);
				$d0 = $ry * cos($t1);
				$this->_outPoint($a0 / $this->k, $this->h -($b0 / $this->k));
				for($i = 1; $i <= $nc; $i++){
					$t1 =($i * $dt) + $astart;
					$a1 = $x0 +($rx * cos($t1));
					$b1 = $y0 +($ry * sin($t1));
					$c1 = -$rx * sin($t1);
					$d1 = $ry * cos($t1);
					$this->_outCurve(($a0 +($c0 * $dtm)) / $this->k, $this->h -(($b0 +($d0 * $dtm)) / $this->k),($a1 -($c1 * $dtm)) / $this->k, $this->h -(($b1 -($d1 * $dtm)) / $this->k), $a1 / $this->k, $this->h -($b1 / $this->k));
					$a0 = $a1;
					$b0 = $b1;
					$c0 = $c1;
					$d0 = $d1;
				}
				$this->_out($op);
			}
		}
		public function Circle($x0, $y0, $r, $astart=0, $afinish=360, $style='', $line_style=array(), $fill_color=array(), $nc=8){
			$this->Ellipse($x0, $y0, $r, 0, 0, $astart, $afinish, $style, $line_style, $fill_color, $nc);
		}
		public function Polygon($p, $style='', $line_style=array(), $fill_color=array()){
			$np = count($p) / 2;
			if(!(false === strpos($style, 'F')) AND isset($fill_color)){
				$this->SetFillColorArray($fill_color);
			}
			switch($style){
				case 'F':{
					$line_style = array();
					$op = 'f';
					break;
				}
				case 'FD':
				case 'DF':{
					$op = 'B';
					break;
				}
				case 'CNZ':{
					$op = 'W n';
					break;
				}
				case 'CEO':{
					$op = 'W* n';
					break;
				}
				default:{
					$op = 'S';
					break;
				}
			}
			$draw = true;
			if($line_style){
				if(isset($line_style['all'])){
					$this->SetLineStyle($line_style['all']);
				} else{
					$draw = false;
					if('B' == $op){
						$op = 'f';
						$this->_outPoint($p[0], $p[1]);
						for($i = 2; $i <($np * 2); $i = $i + 2){
							$this->_outLine($p[$i], $p[$i + 1]);
						}
						$this->_outLine($p[0], $p[1]);
						$this->_out($op);
					}
					$p[($np * 2)] = $p[0];
					$p[(($np * 2) + 1)] = $p[1];
					for($i = 0; $i < $np; $i++){
						if(isset($line_style[$i]) AND($line_style[$i] != 0)){
							$this->Line($p[($i * 2)], $p[(($i * 2) + 1)], $p[(($i * 2) + 2)], $p[(($i * 2) + 3)], $line_style[$i]);
						}
					}
				}
			}
			if($draw){
				$this->_outPoint($p[0], $p[1]);
				for($i = 2; $i <($np * 2); $i = $i + 2){
					$this->_outLine($p[$i], $p[$i + 1]);
				}
				$this->_outLine($p[0], $p[1]);
				$this->_out($op);
			}
		}
		public function RegularPolygon($x0, $y0, $r, $ns, $angle=0, $draw_circle=false, $style='', $line_style=array(), $fill_color=array(), $circle_style='', $circle_outLine_style=array(), $circle_fill_color=array()){
			if(3 > $ns){
				$ns = 3;
			}
			if($draw_circle){
				$this->Circle($x0, $y0, $r, 0, 360, $circle_style, $circle_outLine_style, $circle_fill_color);
			}
			$p = array();
			for($i = 0; $i < $ns; $i++){
				$a = $angle +($i * 360 / $ns);
				$a_rad = deg2rad((float) $a);
				$p[] = $x0 +($r * sin($a_rad));
				$p[] = $y0 +($r * cos($a_rad));
			}
			$this->Polygon($p, $style, $line_style, $fill_color);
		}
		public function StarPolygon($x0, $y0, $r, $nv, $ng, $angle=0, $draw_circle=false, $style='', $line_style=array(), $fill_color=array(), $circle_style='', $circle_outLine_style=array(), $circle_fill_color=array()){
			if(2 > $nv){
				$nv = 2;
			}
			if($draw_circle){
				$this->Circle($x0, $y0, $r, 0, 360, $circle_style, $circle_outLine_style, $circle_fill_color);
			}
			$p2 = array();
			$visited = array();
			for($i = 0; $i < $nv; $i++){
				$a = $angle +($i * 360 / $nv);
				$a_rad = deg2rad((float) $a);
				$p2[] = $x0 +($r * sin($a_rad));
				$p2[] = $y0 +($r * cos($a_rad));
				$visited[] = false;
			}
			$p = array();
			$i = 0;
			do{
				$p[] = $p2[$i * 2];
				$p[] = $p2[($i * 2) + 1];
				$visited[$i] = true;
				$i += $ng;
				$i %= $nv;
			} while(!$visited[$i]);
			$this->Polygon($p, $style, $line_style, $fill_color);
		}
		public function RoundedRect($x, $y, $w, $h, $r, $round_corner='1111', $style='', $border_style=array(), $fill_color=array()){
			if('0000' == $round_corner){
				$this->Rect($x, $y, $w, $h, $style, $border_style, $fill_color);
			} else{
				if(!(false === strpos($style, 'F')) AND isset($fill_color)){
					$this->SetFillColorArray($fill_color);
				}
				switch($style){
					case 'F':{
						$border_style = array();
						$op = 'f';
						break;
					}
					case 'FD':
					case 'DF':{
						$op = 'B';
						break;
					}
					case 'CNZ':{
						$op = 'W n';
						break;
					}
					case 'CEO':{
						$op = 'W* n';
						break;
					}
					default:{
						$op = 'S';
						break;
					}
				}
				if($border_style){
					$this->SetLineStyle($border_style);
				}
				$MyArc = 4 / 3 *(sqrt(2) - 1);
				$this->_outPoint($x + $r, $y);
				$xc = $x + $w - $r;
				$yc = $y + $r;
				$this->_outLine($xc, $y);
				if($round_corner[0]){
					$this->_outCurve($xc +($r * $MyArc), $yc - $r, $xc + $r, $yc -($r * $MyArc), $xc + $r, $yc);
				} else{
					$this->_outLine($x + $w, $y);
				}
				$xc = $x + $w - $r;
				$yc = $y + $h - $r;
				$this->_outLine($x + $w, $yc);
				if($round_corner[1]){
					$this->_outCurve($xc + $r, $yc +($r * $MyArc), $xc +($r * $MyArc), $yc + $r, $xc, $yc + $r);
				} else{
					$this->_outLine($x + $w, $y + $h);
				}
				$xc = $x + $r;
				$yc = $y + $h - $r;
				$this->_outLine($xc, $y + $h);
				if($round_corner[2]){
					$this->_outCurve($xc -($r * $MyArc), $yc + $r, $xc - $r, $yc +($r * $MyArc), $xc - $r, $yc);
				} else{
					$this->_outLine($x, $y + $h);
				}
				$xc = $x + $r;
				$yc = $y + $r;
				$this->_outLine($x, $yc);
				if($round_corner[3]){
					$this->_outCurve($xc - $r, $yc -($r * $MyArc), $xc -($r * $MyArc), $yc - $r, $xc, $yc - $r);
				} else{
					$this->_outLine($x, $y);
					$this->_outLine($x + $r, $y);
				}
				$this->_out($op);
			}
		}
		protected function utf8StrRev($str, $setbom=false, $forcertl=false){
			return $this->arrUTF8ToUTF16BE($this->utf8Bidi($this->UTF8StringToArray($str), $forcertl), $setbom);
		}
		protected function utf8Bidi($ta, $forcertl=false){
			global $unicode, $unicode_mirror, $unicode_arlet, $laa_array, $diacritics;
			$pel = 0;
			$maxlevel = 0;
			$str = $this->UTF8ArrSubString($ta);
			if(preg_match(K_RE_PATTERN_ARABIC, $str)){
				$arabic = true;
			} else{
				$arabic = false;
			}
			if(!($forcertl OR $arabic OR preg_match(K_RE_PATTERN_RTL, $str))){
				return $ta;
			}
			$numchars = count($ta);
			if($forcertl == 'R'){
					$pel = 1;
			} elseif($forcertl == 'L'){
					$pel = 0;
			} else{
				for($i=0; $i < $numchars; $i++){
					$type = $unicode[$ta[$i]];
					if($type == 'L'){
						$pel = 0;
						break;
					} elseif(($type == 'AL') OR($type == 'R')){
						$pel = 1;
						break;
					}
				}
			}
			$cel = $pel;
			$dos = 'N';
			$remember = array();
			$sor = $pel % 2 ? 'R' : 'L';
			$eor = $sor;
			$chardata = Array();
			for($i=0; $i < $numchars; $i++){
				if($ta[$i] == K_RLE){
					$next_level = $cel +($cel % 2) + 1;
					if($next_level < 62){
						$remember[] = array('num' => K_RLE, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'N';
						$sor = $eor;
						$eor = $cel % 2 ? 'R' : 'L';
					}
				} elseif($ta[$i] == K_LRE){
					$next_level = $cel + 2 -($cel % 2);
					if( $next_level < 62){
						$remember[] = array('num' => K_LRE, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'N';
						$sor = $eor;
						$eor = $cel % 2 ? 'R' : 'L';
					}
				} elseif($ta[$i] == K_RLO){
					$next_level = $cel +($cel % 2) + 1;
					if($next_level < 62){
						$remember[] = array('num' => K_RLO, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'R';
						$sor = $eor;
						$eor = $cel % 2 ? 'R' : 'L';
					}
				} elseif($ta[$i] == K_LRO){
					$next_level = $cel + 2 -($cel % 2);
					if( $next_level < 62){
						$remember[] = array('num' => K_LRO, 'cel' => $cel, 'dos' => $dos);
						$cel = $next_level;
						$dos = 'L';
						$sor = $eor;
						$eor = $cel % 2 ? 'R' : 'L';
					}
				} elseif($ta[$i] == K_PDF){
					if(count($remember)){
						$last = count($remember) - 1;
						if(($remember[$last]['num'] == K_RLE) OR
							  ($remember[$last]['num'] == K_LRE) OR
							  ($remember[$last]['num'] == K_RLO) OR
							  ($remember[$last]['num'] == K_LRO)){
							$match = array_pop($remember);
							$cel = $match['cel'];
							$dos = $match['dos'];
							$sor = $eor;
							$eor =($cel > $match['cel'] ? $cel : $match['cel']) % 2 ? 'R' : 'L';
						}
					}
				} elseif(($ta[$i] != K_RLE) AND
								 ($ta[$i] != K_LRE) AND
								 ($ta[$i] != K_RLO) AND
								 ($ta[$i] != K_LRO) AND
								 ($ta[$i] != K_PDF)){
					if($dos != 'N'){
						$chardir = $dos;
					} else{
						$chardir = $unicode[$ta[$i]];
					}
					$chardata[] = array('char' => $ta[$i], 'level' => $cel, 'type' => $chardir, 'sor' => $sor, 'eor' => $eor);
				}
			}
			$numchars = count($chardata);
			$prevlevel = -1;
			$levcount = 0;
			for($i=0; $i < $numchars; $i++){
				if($chardata[$i]['type'] == 'NSM'){
					if($levcount){
						$chardata[$i]['type'] = $chardata[$i]['sor'];
					} elseif($i > 0){
						$chardata[$i]['type'] = $chardata[($i-1)]['type'];
					}
				}
				if($chardata[$i]['level'] != $prevlevel){
					$levcount = 0;
				} else{
					$levcount++;
				}
				$prevlevel = $chardata[$i]['level'];
			}
			$prevlevel = -1;
			$levcount = 0;
			for($i=0; $i < $numchars; $i++){
				if($chardata[$i]['char'] == 'EN'){
					for($j=$levcount; $j >= 0; $j--){
						if($chardata[$j]['type'] == 'AL'){
							$chardata[$i]['type'] = 'AN';
						} elseif(($chardata[$j]['type'] == 'L') OR($chardata[$j]['type'] == 'R')){
							break;
						}
					}
				}
				if($chardata[$i]['level'] != $prevlevel){
					$levcount = 0;
				} else{
					$levcount++;
				}
				$prevlevel = $chardata[$i]['level'];
			}
			for($i=0; $i < $numchars; $i++){
				if($chardata[$i]['type'] == 'AL'){
					$chardata[$i]['type'] = 'R';
				}
			}
			$prevlevel = -1;
			$levcount = 0;
			for($i=0; $i < $numchars; $i++){
				if(($levcount > 0) AND(($i+1) < $numchars) AND($chardata[($i+1)]['level'] == $prevlevel)){
					if(($chardata[$i]['type'] == 'ES') AND($chardata[($i-1)]['type'] == 'EN') AND($chardata[($i+1)]['type'] == 'EN')){
						$chardata[$i]['type'] = 'EN';
					} elseif(($chardata[$i]['type'] == 'CS') AND($chardata[($i-1)]['type'] == 'EN') AND($chardata[($i+1)]['type'] == 'EN')){
						$chardata[$i]['type'] = 'EN';
					} elseif(($chardata[$i]['type'] == 'CS') AND($chardata[($i-1)]['type'] == 'AN') AND($chardata[($i+1)]['type'] == 'AN')){
						$chardata[$i]['type'] = 'AN';
					}
				}
				if($chardata[$i]['level'] != $prevlevel){
					$levcount = 0;
				} else{
					$levcount++;
				}
				$prevlevel = $chardata[$i]['level'];
			}
			$prevlevel = -1;
			$levcount = 0;
			for($i=0; $i < $numchars; $i++){
				if($chardata[$i]['type'] == 'ET'){
					if(($levcount > 0) AND($chardata[($i-1)]['type'] == 'EN')){
						$chardata[$i]['type'] = 'EN';
					} else{
						$j = $i+1;
						while(($j < $numchars) AND($chardata[$j]['level'] == $prevlevel)){
							if($chardata[$j]['type'] == 'EN'){
								$chardata[$i]['type'] = 'EN';
								break;
							} elseif($chardata[$j]['type'] != 'ET'){
								break;
							}
							$j++;
						}
					}
				}
				if($chardata[$i]['level'] != $prevlevel){
					$levcount = 0;
				} else{
					$levcount++;
				}
				$prevlevel = $chardata[$i]['level'];
			}
			$prevlevel = -1;
			$levcount = 0;
			for($i=0; $i < $numchars; $i++){
				if(($chardata[$i]['type'] == 'ET') OR($chardata[$i]['type'] == 'ES') OR($chardata[$i]['type'] == 'CS')){
					$chardata[$i]['type'] = 'ON';
				}
				if($chardata[$i]['level'] != $prevlevel){
					$levcount = 0;
				} else{
					$levcount++;
				}
				$prevlevel = $chardata[$i]['level'];
			}
			$prevlevel = -1;
			$levcount = 0;
			for($i=0; $i < $numchars; $i++){
				if($chardata[$i]['char'] == 'EN'){
					for($j=$levcount; $j >= 0; $j--){
						if($chardata[$j]['type'] == 'L'){
							$chardata[$i]['type'] = 'L';
						} elseif($chardata[$j]['type'] == 'R'){
							break;
						}
					}
				}
				if($chardata[$i]['level'] != $prevlevel){
					$levcount = 0;
				} else{
					$levcount++;
				}
				$prevlevel = $chardata[$i]['level'];
			}
			$prevlevel = -1;
			$levcount = 0;
			for($i=0; $i < $numchars; $i++){
				if(($levcount > 0) AND(($i+1) < $numchars) AND($chardata[($i+1)]['level'] == $prevlevel)){
					if(($chardata[$i]['type'] == 'N') AND($chardata[($i-1)]['type'] == 'L') AND($chardata[($i+1)]['type'] == 'L')){
						$chardata[$i]['type'] = 'L';
					} elseif(($chardata[$i]['type'] == 'N') AND
					 (($chardata[($i-1)]['type'] == 'R') OR($chardata[($i-1)]['type'] == 'EN') OR($chardata[($i-1)]['type'] == 'AN')) AND
					 (($chardata[($i+1)]['type'] == 'R') OR($chardata[($i+1)]['type'] == 'EN') OR($chardata[($i+1)]['type'] == 'AN'))){
						$chardata[$i]['type'] = 'R';
					} elseif($chardata[$i]['type'] == 'N'){
						$chardata[$i]['type'] = $chardata[$i]['sor'];
					}
				} elseif(($levcount == 0) AND(($i+1) < $numchars) AND($chardata[($i+1)]['level'] == $prevlevel)){
					if(($chardata[$i]['type'] == 'N') AND($chardata[$i]['sor'] == 'L') AND($chardata[($i+1)]['type'] == 'L')){
						$chardata[$i]['type'] = 'L';
					} elseif(($chardata[$i]['type'] == 'N') AND
					 (($chardata[$i]['sor'] == 'R') OR($chardata[$i]['sor'] == 'EN') OR($chardata[$i]['sor'] == 'AN')) AND
					 (($chardata[($i+1)]['type'] == 'R') OR($chardata[($i+1)]['type'] == 'EN') OR($chardata[($i+1)]['type'] == 'AN'))){
						$chardata[$i]['type'] = 'R';
					} elseif($chardata[$i]['type'] == 'N'){
						$chardata[$i]['type'] = $chardata[$i]['sor'];
					}
				} elseif(($levcount > 0) AND((($i+1) == $numchars) OR(($i+1) < $numchars) AND($chardata[($i+1)]['level'] != $prevlevel))){
					if(($chardata[$i]['type'] == 'N') AND($chardata[($i-1)]['type'] == 'L') AND($chardata[$i]['eor'] == 'L')){
						$chardata[$i]['type'] = 'L';
					} elseif(($chardata[$i]['type'] == 'N') AND
					 (($chardata[($i-1)]['type'] == 'R') OR($chardata[($i-1)]['type'] == 'EN') OR($chardata[($i-1)]['type'] == 'AN')) AND
					 (($chardata[$i]['eor'] == 'R') OR($chardata[$i]['eor'] == 'EN') OR($chardata[$i]['eor'] == 'AN'))){
						$chardata[$i]['type'] = 'R';
					} elseif($chardata[$i]['type'] == 'N'){
						$chardata[$i]['type'] = $chardata[$i]['sor'];
					}
				} elseif($chardata[$i]['type'] == 'N'){
					$chardata[$i]['type'] = $chardata[$i]['sor'];
				}
				if($chardata[$i]['level'] != $prevlevel){
					$levcount = 0;
				} else{
					$levcount++;
				}
				$prevlevel = $chardata[$i]['level'];
			}
			for($i=0; $i < $numchars; $i++){
				$odd = $chardata[$i]['level'] % 2;
				if($odd){
					if(($chardata[$i]['type'] == 'L') OR($chardata[$i]['type'] == 'AN') OR($chardata[$i]['type'] == 'EN')){
						$chardata[$i]['level'] += 1;
					}
				} else{
					if($chardata[$i]['type'] == 'R'){
						$chardata[$i]['level'] += 1;
					} elseif(($chardata[$i]['type'] == 'AN') OR($chardata[$i]['type'] == 'EN')){
						$chardata[$i]['level'] += 2;
					}
				}
				$maxlevel = max($chardata[$i]['level'],$maxlevel);
			}
			for($i=0; $i < $numchars; $i++){
				if(($chardata[$i]['type'] == 'B') OR($chardata[$i]['type'] == 'S')){
					$chardata[$i]['level'] = $pel;
				} elseif($chardata[$i]['type'] == 'WS'){
					$j = $i+1;
					while($j < $numchars){
						if((($chardata[$j]['type'] == 'B') OR($chardata[$j]['type'] == 'S')) OR
							(($j ==($numchars-1)) AND($chardata[$j]['type'] == 'WS'))){
							$chardata[$i]['level'] = $pel;
							break;
						} elseif($chardata[$j]['type'] != 'WS'){
							break;
						}
						$j++;
					}
				}
			}
			if($arabic){
				$endedletter = array(1569,1570,1571,1572,1573,1575,1577,1583,1584,1585,1586,1608,1688);
				$alfletter = array(1570,1571,1573,1575);
				$chardata2 = $chardata;
				$laaletter = false;
				$charAL = array();
				$x = 0;
				for($i=0; $i < $numchars; $i++){
					if(($unicode[$chardata[$i]['char']] == 'AL') OR($chardata[$i]['char'] == 32) OR($chardata[$i]['char'] == 8204)){
						$charAL[$x] = $chardata[$i];
						$charAL[$x]['i'] = $i;
						$chardata[$i]['x'] = $x;
						$x++;
					}
				}
				$numAL = $x;
				for($i=0; $i < $numchars; $i++){
					$thischar = $chardata[$i];
					if($i > 0){
						$prevchar = $chardata[($i-1)];
					} else{
						$prevchar = false;
					}
					if(($i+1) < $numchars){
						$nextchar = $chardata[($i+1)];
					} else{
						$nextchar = false;
					}
					if($unicode[$thischar['char']] == 'AL'){
						$x = $thischar['x'];
						if($x > 0){
							$prevchar = $charAL[($x-1)];
						} else{
							$prevchar = false;
						}
						if(($x+1) < $numAL){
							$nextchar = $charAL[($x+1)];
						} else{
							$nextchar = false;
						}
						if(($prevchar !== false) AND($prevchar['char'] == 1604) AND(in_array($thischar['char'], $alfletter))){
							$arabicarr = $laa_array;
							$laaletter = true;
							if($x > 1){
								$prevchar = $charAL[($x-2)];
							} else{
								$prevchar = false;
							}
						} else{
							$arabicarr = $unicode_arlet;
							$laaletter = false;
						}
						if(($prevchar !== false) AND($nextchar !== false) AND
							(($unicode[$prevchar['char']] == 'AL') OR($unicode[$prevchar['char']] == 'NSM')) AND
							(($unicode[$nextchar['char']] == 'AL') OR($unicode[$nextchar['char']] == 'NSM')) AND
							($prevchar['type'] == $thischar['type']) AND
							($nextchar['type'] == $thischar['type']) AND
							($nextchar['char'] != 1567)){
							if(in_array($prevchar['char'], $endedletter)){
								if(isset($arabicarr[$thischar['char']][2])){
									$chardata2[$i]['char'] = $arabicarr[$thischar['char']][2];
								}
							} else{
								if(isset($arabicarr[$thischar['char']][3])){
									$chardata2[$i]['char'] = $arabicarr[$thischar['char']][3];
								}
							}
						} elseif(($nextchar !== false) AND
							(($unicode[$nextchar['char']] == 'AL') OR($unicode[$nextchar['char']] == 'NSM')) AND
							($nextchar['type'] == $thischar['type']) AND
							($nextchar['char'] != 1567)){
							if(isset($arabicarr[$chardata[$i]['char']][2])){
								$chardata2[$i]['char'] = $arabicarr[$thischar['char']][2];
							}
						} elseif((($prevchar !== false) AND
							(($unicode[$prevchar['char']] == 'AL') OR($unicode[$prevchar['char']] == 'NSM')) AND
							($prevchar['type'] == $thischar['type'])) OR
							(($nextchar !== false) AND($nextchar['char'] == 1567))){
							if(($i > 1) AND($thischar['char'] == 1607) AND
								($chardata[$i-1]['char'] == 1604) AND
								($chardata[$i-2]['char'] == 1604)){
								$chardata2[$i-2]['char'] = false;
								$chardata2[$i-1]['char'] = false;
								$chardata2[$i]['char'] = 65010;
							} else{
								if(($prevchar !== false) AND in_array($prevchar['char'], $endedletter)){
									if(isset($arabicarr[$thischar['char']][0])){
										$chardata2[$i]['char'] = $arabicarr[$thischar['char']][0];
									}
								} else{
									if(isset($arabicarr[$thischar['char']][1])){
										$chardata2[$i]['char'] = $arabicarr[$thischar['char']][1];
									}
								}
							}
						} elseif(isset($arabicarr[$thischar['char']][0])){
							$chardata2[$i]['char'] = $arabicarr[$thischar['char']][0];
						}
						if($laaletter){
							$chardata2[($charAL[($x-1)]['i'])]['char'] = false;
						}
					}
				}
				$cw = &$this->CurrentFont['cw'];
				for($i=0; $i <($numchars-1); $i++){
					if(($chardata2[$i]['char'] == 1617) AND(isset($diacritics[($chardata2[$i+1]['char'])]))){
						if(isset($cw[($diacritics[($chardata2[$i+1]['char'])])])){
							$chardata2[$i]['char'] = false;
							$chardata2[$i+1]['char'] = $diacritics[($chardata2[$i+1]['char'])];
						}
					}
				}
				foreach($chardata2 as $key => $value){
					if($value['char'] === false){
						unset($chardata2[$key]);
					}
				}
				$chardata = array_values($chardata2);
				$numchars = count($chardata);
				unset($chardata2);
				unset($arabicarr);
				unset($laaletter);
				unset($charAL);
			}
			for($j=$maxlevel; $j > 0; $j--){
				$ordarray = Array();
				$revarr = Array();
				$onlevel = false;
				for($i=0; $i < $numchars; $i++){
					if($chardata[$i]['level'] >= $j){
						$onlevel = true;
						if(isset($unicode_mirror[$chardata[$i]['char']])){
							$chardata[$i]['char'] = $unicode_mirror[$chardata[$i]['char']];
						}
						$revarr[] = $chardata[$i];
					} else{
						if($onlevel){
							$revarr = array_reverse($revarr);
							$ordarray = array_merge($ordarray, $revarr);
							$revarr = Array();
							$onlevel = false;
						}
						$ordarray[] = $chardata[$i];
					}
				}
				if($onlevel){
					$revarr = array_reverse($revarr);
					$ordarray = array_merge($ordarray, $revarr);
				}
				$chardata = $ordarray;
			}
			$ordarray = array();
			for($i=0; $i < $numchars; $i++){
				$ordarray[] = $chardata[$i]['char'];
			}
			return $ordarray;
		}
		public function Bookmark($txt, $level=0, $y=-1){
			if($level < 0){
				$level = 0;
			}
			if(isset($this->outlines[0])){
				$lastoutline = end($this->outlines);
				$maxlevel = $lastoutline['l'] + 1;
			} else{
				$maxlevel = 0;
			}
			if($level > $maxlevel){
				$level = $maxlevel;
			}
			if($y == -1){
				$y = $this->GetY();
			}
			$this->outlines[] = array('t' => $txt, 'l' => $level, 'y' => $y, 'p' => $this->PageNo());
		}
		protected function _putbookmarks(){
			$nb = count($this->outlines);
			if($nb == 0){
				return;
			}
			$lru = array();
			$level = 0;
			foreach($this->outlines as $i => $o){
				if($o['l'] > 0){
					$parent = $lru[($o['l'] - 1)];
					$this->outlines[$i]['parent'] = $parent;
					$this->outlines[$parent]['last'] = $i;
					if($o['l'] > $level){
						$this->outlines[$parent]['first'] = $i;
					}
				} else{
					$this->outlines[$i]['parent'] = $nb;
				}
				if(($o['l'] <= $level) AND($i > 0)){
					$prev = $lru[$o['l']];
					$this->outlines[$prev]['next'] = $i;
					$this->outlines[$i]['prev'] = $prev;
				}
				$lru[$o['l']] = $i;
				$level = $o['l'];
			}
			$n = $this->n + 1;
			foreach($this->outlines as $i => $o){
				$this->_newobj();
				$this->_out('<</Title '.$this->_textstring($o['t']));
				$this->_out('/Parent '.($n + $o['parent']).' 0 R');
				if(isset($o['prev']))
				$this->_out('/Prev '.($n + $o['prev']).' 0 R');
				if(isset($o['next']))
				$this->_out('/Next '.($n + $o['next']).' 0 R');
				if(isset($o['first']))
				$this->_out('/First '.($n + $o['first']).' 0 R');
				if(isset($o['last']))
				$this->_out('/Last '.($n + $o['last']).' 0 R');
				$this->_out(sprintf('/Dest [%d 0 R /XYZ 0 %.2f null]',(1 +(2 * $o['p'])),($this->pagedim[$o['p']]['h'] -($o['y'] * $this->k))));
				$this->_out('/Count 0>>');
				$this->_out('endobj');
			}
			$this->_newobj();
			$this->OutlineRoot=$this->n;
			$this->_out('<</Type /Outlines /First '.$n.' 0 R');
			$this->_out('/Last '.($n + $lru[0]).' 0 R>>');
			$this->_out('endobj');
		}
		public function IncludeJS($script){
			$this->javascript .= $script;
		}
		protected function _putjavascript(){
			if(empty($this->javascript)){
				return;
			}
			$js1 = sprintf("ftcpdfdocsaved=this.addField('%s','%s',%d,[%.2f,%.2f,%.2f,%.2f]);", 'tcpdfdocsaved', 'text', 0, 0, 1, 0, 1);
			$js2 = "getField('tcpdfdocsaved').value = 'saved';";
			$this->_newobj();
			$this->n_js = $this->n;
			$this->_out('<<');
			$this->_out('/Names [(EmbeddedJS) '.($this->n + 1).' 0 R ]');
			$this->_out('>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->_out('<<');
			$this->_out('/S /JavaScript');
			$this->_out('/JS '.$this->_textstring($js1."\n".$this->javascript."\n".$js2));
			$this->_out('>>');
			$this->_out('endobj');
		}
		protected function _JScolor($color){
			static $aColors = array('transparent', 'black', 'white', 'red', 'green', 'blue', 'cyan', 'magenta', 'yellow', 'dkGray', 'gray', 'ltGray');
			if(substr($color,0,1) == '#'){
				return sprintf("['RGB',%.3f,%.3f,%.3f]", hexdec(substr($color,1,2))/255, hexdec(substr($color,3,2))/255, hexdec(substr($color,5,2))/255);
			}
			if(!in_array($color,$aColors)){
				$this->Error('Invalid color: '.$color);
			}
			return 'color.'.$color;
		}
		protected function _addfield($type, $name, $x, $y, $w, $h, $prop){
			if($this->rtl){
				$x = $x - $w;
			}
			$this->javascript .= "if(getField('tcpdfdocsaved').value != 'saved') {";
			$k = $this->k;
			$this->javascript .= sprintf("f".$name."=this.addField('%s','%s',%d,[%.2f,%.2f,%.2f,%.2f]);", $name, $type, $this->PageNo()-1, $x*$k,($this->h-$y)*$k+1,($x+$w)*$k,($this->h-$y-$h)*$k+1)."\n";
			$this->javascript .= 'f'.$name.'.textSize='.$this->FontSizePt.";\n";
			while(list($key, $val) = each($prop)){
				if(strcmp(substr($key, -5), 'Color') == 0){
					$val = $this->_JScolor($val);
				} else{
					$val = "'".$val."'";
				}
				$this->javascript .= 'f'.$name.'.'.$key.'='.$val.";\n";
			}
			if($this->rtl){
				$this->x -= $w;
			} else{
				$this->x += $w;
			}
			$this->javascript .= '}';
		}
		public function TextField($name, $w, $h, $prop=array()){
			$this->_addfield('text', $name, $this->x, $this->y, $w, $h, $prop);
		}
		public function RadioButton($name, $w, $prop=array()){
			if(!isset($prop['strokeColor'])){
				$prop['strokeColor']='black';
			}
			$this->_addfield('radiobutton', $name, $this->x, $this->y, $w, $w, $prop);
		}
		public function ListBox($name, $w, $h, $values, $prop=array()){
			if(!isset($prop['strokeColor'])){
				$prop['strokeColor'] = 'ltGray';
			}
			$this->_addfield('listbox', $name, $this->x, $this->y, $w, $h, $prop);
			$s = '';
			foreach($values as $value){
				$s .= "'".addslashes($value)."',";
			}
			$this->javascript .= 'f'.$name.'.setItems(['.substr($s, 0, -1)."]);\n";
		}
		public function ComboBox($name, $w, $h, $values, $prop=array()){
			$this->_addfield('combobox', $name, $this->x, $this->y, $w, $h, $prop);
			$s = '';
			foreach($values as $value){
				$s .= "'".addslashes($value)."',";
			}
			$this->javascript .= 'f'.$name.'.setItems(['.substr($s, 0, -1)."]);\n";
		}
		public function CheckBox($name, $w, $checked=false, $prop=array()){
			$prop['value'] =($checked ? 'Yes' : 'Off');
			if(!isset($prop['strokeColor'])){
				$prop['strokeColor'] = 'black';
			}
			$this->_addfield('checkbox', $name, $this->x, $this->y, $w, $w, $prop);
		}
		public function Button($name, $w, $h, $caption, $action, $prop=array()){
			if(!isset($prop['strokeColor'])){
				$prop['strokeColor'] = 'black';
			}
			if(!isset($prop['borderStyle'])){
				$prop['borderStyle'] = 'beveled';
			}
			$this->_addfield('button', $name, $this->x, $this->y, $w, $h, $prop);
			$this->javascript .= 'f'.$name.".buttonSetCaption('".addslashes($caption)."');\n";
			$this->javascript .= 'f'.$name.".setAction('MouseUp','".addslashes($action)."');\n";
			$this->javascript .= 'f'.$name.".highlight='push';\n";
			$this->javascript .= 'f'.$name.".print=false;\n";
		}
		protected function _putuserrights(){
			if(!$this->ur){
				return;
			}
			$this->_out('/Perms');
			$this->_out('<<');
			$this->_out('/UR3');
			$this->_out('<<');
			$this->_out('/M '.$this->_datastring('D:'.date('YmdHis')));
			$this->_out('/Name(TCPDF)');
			$this->_out('/Reference[');
			$this->_out('<<');
			$this->_out('/TransformParams');
			$this->_out('<<');
			$this->_out('/Type/TransformParams');
			$this->_out('/V/2.2');
			if(!empty($this->ur_document)){
				$this->_out('/Document['.$this->ur_document.']');
			}
			if(!empty($this->ur_annots)){
				$this->_out('/Annots['.$this->ur_annots.']');
			}
			if(!empty($this->ur_form)){
				$this->_out('/Form['.$this->ur_form.']');
			}
			if(!empty($this->ur_signature)){
				$this->_out('/Signature['.$this->ur_signature.']');
			}
			$this->_out('>>');
			$this->_out('/TransformMethod/UR3');
			$this->_out('/Type/SigRef');
			$this->_out('>>');
			$this->_out(']');
			$this->_out('/Type/Sig');
			$this->_out('>>');
			$this->_out('>>');
		}
		public function setUserRights(
				$enable=true,
				$document='/FullSave',
				$annots='/Create/Delete/Modify/Copy/Import/Export',
				$form='/Add/Delete/FillIn/Import/Export/SubmitStandalone/SpawnTemplate',
				$signature='/Modify'){
			$this->ur = $enable;
			$this->ur_document = $document;
			$this->ur_annots = $annots;
			$this->ur_form = $form;
			$this->ur_signature = $signature;
		}
		public function startPageGroup(){
			$this->newpagegroup = true;
		}
		public function AliasNbPages($alias='{nb}'){
			$this->AliasNbPages = $alias;
		}
		public function getAliasNbPages(){
			if(($this->CurrentFont['type'] == 'TrueTypeUnicode') OR($this->CurrentFont['type'] == 'cidfont0')){
				return '{'.$this->AliasNbPages.'}';
			}
			return $this->AliasNbPages;
		}
		public function getGroupPageNo(){
			return $this->pagegroups[$this->currpagegroup];
		}
		public function getGroupPageNoFormatted(){
			return $this->formatPageNumber($this->getGroupPageNo());
		}
		public function getPageGroupAlias(){
		if(($this->CurrentFont['type'] == 'TrueTypeUnicode') OR($this->CurrentFont['type'] == 'cidfont0')){
				return '{'.$this->currpagegroup.'}';
			}
			return $this->currpagegroup;
		}
		protected function formatPageNumber($num){
			return number_format((float)$num, 0, '', '.');
		}
		public function PageNoFormatted(){
			return $this->formatPageNumber($this->PageNo());
		}
		protected function _putocg(){
			$this->_newobj();
			$this->n_ocg_print = $this->n;
			$this->_out('<</Type /OCG /Name '.$this->_textstring('print'));
			$this->_out('/Usage <</Print <</PrintState /ON>> /View <</ViewState /OFF>>>>>>');
			$this->_out('endobj');
			$this->_newobj();
			$this->n_ocg_view=$this->n;
			$this->_out('<</Type /OCG /Name '.$this->_textstring('view'));
			$this->_out('/Usage <</Print <</PrintState /OFF>> /View <</ViewState /ON>>>>>>');
			$this->_out('endobj');
		}
		public function setVisibility($v){
			if($this->openMarkedContent){
				$this->_out('EMC');
				$this->openMarkedContent = false;
			}
			switch($v){
				case 'print':{
					$this->_out('/OC /OC1 BDC');
					$this->openMarkedContent = true;
					break;
				}
				case 'screen':{
					$this->_out('/OC /OC2 BDC');
					$this->openMarkedContent = true;
					break;
				}
				case 'all':{
					$this->_out('');
					break;
				}
				default:{
					$this->Error('Incorrect visibility: '.$v);
					break;
				}
			}
			$this->visibility = $v;
		}
		protected function addExtGState($parms){
			$n = count($this->extgstates) + 1;
			$this->extgstates[$n]['parms'] = $parms;
			return $n;
		}
		protected function setExtGState($gs){
			$this->_out(sprintf('/GS%d gs', $gs));
		}
		protected function _putextgstates(){
			$ne = count($this->extgstates);
			for($i = 1; $i <= $ne; $i++){
				$this->_newobj();
				$this->extgstates[$i]['n'] = $this->n;
				$this->_out('<</Type /ExtGState');
				foreach($this->extgstates[$i]['parms'] as $k => $v){
					$this->_out('/'.$k.' '.$v);
				}
				$this->_out('>>');
				$this->_out('endobj');
			}
		}
		public function setAlpha($alpha, $bm='Normal'){
			$gs = $this->addExtGState(array('ca' => $alpha, 'CA' => $alpha, 'BM' => '/'.$bm));
			$this->setExtGState($gs);
		}
		public function setJPEGQuality($quality){
			if(($quality < 1) OR($quality > 100)){
				$quality = 75;
			}
			$this->jpeg_quality = intval($quality);
		}
		public function setDefaultTableColumns($cols=4){
			$this->default_table_columns = intval($cols);
		}
		public function setCellHeightRatio($h){
			$this->cell_height_ratio = $h;
		}
		public function getCellHeightRatio(){
			return $this->cell_height_ratio;
		}
		public function setPDFVersion($version='1.7'){
			$this->PDFVersion = $version;
		}
		public function setViewerPreferences($preferences){
			$this->viewer_preferences = $preferences;
		}
		public function LinearGradient($x, $y, $w, $h, $col1=array(), $col2=array(), $coords=array(0,0,1,0)){
			$this->Clip($x, $y, $w, $h);
			$this->Gradient(2, $col1, $col2, $coords);
		}
		public function RadialGradient($x, $y, $w, $h, $col1=array(), $col2=array(), $coords=array(0.5,0.5,0.5,0.5,1)){
			$this->Clip($x, $y, $w, $h);
			$this->Gradient(3, $col1, $col2, $coords);
		}
		public function CoonsPatchMesh($x, $y, $w, $h, $col1=array(), $col2=array(), $col3=array(), $col4=array(), $coords=array(0.00,0.0,0.33,0.00,0.67,0.00,1.00,0.00,1.00,0.33,1.00,0.67,1.00,1.00,0.67,1.00,0.33,1.00,0.00,1.00,0.00,0.67,0.00,0.33), $coords_min=0, $coords_max=1){
			$this->Clip($x, $y, $w, $h);
			$n = count($this->gradients) + 1;
			$this->gradients[$n]['type'] = 6;
			if(!isset($coords[0]['f'])){
				if(!isset($col1[1])){
					$col1[1] = $col1[2] = $col1[0];
				}
				if(!isset($col2[1])){
					$col2[1] = $col2[2] = $col2[0];
				}
				if(!isset($col3[1])){
					$col3[1] = $col3[2] = $col3[0];
				}
				if(!isset($col4[1])){
					$col4[1] = $col4[2] = $col4[0];
				}
				$patch_array[0]['f'] = 0;
				$patch_array[0]['points'] = $coords;
				$patch_array[0]['colors'][0]['r'] = $col1[0];
				$patch_array[0]['colors'][0]['g'] = $col1[1];
				$patch_array[0]['colors'][0]['b'] = $col1[2];
				$patch_array[0]['colors'][1]['r'] = $col2[0];
				$patch_array[0]['colors'][1]['g'] = $col2[1];
				$patch_array[0]['colors'][1]['b'] = $col2[2];
				$patch_array[0]['colors'][2]['r'] = $col3[0];
				$patch_array[0]['colors'][2]['g'] = $col3[1];
				$patch_array[0]['colors'][2]['b'] = $col3[2];
				$patch_array[0]['colors'][3]['r'] = $col4[0];
				$patch_array[0]['colors'][3]['g'] = $col4[1];
				$patch_array[0]['colors'][3]['b'] = $col4[2];
			} else{
				$patch_array = $coords;
			}
			$bpcd = 65535;
			$this->gradients[$n]['stream'] = '';
			for($i=0; $i < count($patch_array); $i++){
				$this->gradients[$n]['stream'] .= chr($patch_array[$i]['f']);
				for($j=0; $j < count($patch_array[$i]['points']); $j++){
					$patch_array[$i]['points'][$j] =(($patch_array[$i]['points'][$j]-$coords_min)/($coords_max-$coords_min))*$bpcd;
					if($patch_array[$i]['points'][$j] < 0){
						$patch_array[$i]['points'][$j] = 0;
					}
					if($patch_array[$i]['points'][$j] > $bpcd){
						$patch_array[$i]['points'][$j] = $bpcd;
					}
					$this->gradients[$n]['stream'] .= chr(floor($patch_array[$i]['points'][$j]/256));
					$this->gradients[$n]['stream'] .= chr(floor($patch_array[$i]['points'][$j]%256));
				}
				for($j=0; $j < count($patch_array[$i]['colors']); $j++){
					$this->gradients[$n]['stream'] .= chr($patch_array[$i]['colors'][$j]['r']);
					$this->gradients[$n]['stream'] .= chr($patch_array[$i]['colors'][$j]['g']);
					$this->gradients[$n]['stream'] .= chr($patch_array[$i]['colors'][$j]['b']);
				}
			}
			$this->_out('/Sh'.$n.' sh');
			$this->_out('Q');
		}
		protected function Clip($x, $y, $w, $h){
			if($this->rtl){
				$x = $this->w - $x - $w;
			}
			$s = 'q';
			$s .= sprintf(' %.2f %.2f %.2f %.2f re W n', $x*$this->k,($this->h-$y)*$this->k, $w*$this->k, -$h*$this->k);
			$s .= sprintf(' %.3f 0 0 %.3f %.3f %.3f cm', $w*$this->k, $h*$this->k, $x*$this->k,($this->h-($y+$h))*$this->k);
			$this->_out($s);
		}
		protected function Gradient($type, $col1, $col2, $coords){
			$n = count($this->gradients) + 1;
			$this->gradients[$n]['type'] = $type;
			if(!isset($col1[1])){
				$col1[1]=$col1[2]=$col1[0];
			}
			$this->gradients[$n]['col1'] = sprintf('%.3f %.3f %.3f',($col1[0]/255),($col1[1]/255),($col1[2]/255));
			if(!isset($col2[1])){
				$col2[1] = $col2[2] = $col2[0];
			}
			$this->gradients[$n]['col2'] = sprintf('%.3f %.3f %.3f',($col2[0]/255),($col2[1]/255),($col2[2]/255));
			$this->gradients[$n]['coords'] = $coords;
			$this->_out('/Sh'.$n.' sh');
			$this->_out('Q');
		}
		function _putshaders(){
			foreach($this->gradients as $id => $grad){
				if(($grad['type'] == 2) OR($grad['type'] == 3)){
					$this->_newobj();
					$this->_out('<<');
					$this->_out('/FunctionType 2');
					$this->_out('/Domain [0.0 1.0]');
					$this->_out('/C0 ['.$grad['col1'].']');
					$this->_out('/C1 ['.$grad['col2'].']');
					$this->_out('/N 1');
					$this->_out('>>');
					$this->_out('endobj');
					$f1 = $this->n;
				}
				$this->_newobj();
				$this->_out('<<');
				$this->_out('/ShadingType '.$grad['type']);
				$this->_out('/ColorSpace /DeviceRGB');
				if($grad['type'] == 2){
					$this->_out(sprintf('/Coords [%.3f %.3f %.3f %.3f]', $grad['coords'][0], $grad['coords'][1], $grad['coords'][2], $grad['coords'][3]));
					$this->_out('/Function '.$f1.' 0 R');
					$this->_out('/Extend [true true] ');
					$this->_out('>>');
				} elseif($grad['type'] == 3){
					$this->_out(sprintf('/Coords [%.3f %.3f 0 %.3f %.3f %.3f]', $grad['coords'][0], $grad['coords'][1], $grad['coords'][2], $grad['coords'][3], $grad['coords'][4]));
					$this->_out('/Function '.$f1.' 0 R');
					$this->_out('/Extend [true true] ');
					$this->_out('>>');
				} elseif($grad['type'] == 6){
					$this->_out('/BitsPerCoordinate 16');
					$this->_out('/BitsPerComponent 8');
					$this->_out('/Decode[0 1 0 1 0 1 0 1 0 1]');
					$this->_out('/BitsPerFlag 8');
					$this->_out('/Length '.strlen($grad['stream']));
					$this->_out('>>');
					$this->_putstream($grad['stream']);
				}
				$this->_out('endobj');
				$this->gradients[$id]['id'] = $this->n;
			}
		}
		protected function _outarc($x1, $y1, $x2, $y2, $x3, $y3){
			$h = $this->h;
			$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c', $x1*$this->k,($h-$y1)*$this->k, $x2*$this->k,($h-$y2)*$this->k, $x3*$this->k,($h-$y3)*$this->k));
		}
		public function PieSector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90){
			if($this->rtl){
				$xc = $this->w - $xc;
			}
			if($cw){
				$d = $b;
				$b = $o - $a;
				$a = $o - $d;
			} else{
				$b += $o;
				$a += $o;
			}
			$a =($a % 360) + 360;
			$b =($b % 360) + 360;
			if($a > $b){
				$b +=360;
			}
			$b = $b / 360 * 2 * M_PI;
			$a = $a / 360 * 2 * M_PI;
			$d = $b - $a;
			if($d == 0){
				$d = 2 * M_PI;
			}
			$k = $this->k;
			$hp = $this->h;
			if($style=='F'){
				$op = 'f';
			} elseif($style=='FD' or $style=='DF'){
				$op = 'b';
			} else{
				$op = 's';
			}
			if(sin($d/2)){
				$MyArc = 4/3 *(1 - cos($d/2)) / sin($d/2) * $r;
			}
			$this->_out(sprintf('%.2f %.2f m',($xc)*$k,($hp-$yc)*$k));
			$this->_out(sprintf('%.2f %.2f l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
			if($d <(M_PI/2)){
				$this->_outarc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
			} else{
				$b = $a + $d/4;
				$MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
				$this->_outarc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
				$a = $b;
				$b = $a + $d/4;
				$this->_outarc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
				$a = $b;
				$b = $a + $d/4;
				$this->_outarc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
				$a = $b;
				$b = $a + $d/4;
				$this->_outarc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a), $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a), $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2), $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2), $xc+$r*cos($b), $yc-$r*sin($b));
			}
			$this->_out($op);
		}
		public function ImageEps($file, $x='', $y='', $w=0, $h=0, $link='', $useBoundingBox=true, $align='', $palign=''){
			if($x === ''){
				$x = $this->x;
			}
			if($y === ''){
				$y = $this->y;
			}
			$data = file_get_contents($file);
			if($data === false){
				$this->Error('EPS file not found: '.$file);
			}
			$regs = array();
			preg_match("/%%Creator:([^\r\n]+)/", $data, $regs);
			if(count($regs) > 1){
				$version_str = trim($regs[1]);
				if(strpos($version_str, 'Adobe Illustrator') !== false){
					$versexp = explode(' ', $version_str);
					$version = (float)array_pop($versexp);
					if($version >= 9){
						$this->Error('This version of Adobe Illustrator file is not supported: '.$file);
					}
				}
			}
			$start = strpos($data, '%!PS-Adobe');
			if($start > 0){
				$data = substr($data, $start);
			}
			preg_match("/%%BoundingBox:([^\r\n]+)/", $data, $regs);
			if(count($regs) > 1){
				list($x1, $y1, $x2, $y2) = explode(' ', trim($regs[1]));
			} else{
				$this->Error('No BoundingBox found in EPS file: '.$file);
			}
			$start = strpos($data, '%%EndSetup');
			if($start === false){
				$start = strpos($data, '%%EndProlog');
			}
			if($start === false){
				$start = strpos($data, '%%BoundingBox');
			}
			$data = substr($data, $start);
			$end = strpos($data, '%%PageTrailer');
			if($end===false){
				$end = strpos($data, 'showpage');
			}
			if($end){
				$data = substr($data, 0, $end);
			}
			$k = $this->k;
			if($w > 0){
				$scale_x = $w/(($x2-$x1)/$k);
				if($h > 0){
					$scale_y = $h/(($y2-$y1)/$k);
				} else{
					$scale_y = $scale_x;
					$h =($y2-$y1)/$k * $scale_y;
				}
			} else{
				if($h > 0){
					$scale_y = $h/(($y2-$y1)/$k);
					$scale_x = $scale_y;
					$w =($x2-$x1)/$k * $scale_x;
				} else{
					$w =($x2 - $x1) / $k;
					$h =($y2 - $y1) / $k;
				}
			}
			if((($y + $h) > $this->PageBreakTrigger) AND(!$this->InFooter) AND $this->AcceptPageBreak()){
				$this->AddPage($this->CurOrientation);
				$y = $this->GetY() + $this->cMargin;
			}
			$this->img_rb_y = $y + $h;
			if($this->rtl){
				if($palign == 'L'){
					$ximg = $this->lMargin;
					$this->img_rb_x = $ximg + $w;
				} elseif($palign == 'C'){
					$ximg =($this->w - $x - $w) / 2;
					$this->img_rb_x = $ximg + $w;
				} else{
					$ximg = $this->w - $x - $w;
					$this->img_rb_x = $ximg;
				}
			} else{
				if($palign == 'R'){
					$ximg = $this->w - $this->rMargin - $w;
					$this->img_rb_x = $ximg;
				} elseif($palign == 'C'){
					$ximg =($this->w - $x - $w) / 2;
					$this->img_rb_x = $ximg + $w;
				} else{
					$ximg = $x;
					$this->img_rb_x = $ximg + $w;
				}
			}
			if($useBoundingBox){
				$dx = $ximg * $k - $x1;
				$dy = $y * $k - $y1;
			} else{
				$dx = $ximg * $k;
				$dy = $y * $k;
			}
			$this->_out('q'.$this->epsmarker);
			$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', 1, 0, 0, 1, $dx, $dy+($this->hPt - 2*$y*$k -($y2-$y1))));
			if(isset($scale_x)){
				$this->_out(sprintf('%.3F %.3F %.3F %.3F %.3F %.3F cm', $scale_x, 0, 0, $scale_y, $x1*(1-$scale_x), $y2*(1-$scale_y)));
			}
			preg_match('/[\r\n]+/s', $data, $regs);
			$lines = explode($regs[0], $data);
			$u=0;
			$cnt = count($lines);
			for($i=0; $i < $cnt; $i++){
				$line = $lines[$i];
				if(($line == '') OR($line{0} == '%')){
					continue;
				}
				$len = strlen($line);
				$chunks = explode(' ', $line);
				$cmd = array_pop($chunks);
				if(($cmd == 'Xa') OR($cmd == 'XA')){
					$b = array_pop($chunks);
					$g = array_pop($chunks);
					$r = array_pop($chunks);
					$this->_out(''.$r.' '.$g.' '.$b.' '.($cmd=='Xa'?'rg':'RG'));
					continue;
				}
				switch($cmd){
					case 'm':
					case 'l':
					case 'v':
					case 'y':
					case 'c':
					case 'k':
					case 'K':
					case 'g':
					case 'G':
					case 's':
					case 'S':
					case 'J':
					case 'j':
					case 'w':
					case 'M':
					case 'd':
					case 'n':
					case 'v':{
						$this->_out($line);
						break;
					}
					case 'x':{
						list($c,$m,$y,$k) = $chunks;
						$this->_out(''.$c.' '.$m.' '.$y.' '.$k.' k');
						break;
					}
					case 'X':{
						list($c,$m,$y,$k) = $chunks;
						$this->_out(''.$c.' '.$m.' '.$y.' '.$k.' K');
						break;
					}
					case 'Y':
					case 'N':
					case 'V':
					case 'L':
					case 'C':{
						$line{$len-1} = strtolower($cmd);
						$this->_out($line);
						break;
					}
					case 'b':
					case 'B':{
						$this->_out($cmd . '*');
						break;
					}
					case 'f':
					case 'F':{
						if($u > 0){
							$isU = false;
							$max = min($i+5, $cnt);
							for($j=$i+1; $j < $max; $j++)
							  $isU =($isU OR(($lines[$j] == 'U') OR($lines[$j] == '*U')));
							if($isU){
								$this->_out('f*');
							}
						} else{
							$this->_out('f*');
						}
						break;
					}
					case '*u':{
						$u++;
						break;
					}
					case '*U':{
						$u--;
						break;
					}
				}
			}
			$this->_out($this->epsmarker.'Q');
			if($link){
				$this->Link($ximg, $y, $w, $h, $link, 0);
			}
			switch($align){
				case 'T':{
					$this->y = $y;
					$this->x = $this->img_rb_x;
					break;
				}
				case 'M':{
					$this->y = $y + round($h/2);
					$this->x = $this->img_rb_x;
					break;
				}
				case 'B':{
					$this->y = $this->img_rb_y;
					$this->x = $this->img_rb_x;
					break;
				}
				case 'N':{
					$this->SetY($this->img_rb_y);
					break;
				}
				default:{
					break;
				}
			}
			$this->endlinex = $this->img_rb_x;
		}
		public function setBarcode($bc=''){
			$this->barcode = $bc;
		}
		public function getBarcode(){
			return $this->barcode;
		}
		public function write1DBarcode($code, $type, $x='', $y='', $w='', $h='', $xres=0.4, $style='', $align=''){
			if(empty($code)){
				return;
			}
			$gvars = $this->getGraphicVars();
			$barcodeobj = new TCPDFbarcode($code, $type);
			$arrcode = $barcodeobj->getBarcodeArray();
			if($arrcode === false){
				$this->Error('Error in barcode string');
			}
			if(!isset($style['position'])){
				if($this->rtl){
					$style['position'] = 'R';
				} else{
					$style['position'] = 'L';
				}
			}
			if(!isset($style['padding'])){
				$style['padding'] = 0;
			}
			if(!isset($style['fgcolor'])){
				$style['fgcolor'] = array(0,0,0);
			}
			if(!isset($style['bgcolor'])){
				$style['bgcolor'] = false;
			}
			if(!isset($style['border'])){
				$style['border'] = false;
			}
			if(!isset($style['text'])){
				$style['text'] = false;
				$fontsize = 0;
			}
			if($style['text'] AND isset($style['font'])){
				if(isset($style['fontsize'])){
					$fontsize = $style['fontsize'];
				} else{
					$fontsize = 0;
				}
				$this->SetFont($style['font'], '', $fontsize);
			}
			if(!isset($style['stretchtext'])){
				$style['stretchtext'] = 4;
			}
			$this->SetDrawColorArray($style['fgcolor']);
			$this->SetTextColorArray($style['fgcolor']);
			if(empty($w) OR($w <= 0)){
				if($this->rtl){
					$w = $this->x - $this->lMargin;
				} else{
					$w = $this->w - $this->rMargin - $this->x;
				}
			}
			if(empty($x)){
				$x = $this->GetX();
			}
			if($this->rtl){
				$x = $this->w - $x;
			}
			if(empty($y)){
				$y = $this->GetY();
			}
			if(empty($xres)){
				$xres = 0.4;
			}
			$fbw =($arrcode['maxw'] * $xres) +(2 * $style['padding']);
			$extraspace =($this->cell_height_ratio * $fontsize / $this->k) +(2 * $style['padding']);
			if(empty($h)){
				$h = 10 + $extraspace;
			}
			if((($y + $h) > $this->PageBreakTrigger) AND(!$this->InFooter) AND($this->AcceptPageBreak())){
				$x = $this->x;
				$ws = $this->ws;
				if($ws > 0){
					$this->ws = 0;
					$this->_out('0 Tw');
				}
				$this->AddPage($this->CurOrientation);
				if($ws > 0){
					$this->ws = $ws;
					$this->_out(sprintf('%.3f Tw',$ws * $k));
				}
				$this->x = $x;
				$y = $this->y;
			}
			$barh = $h - $extraspace;
			switch($style['position']){
				case 'L':{
					if($this->rtl){
						$xpos = $x - $w;
					} else{
						$xpos = $x;
					}
					break;
				}
				case 'C':{
					$xdiff =(($w - $fbw) / 2);
					if($this->rtl){
						$xpos = $x - $w + $xdiff;
					} else{
						$xpos = $x + $xdiff;
					}
					break;
				}
				case 'R':{
					if($this->rtl){
						$xpos = $x - $fbw;
					} else{
						$xpos = $x + $w - $fbw;
					}
					break;
				}
				case 'S':{
					$fbw = $w;
					$xres =($w -(2 * $style['padding'])) / $arrcode['maxw'];
					if($this->rtl){
						$xpos = $x - $w;
					} else{
						$xpos = $x;
					}
					break;
				}
			}
			$xpos_rect = $xpos;
			$xpos = $xpos_rect + $style['padding'];
			$xpos_text = $xpos;
			$tempRTL = $this->rtl;
			$this->rtl = false;
			if($style['bgcolor']){
				$this->Rect($xpos_rect, $y, $fbw, $h, 'DF', '', $style['bgcolor']);
			} elseif($style['border']){
				$this->Rect($xpos_rect, $y, $fbw, $h, 'D');
			}
			if($arrcode !== false){
				foreach($arrcode['bcode'] as $k => $v){
					$bw =($v['w'] * $xres);
					if($v['t']){
						$ypos = $y + $style['padding'] +($v['p'] * $barh / $arrcode['maxh']);
						$this->Rect($xpos, $ypos, $bw,($v['h'] * $barh  / $arrcode['maxh']), 'DF', array('L'=>0, 'T'=>0, 'R'=>0, 'B'=>0), $style['fgcolor']);
					}
					$xpos += $bw;
				}
			}
			if($style['text']){
				$this->x = $xpos_text;
				$this->y = $y + $style['padding'] + $barh;
				$this->Cell(($arrcode['maxw'] * $xres),($this->cell_height_ratio * $fontsize / $this->k), $code, 0, 0, 'C', 0, '', $style['stretchtext']);
			}
			$this->rtl = $tempRTL;
			$this->setGraphicVars($gvars);
			$this->img_rb_y = $y + $h;
			if($this->rtl){
				$this->img_rb_x =($this->w - $x - $w);
			} else{
				$this->img_rb_x = $x + $w;
			}
			switch($align){
				case 'T':{
					$this->y = $y;
					$this->x = $this->img_rb_x;
					break;
				}
				case 'M':{
					$this->y = $y + round($h/2);
					$this->x = $this->img_rb_x;
					break;
				}
				case 'B':{
					$this->y = $this->img_rb_y;
					$this->x = $this->img_rb_x;
					break;
				}
				case 'N':{
					$this->SetY($this->img_rb_y);
					break;
				}
				default:{
					break;
				}
			}
		}
		public function writeBarcode($x, $y, $w, $h, $type, $style, $font, $xres, $code){
			$xres = 1 / $xres;
			$newstyle = array(
				'position' => 'L',
				'border' => false,
				'padding' => 0,
				'fgcolor' => array(0,0,0),
				'bgcolor' => false,
				'text' => true,
				'font' => $font,
				'fontsize' => 8,
				'stretchtext' => 4
			);
			if($style & 1){
				$newstyle['border'] = true;
			}
			if($style & 2){
				$newstyle['bgcolor'] = false;
			}
			if($style & 4){
				$newstyle['position'] = 'C';
			} elseif($style & 8){
				$newstyle['position'] = 'L';
			} elseif($style & 16){
				$newstyle['position'] = 'R';
			}
			if($style & 128){
				$newstyle['text'] = true;
			}
			if($style & 256){
				$newstyle['stretchtext'] = 4;
			}
			$this->write1DBarcode($code, $type, $x, $y, $w, $h, $xres, $newstyle, '');
		}
		public function getMargins(){
			$ret = array(
				'left' => $this->lMargin,
				'right' => $this->rMargin,
				'top' => $this->tMargin,
				'bottom' => $this->bMargin,
				'header' => $this->header_margin,
				'footer' => $this->footer_margin,
				'cell' => $this->cMargin,
			);
			return $ret;
		}
		public function getOriginalMargins(){
			$ret = array(
				'left' => $this->original_lMargin,
				'right' => $this->original_rMargin
			);
			return $ret;
		}
		public function getFontSize(){
			return $this->FontSize;
		}
		public function getFontSizePt(){
			return $this->FontSizePt;
		}
		public function writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align=''){
			return $this->MultiCell($w, $h, $html, $border, $align, $fill, $ln, $x, $y, $reseth, 0, true);
		}
		protected function getHtmlDomArray($html){
			$html = strip_tags($html, '<marker/><a><b><blockquote><br><br/><dd><del><div><dl><dt><em><font><h1><h2><h3><h4><h5><h6><hr><i><img><li><ol><p><small><span><strong><sub><sup><table><td><th><tr><u><ul>');
			$repTable = array("\t" => ' ', "\n" => ' ', "\r" => ' ', "\0" => ' ', "\x0B" => ' ', "\\" => "\\\\");
			$html = strtr($html, $repTable);
			$html = preg_replace('/[\s]*<\/table>[\s]*/', '</table>', $html);
			$html = preg_replace('/[\s]*<\/tr>[\s]*/', '</tr>', $html);
			$html = preg_replace('/[\s]*<tr/', '<tr', $html);
			$html = preg_replace('/[\s]*<\/th>[\s]*/', '</th>', $html);
			$html = preg_replace('/[\s]*<th/', '<th', $html);
			$html = preg_replace('/[\s]*<\/td>[\s]*/', '</td>', $html);
			$html = preg_replace('/[\s]*<td/', '<td', $html);
			$html = preg_replace('/<\/th>/', '<marker/></th>', $html);
			$html = preg_replace('/<\/td>/', '<marker/></td>', $html);
			$html = preg_replace('/<\/table><marker\/>/', '</table>', $html);
			$html = preg_replace('/<img/', ' <img', $html);
			$html = preg_replace('/<img([^\>]*)>/xi', '<img\\1><span></span>', $html);
			$html = preg_replace('/[\s]*<li/', '<li', $html);
			$html = preg_replace('/<\/li>[\s]*/', '</li>', $html);
			$tagpattern = '/(<[^>]+>)/';
			$a = preg_split($tagpattern, $html, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			$maxel = count($a);
			$key = 0;
			$dom = array();
			$dom[$key] = array();
			$dom[$key]['tag'] = false;
			$dom[$key]['value'] = '';
			$dom[$key]['parent'] = 0;
			$dom[$key]['fontname'] = $this->FontFamily;
			$dom[$key]['fontstyle'] = $this->FontStyle;
			$dom[$key]['fontsize'] = $this->FontSizePt;
			$dom[$key]['bgcolor'] = false;
			$dom[$key]['fgcolor'] = $this->fgcolor;
			$dom[$key]['align'] = '';
			$key++;
			$level = array();
			array_push($level, 0);
			while($key <= $maxel){
				if($key > 0){
					$dom[$key] = array();
				}
				$element = $a[($key-1)];
				if(preg_match($tagpattern, $element)){
					$dom[$key]['tag'] = true;
					$element = substr($element, 1, -1);
					preg_match('/[\/]?([a-zA-Z0-9]*)/', $element, $tag);
					$dom[$key]['value'] = strtolower($tag[1]);
					if($element{0} == '/'){
						$dom[$key]['opening'] = false;
						$dom[$key]['parent'] = end($level);
						array_pop($level);
						$dom[$key]['fontname'] = $dom[($dom[($dom[$key]['parent'])]['parent'])]['fontname'];
						$dom[$key]['fontstyle'] = $dom[($dom[($dom[$key]['parent'])]['parent'])]['fontstyle'];
						$dom[$key]['fontsize'] = $dom[($dom[($dom[$key]['parent'])]['parent'])]['fontsize'];
						$dom[$key]['bgcolor'] = $dom[($dom[($dom[$key]['parent'])]['parent'])]['bgcolor'];
						$dom[$key]['fgcolor'] = $dom[($dom[($dom[$key]['parent'])]['parent'])]['fgcolor'];
						$dom[$key]['align'] = $dom[($dom[($dom[$key]['parent'])]['parent'])]['align'];
						if(($dom[$key]['value'] == 'tr') AND(!isset($dom[($dom[($dom[$key]['parent'])]['parent'])]['cols']))){
							$dom[($dom[($dom[$key]['parent'])]['parent'])]['cols'] = $dom[($dom[$key]['parent'])]['cols'];
						}
						if(($dom[$key]['value'] == 'td') OR($dom[$key]['value'] == 'th')){
							$dom[($dom[$key]['parent'])]['content'] = '';
							for($i =($dom[$key]['parent'] + 1); $i < $key; $i++){
								$dom[($dom[$key]['parent'])]['content'] .= $a[($i-1)];
							}
							$key = $i;
						}
					} else{
						$dom[$key]['opening'] = true;
						$dom[$key]['parent'] = end($level);
						if(substr($element, -1, 1) != '/'){
							array_push($level, $key);
							$dom[$key]['self'] = false;
						} else{
							$dom[$key]['self'] = true;
						}
						if($key > 0){
							$dom[$key]['fontname'] = $dom[($dom[$key]['parent'])]['fontname'];
							$dom[$key]['fontstyle'] = $dom[($dom[$key]['parent'])]['fontstyle'];
							$dom[$key]['fontsize'] = $dom[($dom[$key]['parent'])]['fontsize'];
							$dom[$key]['bgcolor'] = $dom[($dom[$key]['parent'])]['bgcolor'];
							$dom[$key]['fgcolor'] = $dom[($dom[$key]['parent'])]['fgcolor'];
							$dom[$key]['align'] = $dom[($dom[$key]['parent'])]['align'];
						}
						preg_match_all('/([^=\s]*)=["\']?([^"\']*)["\']?/', $element, $attr_array, PREG_PATTERN_ORDER);
						$dom[$key]['attribute'] = array();
						while(list($id, $name) = each($attr_array[1])){
							$dom[$key]['attribute'][strtolower($name)] = $attr_array[2][$id];
						}
						if(isset($dom[$key]['attribute']['style'])){
							preg_match_all('/([^:\s]*):([^;]*)/', $dom[$key]['attribute']['style'], $style_array, PREG_PATTERN_ORDER);
							$dom[$key]['style'] = array();
							while(list($id, $name) = each($style_array[1])){
								$dom[$key]['style'][strtolower($name)] = trim($style_array[2][$id]);
							}
							if(isset($dom[$key]['style']['font-family'])){
								if(isset($dom[$key]['style']['font-family'])){
									$fontslist = split(',', strtolower($dom[$key]['style']['font-family']));
									foreach($fontslist as $font){
										$font = trim(strtolower($font));
										if(in_array($font, $this->fontlist)){
											$dom[$key]['fontname'] = $font;
											break;
										}
									}
								}
							}
							if(isset($dom[$key]['style']['font-size'])){
								$fsize = trim($dom[$key]['style']['font-size']);
								switch($fsize){
									case 'xx-small':{
										$dom[$key]['fontsize'] = $dom[0]['fontsize'] - 4;
										break;
									}
									case 'x-small':{
										$dom[$key]['fontsize'] = $dom[0]['fontsize'] - 3;
										break;
									}
									case 'small':{
										$dom[$key]['fontsize'] = $dom[0]['fontsize'] - 2;
										break;
									}
									case 'medium':{
										$dom[$key]['fontsize'] = $dom[0]['fontsize'];
										break;
									}
									case 'large':{
										$dom[$key]['fontsize'] = $dom[0]['fontsize'] + 2;
										break;
									}
									case 'x-large':{
										$dom[$key]['fontsize'] = $dom[0]['fontsize'] + 4;
										break;
									}
									case 'xx-large':{
										$dom[$key]['fontsize'] = $dom[0]['fontsize'] + 6;
										break;
									}
									default:{
										$dom[$key]['fontsize'] = intval($fsize);
									}
								}
							}
							if(isset($dom[$key]['style']['font-weight']) AND(strtolower($dom[$key]['style']['font-weight']{0}) == 'b')){
								$dom[$key]['fontstyle'] .= 'B';
							}
							if(isset($dom[$key]['style']['font-style']) AND(strtolower($dom[$key]['style']['font-style']{0}) == 'i')){
								$dom[$key]['fontstyle'] .= '"I';
							}
							if(isset($dom[$key]['style']['color']) AND(!empty($dom[$key]['style']['color']))){
								$dom[$key]['fgcolor'] = $this->convertHTMLColorToDec($dom[$key]['style']['color']);
							}
							if(isset($dom[$key]['style']['background-color']) AND(!empty($dom[$key]['style']['background-color']))){
								$dom[$key]['bgcolor'] = $this->convertHTMLColorToDec($dom[$key]['style']['background-color']);
							}
							if(isset($dom[$key]['style']['text-decoration'])){
								$decors = explode(' ', strtolower($dom[$key]['style']['text-decoration']));
								foreach($decors as $dec){
									$dec = trim($dec);
									if($dec{0} == 'u'){
										$dom[$key]['fontstyle'] .= 'U';
									} elseif($dec{0} == 'l'){
										$dom[$key]['fontstyle'] .= 'D';
									}
								}
							}
							if(isset($dom[$key]['style']['width'])){
								$dom[$key]['width'] = intval($dom[$key]['style']['width']);
							}
							if(isset($dom[$key]['style']['height'])){
								$dom[$key]['height'] = intval($dom[$key]['style']['height']);
							}
							if(isset($dom[$key]['style']['text-align'])){
								$dom[$key]['align'] = strtoupper($dom[$key]['style']['text-align']{0});
							}
						}
						if($dom[$key]['value'] == 'font'){
							if(isset($dom[$key]['attribute']['face'])){
								$fontslist = split(',', strtolower($dom[$key]['attribute']['face']));
								foreach($fontslist as $font){
									$font = trim(strtolower($font));
									if(in_array($font, $this->fontlist)){
										$dom[$key]['fontname'] = $font;
										break;
									}
								}
							}
							if(isset($dom[$key]['attribute']['size'])){
								if($key > 0){
									if($dom[$key]['attribute']['size']{0} == '+'){
										$dom[$key]['fontsize'] = $dom[($dom[$key]['parent'])]['fontsize'] + intval(substr($dom[$key]['attribute']['size'], 1));
									} elseif($dom[$key]['attribute']['size']{0} == '-'){
										$dom[$key]['fontsize'] = $dom[($dom[$key]['parent'])]['fontsize'] - intval(substr($dom[$key]['attribute']['size'], 1));
									} else{
										$dom[$key]['fontsize'] = intval($dom[$key]['attribute']['size']);
									}
								} else{
									$dom[$key]['fontsize'] = intval($dom[$key]['attribute']['size']);
								}
							}
						}
						if(($dom[$key]['value'] == 'ul') OR($dom[$key]['value'] == 'ol') OR($dom[$key]['value'] == 'dl')){
							if($this->rtl){
								$dom[$key]['align'] = 'R';
							} else{
								$dom[$key]['align'] = 'L';
							}
						}
						if(($dom[$key]['value'] == 'small') OR($dom[$key]['value'] == 'sup') OR($dom[$key]['value'] == 'sub')){
							$dom[$key]['fontsize'] = $dom[$key]['fontsize'] * K_SMALL_RATIO;
						}
						if(($dom[$key]['value'] == 'strong') OR($dom[$key]['value'] == 'b')){
							$dom[$key]['fontstyle'] .= 'B';
						}
						if(($dom[$key]['value'] == 'em') OR($dom[$key]['value'] == 'i')){
							$dom[$key]['fontstyle'] .= 'I';
						}
						if($dom[$key]['value'] == 'u'){
							$dom[$key]['fontstyle'] .= 'U';
						}
						if($dom[$key]['value'] == 'del'){
							$dom[$key]['fontstyle'] .= 'D';
						}
						if(($dom[$key]['value']{0} == 'h') AND(intval($dom[$key]['value']{1}) > 0) AND(intval($dom[$key]['value']{1}) < 7)){
							$headsize =(4 - intval($dom[$key]['value']{1})) * 2;
							$dom[$key]['fontsize'] = $dom[0]['fontsize'] + $headsize;
							$dom[$key]['fontstyle'] .= 'B';
						}
						if(($dom[$key]['value'] == 'table')){
							$dom[$key]['rows'] = 0;
							$dom[$key]['trids'] = array();
						}
						if(($dom[$key]['value'] == 'tr')){
							$dom[$key]['cols'] = 0;
							$dom[($dom[$key]['parent'])]['rows']++;
							array_push($dom[($dom[$key]['parent'])]['trids'], $key);
						}
						if(($dom[$key]['value'] == 'th') OR($dom[$key]['value'] == 'td')){
							if(isset($dom[$key]['attribute']['colspan'])){
								$colspan = intval($dom[$key]['attribute']['colspan']);
							} else{
								$colspan = 1;
							}
							$dom[$key]['attribute']['colspan'] = $colspan;
							$dom[($dom[$key]['parent'])]['cols'] += $colspan;
						}
						if(isset($dom[$key]['attribute']['color']) AND(!empty($dom[$key]['attribute']['color']))){
							$dom[$key]['fgcolor'] = $this->convertHTMLColorToDec($dom[$key]['attribute']['color']);
						}
						if(isset($dom[$key]['attribute']['bgcolor']) AND(!empty($dom[$key]['attribute']['bgcolor']))){
							$dom[$key]['bgcolor'] = $this->convertHTMLColorToDec($dom[$key]['attribute']['bgcolor']);
						}
						if(isset($dom[$key]['attribute']['width'])){
							$dom[$key]['width'] = intval($dom[$key]['attribute']['width']);
						}
						if(isset($dom[$key]['attribute']['height'])){
							$dom[$key]['height'] = intval($dom[$key]['attribute']['height']);
						}
						if(isset($dom[$key]['attribute']['align']) AND(!empty($dom[$key]['attribute']['align'])) AND($dom[$key]['value'] !== 'img')){
							$dom[$key]['align'] = strtoupper($dom[$key]['attribute']['align']{0});
						}
					}
				} else{
					$dom[$key]['tag'] = false;
					$dom[$key]['value'] = stripslashes($this->unhtmlentities($element));
					$dom[$key]['parent'] = end($level);
				}
				$key++;
			}
			return $dom;
		}
		public function writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align=''){
			$gvars = $this->getGraphicVars();
			$prevPage = $this->page;
			$prevlMargin = $this->lMargin;
			$prevrMargin = $this->rMargin;
			$curfontname = $this->FontFamily;
			$curfontstyle = $this->FontStyle;
			$curfontsize = $this->FontSizePt;
			$this->newline = true;
			$minstartliney = $this->y;
			$yshift = 0;
			$startlinepage = $this->page;
			$newline = true;
			$loop = 0;
			$curpos = 0;
			$blocktags = array('blockquote','br','dd','div','dt','h1','h2','h3','h4','h5','h6','hr','li','ol','p','ul');
			if(isset($this->PageAnnots[$this->page])){
				$pask = count($this->PageAnnots[$this->page]);
			} else{
				$pask = 0;
			}
			if(isset($this->footerlen[$this->page])){
				$this->footerpos[$this->page] = strlen($this->pages[$this->page]) - $this->footerlen[$this->page];
			} else{
				$this->footerpos[$this->page] = strlen($this->pages[$this->page]);
			}
			$startlinepos = $this->footerpos[$this->page];
			$lalign = $align;
			$plalign = $align;
			if($this->rtl){
				$w = $this->x - $this->lMargin;
			} else{
				$w = $this->w - $this->rMargin - $this->x;
			}
			$w -=(2 * $this->cMargin);
			if($cell){
				if($this->rtl){
					$this->x -= $this->cMargin;
				} else{
					$this->x += $this->cMargin;
				}
			}
			if($this->customlistindent >= 0){
				$this->listindent = $this->customlistindent;
			} else{
				$this->listindent = $this->GetStringWidth('0000');
			}
			$this->listnum = 0;
			if((empty($this->lasth))OR($reseth)){
				$this->lasth = $this->FontSize * $this->cell_height_ratio;
			}
			$dom = $this->getHtmlDomArray($html);
			$maxel = count($dom);
			$key = 0;
			while($key < $maxel){
				if($dom[$key]['tag'] OR($key == 0)){
					if((($dom[$key]['value'] == 'table') OR($dom[$key]['value'] == 'tr')) AND(isset($dom[$key]['align']))){
						$dom[$key]['align'] =($this->rtl) ? 'R' : 'L';
					}
					if((!$this->newline)
						AND($dom[$key]['value'] == 'img')
						AND(isset($dom[$key]['attribute']['height']))
						AND($dom[$key]['attribute']['height'] > 0)
						AND(!((($this->y + $this->pixelsToUnits($dom[$key]['attribute']['height'])) > $this->PageBreakTrigger)
							AND(!$this->InFooter)
							AND $this->AcceptPageBreak()))
						){
						if($this->page > $startlinepage){
							if(isset($this->footerlen[$startlinepage])){
								$curpos = strlen($this->pages[$startlinepage]) - $this->footerlen[$startlinepage];
							}
							$linebeg = substr($this->pages[$startlinepage], $startlinepos,($curpos - $startlinepos));
							$tstart = substr($this->pages[$startlinepage], 0, $startlinepos);
							$tend = substr($this->pages[$startlinepage], $curpos);
							$this->pages[$startlinepage] = $tstart.''.$tend;
							$tstart = substr($this->pages[$this->page], 0, $this->intmrk[$this->page]);
							$tend = substr($this->pages[$this->page], $this->intmrk[$this->page]);
							$yshift = $minstartliney - $this->y;
							$try = sprintf('1 0 0 1 0 %.3f cm',($yshift * $this->k));
							$this->pages[$this->page] = $tstart."\nq\n".$try."\n".$linebeg."\nQ\n".$tend;
							if(isset($this->PageAnnots[$startlinepage])){
								foreach($this->PageAnnots[$startlinepage] as $pak => $pac){
									if($pak >= $pask){
										$this->PageAnnots[$this->page][] = $pac;
										unset($this->PageAnnots[$startlinepage][$pak]);
										$npak = count($this->PageAnnots[$this->page]) - 1;
										$this->PageAnnots[$this->page][$npak]['y'] -= $yshift;
									}
								}
							}
						}
						$this->y +=(($curfontsize / $this->k) - $this->pixelsToUnits($dom[$key]['attribute']['height']));
						$minstartliney = min($this->y, $minstartliney);
					} elseif(isset($dom[$key]['fontname']) OR isset($dom[$key]['fontstyle']) OR isset($dom[$key]['fontsize'])){
						$pfontname = $curfontname;
						$pfontstyle = $curfontstyle;
						$pfontsize = $curfontsize;
						$fontname = isset($dom[$key]['fontname']) ? $dom[$key]['fontname'] : $curfontname;
						$fontstyle = isset($dom[$key]['fontstyle']) ? $dom[$key]['fontstyle'] : $curfontstyle;
						$fontsize = isset($dom[$key]['fontsize']) ? $dom[$key]['fontsize'] : $curfontsize;
						if(($fontname != $curfontname) OR($fontstyle != $curfontstyle) OR($fontsize != $curfontsize)){
							$this->SetFont($fontname, $fontstyle, $fontsize);
							$this->lasth = $this->FontSize * $this->cell_height_ratio;
							if(is_numeric($fontsize) AND($fontsize > 0)
								AND is_numeric($curfontsize) AND($curfontsize > 0)
								AND($fontsize != $curfontsize) AND(!$this->newline)
								AND($key <($maxel - 1))
								){
								if((!$this->newline) AND($this->page > $startlinepage)){
									if(isset($this->footerlen[$startlinepage])){
										$curpos = strlen($this->pages[$startlinepage]) - $this->footerlen[$startlinepage];
									}
									$linebeg = substr($this->pages[$startlinepage], $startlinepos,($curpos - $startlinepos));
									$tstart = substr($this->pages[$startlinepage], 0, $startlinepos);
									$tend = substr($this->pages[$startlinepage], $curpos);
									$this->pages[$startlinepage] = $tstart.''.$tend;
									$tstart = substr($this->pages[$this->page], 0, $this->intmrk[$this->page]);
									$tend = substr($this->pages[$this->page], $this->intmrk[$this->page]);
									$yshift = $minstartliney - $this->y;
									$try = sprintf('1 0 0 1 0 %.3f cm',($yshift * $this->k));
									$this->pages[$this->page] = $tstart."\nq\n".$try."\n".$linebeg."\nQ\n".$tend;
									if(isset($this->PageAnnots[$startlinepage])){
										foreach($this->PageAnnots[$startlinepage] as $pak => $pac){
											if($pak >= $pask){
												$this->PageAnnots[$this->page][] = $pac;
												unset($this->PageAnnots[$startlinepage][$pak]);
												$npak = count($this->PageAnnots[$this->page]) - 1;
												$this->PageAnnots[$this->page][$npak]['y'] -= $yshift;
											}
										}
									}
								}
								$this->y +=(($curfontsize - $fontsize) / $this->k);
								$minstartliney = min($this->y, $minstartliney);
							}
							$curfontname = $fontname;
							$curfontstyle = $fontstyle;
							$curfontsize = $fontsize;
						}
					}
					if(($plalign == 'J') AND(in_array($dom[$key]['value'], $blocktags))){
						$plalign = '';
					}
					$curpos = strlen($this->pages[$startlinepage]);
					if(isset($dom[$key]['bgcolor']) AND($dom[$key]['bgcolor'] !== false)){
						$this->SetFillColorArray($dom[$key]['bgcolor']);
						$wfill = true;
					} else{
						$wfill = $fill | false;
					}
					if(isset($dom[$key]['fgcolor']) AND($dom[$key]['fgcolor'] !== false)){
						$this->SetTextColorArray($dom[$key]['fgcolor']);
					}
					if(isset($dom[$key]['align'])){
						$lalign = $dom[$key]['align'];
					}
					if(empty($lalign)){
						$lalign = $align;
					}
				}
				if($this->newline AND(strlen($dom[$key]['value']) > 0) AND($dom[$key]['value'] != 'td') AND($dom[$key]['value'] != 'th')){
					$newline = true;
					if(isset($startlinex)){
						$yshift = $minstartliney - $startliney;
						if(($yshift > 0) OR($this->page > $startlinepage)){
							$yshift = 0;
						}
						if((isset($plalign) AND((($plalign == 'C') OR($plalign == 'J') OR(($plalign == 'R') AND(!$this->rtl)) OR(($plalign == 'L') AND($this->rtl))))) OR($yshift < 0)){
							$linew = abs($this->endlinex - $startlinex);
							$pstart = substr($this->pages[$startlinepage], 0, $startlinepos);
							if(isset($opentagpos) AND isset($this->footerlen[$startlinepage])){
								$this->footerpos[$startlinepage] = strlen($this->pages[$startlinepage]) - $this->footerlen[$startlinepage];
								$midpos = min($opentagpos, $this->footerpos[$startlinepage]);
							} elseif(isset($opentagpos)){
								$midpos = $opentagpos;
							} elseif(isset($this->footerlen[$startlinepage])){
								$this->footerpos[$startlinepage] = strlen($this->pages[$startlinepage]) - $this->footerlen[$startlinepage];
								$midpos = $this->footerpos[$startlinepage];
							} else{
								$midpos = 0;
							}
							if($midpos > 0){
								$pmid = substr($this->pages[$startlinepage], $startlinepos,($midpos - $startlinepos));
								$pend = substr($this->pages[$startlinepage], $midpos);
							} else{
								$pmid = substr($this->pages[$startlinepage], $startlinepos);
								$pend = '';
							}
							$tw = $w;
							if($this->lMargin != $prevlMargin){
								$tw +=($prevlMargin - $this->lMargin);
							}
							if($this->rMargin != $prevrMargin){
								$tw +=($prevrMargin - $this->rMargin);
							}
							$mdiff = abs($tw - $linew);
							$t_x = 0;
							if($plalign == 'C'){
								if($this->rtl){
									$t_x = -($mdiff / 2);
								} else{
									$t_x =($mdiff / 2);
								}
							} elseif(($plalign == 'R') AND(!$this->rtl)){
								$t_x = $mdiff;
							} elseif(($plalign == 'L') AND($this->rtl)){
								$t_x = -$mdiff;
							} elseif(($plalign == 'J') AND($plalign == $lalign)){
								if($this->rtl OR $this->tmprtl){
									$t_x = $this->lMargin - $this->endlinex;
								}
								$no = 0;
								$ns = 0;
								$pmidtemp = $pmid;
								$pmidtemp = preg_replace('/[\\\][\(]/x', '\\#!#OP#!#', $pmidtemp);
								$pmidtemp = preg_replace('/[\\\][\)]/x', '\\#!#CP#!#', $pmidtemp);
								if(preg_match_all('/\[\(([^\)]*)\)\]/x', $pmidtemp, $lnstring, PREG_PATTERN_ORDER)){
									$maxkk = count($lnstring[1]) - 1;
									for($kk=0; $kk <= $maxkk; $kk++){
										$lnstring[1][$kk] = str_replace('#!#OP#!#', '(', $lnstring[1][$kk]);
										$lnstring[1][$kk] = str_replace('#!#CP#!#', ')', $lnstring[1][$kk]);
										if($kk == $maxkk){
											if($this->rtl OR $this->tmprtl){
												$tvalue = ltrim($lnstring[1][$kk]);
											} else{
												$tvalue = rtrim($lnstring[1][$kk]);
											}
										} else{
											$tvalue = $lnstring[1][$kk];
										}
										$no += substr_count($lnstring[1][$kk], chr(32));
										$ns += substr_count($tvalue, chr(32));
									}
									if($this->rtl OR $this->tmprtl){
										$t_x = $this->lMargin - $this->endlinex -(($no - $ns - 1) * $this->GetStringWidth(chr(32)));
									}
									$spacewidth =(($tw - $linew +(($no - $ns) * $this->GetStringWidth(chr(32)))) /($ns?$ns:1)) * $this->k;
									$spacewidthu =($tw - $linew +($no * $this->GetStringWidth(chr(32)))) /($ns?$ns:1) / $this->FontSize / $this->k;
									$nsmax = $ns;
									$ns = 0;
									reset($lnstring);
									$offset = 0;
									$strcount = 0;
									$prev_epsposbeg = 0;
									global $spacew;
									while(preg_match('/([0-9\.\+\-]*)[\s](Td|cm|m|l|c|re)[\s]/x', $pmid, $strpiece, PREG_OFFSET_CAPTURE, $offset) == 1){
										if($this->rtl OR $this->tmprtl){
											$spacew =($spacewidth *($nsmax - $ns));
										} else{
											$spacew =($spacewidth * $ns);
										}
										$offset = $strpiece[2][1] + strlen($strpiece[2][0]);
										$epsposbeg = strpos($pmid, 'q'.$this->epsmarker, $offset);
										$epsposend = strpos($pmid, $this->epsmarker.'Q', $offset) + strlen($this->epsmarker.'Q');
										if((($epsposbeg > 0) AND($epsposend > 0) AND($offset > $epsposbeg) AND($offset < $epsposend))
											OR(($epsposbeg === false) AND($epsposend > 0) AND($offset < $epsposend))){
											$trx = sprintf('1 0 0 1 %.3f 0 cm', $spacew);
											$epsposbeg = strpos($pmid, 'q'.$this->epsmarker,($prev_epsposbeg - 6));
											$pmid_b = substr($pmid, 0, $epsposbeg);
											$pmid_m = substr($pmid, $epsposbeg,($epsposend - $epsposbeg));
											$pmid_e = substr($pmid, $epsposend);
											$pmid = $pmid_b."\nq\n".$trx."\n".$pmid_m."\nQ\n".$pmid_e;
											$offset = $epsposend;
											continue;
										}
										$prev_epsposbeg = $epsposbeg;
										$currentxpos = 0;
										switch($strpiece[2][0]){
											case 'Td':
											case 'cm':
											case 'm':
											case 'l':{
												preg_match('/([0-9\.\+\-]*)[\s]('.$strpiece[1][0].')[\s]('.$strpiece[2][0].')([\s]*)/x', $pmid, $xmatches);
												$currentxpos = $xmatches[1];
												if(($strcount <= $maxkk) AND($strpiece[2][0] == 'Td')){
													if($strcount == $maxkk){
														if($this->rtl OR $this->tmprtl){
															$tvalue = $lnstring[1][$strcount];
														} else{
															$tvalue = rtrim($lnstring[1][$strcount]);
														}
													} else{
														$tvalue = $lnstring[1][$strcount];
													}
													$ns += substr_count($tvalue, chr(32));
													$strcount++;
												}
												if($this->rtl OR $this->tmprtl){
													$spacew =($spacewidth *($nsmax - $ns));
												}
												$pmid = preg_replace_callback('/([0-9\.\+\-]*)[\s]('.$strpiece[1][0].')[\s]('.$strpiece[2][0].')([\s]*)/x',
													create_function('$matches', 'global $spacew; 
													$newx = sprintf("%.2f",(floatval($matches[1]) + $spacew));
													return "".$newx." ".$matches[2]." x*#!#*x".$matches[3].$matches[4];'), $pmid, 1);
												break;
											}
											case 're':{
												preg_match('/([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]('.$strpiece[1][0].')[\s]('.$strpiece[2][0].')([\s]*)/x', $pmid, $xmatches);
												$currentxpos = $xmatches[1];
												$pmid = preg_replace_callback('/([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]('.$strpiece[1][0].')[\s]('.$strpiece[2][0].')([\s]*)/x',
													create_function('$matches', 'global $spacew; 
													$newx = sprintf("%.2f",(floatval($matches[1]) + $spacew));
													return "".$newx." ".$matches[2]." ".$matches[3]." ".$matches[4]." x*#!#*x".$matches[5].$matches[6];'), $pmid, 1);
												break;
											}
											case 'c':{
												preg_match('/([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]('.$strpiece[1][0].')[\s]('.$strpiece[2][0].')([\s]*)/x', $pmid, $xmatches);
												$currentxpos = $xmatches[1];
												$pmid = preg_replace_callback('/([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]([0-9\.\+\-]*)[\s]('.$strpiece[1][0].')[\s]('.$strpiece[2][0].')([\s]*)/x',
													create_function('$matches', 'global $spacew; 
													$newx1 = sprintf("%.3f",(floatval($matches[1]) + $spacew));
													$newx2 = sprintf("%.3f",(floatval($matches[3]) + $spacew));
													$newx3 = sprintf("%.3f",(floatval($matches[5]) + $spacew));
													return "".$newx1." ".$matches[2]." ".$newx2." ".$matches[4]." ".$newx3." ".$matches[6]." x*#!#*x".$matches[7].$matches[8];'), $pmid, 1);
												break;
											}
										}
										if(isset($this->PageAnnots[$this->page])){
											foreach($this->PageAnnots[$this->page] as $pak => $pac){
												if(($pac['y'] >= $minstartliney) AND(($pac['x'] * $this->k) >=($currentxpos - $this->feps)) AND(($pac['x'] * $this->k) <=($currentxpos + $this->feps))){
													$this->PageAnnots[$this->page][$pak]['x'] +=($spacew / $this->k);
													$this->PageAnnots[$this->page][$pak]['w'] +=(($spacewidth * $pac['numspaces']) / $this->k);
													break;
												}
											}
										}
									}
									$pmid = str_replace('x*#!#*x', '', $pmid);
									if(($this->CurrentFont['type'] == 'TrueTypeUnicode') OR($this->CurrentFont['type'] == 'cidfont0')){
										$spacew = $spacewidthu;
										$pmidtemp = $pmid;
										$pmidtemp = preg_replace('/[\\\][\(]/x', '\\#!#OP#!#', $pmidtemp);
										$pmidtemp = preg_replace('/[\\\][\)]/x', '\\#!#CP#!#', $pmidtemp);
										$pmid = preg_replace_callback("/\[\(([^\)]*)\)\]/x",
													create_function('$matches', 'global $spacew;
													$matches[1] = str_replace("#!#OP#!#", "(", $matches[1]);
													$matches[1] = str_replace("#!#CP#!#", ")", $matches[1]);
													return "[(".str_replace(chr(0).chr(32), ") ".(-2830 * $spacew)." (", $matches[1]).")]";'), $pmidtemp);
										$this->pages[$startlinepage] = $pstart."\n".$pmid."\n".$pend;
										$endlinepos = strlen($pstart."\n".$pmid."\n");
									} else{
										$rs = sprintf("%.3f Tw", $spacewidth);
										$pmid = preg_replace("/\[\(/x", $rs.' [(', $pmid);
										$this->pages[$startlinepage] = $pstart."\n".$pmid."\nBT 0 Tw ET\n".$pend;
										$endlinepos = strlen($pstart."\n".$pmid."\nBT 0 Tw ET\n");
									}
								}
							}
							if(($t_x != 0) OR($yshift < 0)){
								$trx = sprintf('1 0 0 1 %.3f %.3f cm',($t_x * $this->k),($yshift * $this->k));
								$this->pages[$startlinepage] = $pstart."\nq\n".$trx."\n".$pmid."\nQ\n".$pend;
								$endlinepos = strlen($pstart."\nq\n".$trx."\n".$pmid."\nQ\n");
								if(isset($this->PageAnnots[$this->page])){
									foreach($this->PageAnnots[$this->page] as $pak => $pac){
										if($pak >= $pask){
											$this->PageAnnots[$this->page][$pak]['x'] += $t_x;
											$this->PageAnnots[$this->page][$pak]['y'] -= $yshift;
										}
									}
								}
								$this->y -= $yshift;
							}
						}
					}
					$this->newline = false;
					$pbrk = $this->checkPageBreak($this->lasth);
					$this->SetFont($fontname, $fontstyle, $fontsize);
					if($wfill){
						$this->SetFillColorArray($this->bgcolor);
					}
					$startlinex = $this->x;
					$startliney = $this->y;
					$minstartliney = $this->y;
					$startlinepage = $this->page;
					if(isset($endlinepos) AND(!$pbrk)){
						$startlinepos = $endlinepos;
						unset($endlinepos);
					} else{
						if(isset($this->footerlen[$this->page])){
							$this->footerpos[$this->page] = strlen($this->pages[$this->page]) - $this->footerlen[$this->page];
						} else{
							$this->footerpos[$this->page] = strlen($this->pages[$this->page]);
						}
						$startlinepos = $this->footerpos[$this->page];
					}
					$plalign = $lalign;
					if(isset($this->PageAnnots[$this->page])){
						$pask = count($this->PageAnnots[$this->page]);
					} else{
						$pask = 0;
					}
				}
				if(isset($opentagpos)){
					unset($opentagpos);
				}
				if($dom[$key]['tag']){
					if($dom[$key]['opening']){
						if(($dom[$key]['value'] == 'td') OR($dom[$key]['value'] == 'th')){
							$trid = $dom[$key]['parent'];
							$table_el = $dom[$trid]['parent'];
							if(!isset($dom[$table_el]['cols'])){
								$dom[$table_el]['cols'] = $trid['cols'];
							}
							if(isset($dom[$trid]['width'])){
								$table_width = $this->pixelsToUnits($dom[$trid]['width']);
							} else{
								$table_width = $w;
							}
							if(isset($dom[($dom[$trid]['parent'])]['attribute']['cellpadding'])){
								$currentcmargin = $this->pixelsToUnits($dom[($dom[$trid]['parent'])]['attribute']['cellpadding']);
							} else{
								$currentcmargin = 0;
							}
							$this->cMargin = $currentcmargin;
							if(isset($dom[($dom[$trid]['parent'])]['attribute']['cellspacing'])){
								$cellspacing = $this->pixelsToUnits($dom[($dom[$trid]['parent'])]['attribute']['cellspacing']);
							} else{
								$cellspacing = 0;
							}
							if($this->rtl){
								$cellspacingx = -$cellspacing;
							} else{
								$cellspacingx = $cellspacing;
							}
							$colspan = $dom[$key]['attribute']['colspan'];
							if(isset($dom[$key]['width'])){
								$cellw = $this->pixelsToUnits($dom[$key]['width']);
							} else{
								$cellw =($colspan *($table_width / $dom[$table_el]['cols']));
							}
							$cellw -= $cellspacing;
							if(isset($dom[$key]['content'])){
								$cell_content = $dom[$key]['content'];
							} else{
								$cell_content = '&nbsp;';
							}
							$tagtype = $dom[$key]['value'];
							$parentid = $key;
							while(($key < $maxel) AND(!(($dom[$key]['tag']) AND(!$dom[$key]['opening']) AND($dom[$key]['value'] == $tagtype) AND($dom[$key]['parent'] == $parentid)))){
								$key++;
							}
							if(!isset($dom[$trid]['startpage'])){
								$dom[$trid]['startpage'] = $this->page;
							} else{
								$this->setPage($dom[$trid]['startpage']);
							}
							if(!isset($dom[$trid]['starty'])){
								$dom[$trid]['starty'] = $this->y;
							} else{
								$this->y = $dom[$trid]['starty'];
							}
							if(!isset($dom[$trid]['startx'])){
								$dom[$trid]['startx'] = $this->x;
							}
							$this->x +=($cellspacingx / 2);
							if(isset($dom[$parentid]['attribute']['rowspan'])){
								$rowspan = intval($dom[$parentid]['attribute']['rowspan']);
							} else{
								$rowspan = 1;
							}
							if(isset($dom[$table_el]['rowspans'])){
								$rsk = 0;
								$rskmax = count($dom[$table_el]['rowspans']);
								while($rsk < $rskmax){
									$trwsp = $dom[$table_el]['rowspans'][$rsk];
									$rsstartx = $trwsp['startx'];
									$rsendx = $trwsp['endx'];
									if($trwsp['startpage'] < $this->page){
										if(($this->rtl) AND($this->pagedim[$this->page]['orm'] != $this->pagedim[$trwsp['startpage']]['orm'])){
											$dl =($this->pagedim[$this->page]['orm'] - $this->pagedim[$trwsp['startpage']]['orm']);
											$rsstartx -= $dl;
											$rsendx -= $dl;
										} elseif((!$this->rtl) AND($this->pagedim[$this->page]['olm'] != $this->pagedim[$trwsp['startpage']]['olm'])){
											$dl =($this->pagedim[$this->page]['olm'] - $this->pagedim[$trwsp['startpage']]['olm']);
											$rsstartx += $dl;
											$rsendx += $dl;
										}
									}
									if(($trwsp['rowspan'] > 0)
										AND($rsstartx >($this->x - $cellspacing - $currentcmargin - $this->feps))
										AND($rsstartx <($this->x + $cellspacing + $currentcmargin + $this->feps))
										AND(($trwsp['starty'] <($this->y - $this->feps)) OR($trwsp['startpage'] < $this->page))){
										$this->x = $rsendx + $cellspacingx;
										if(($trwsp['rowspan'] == 1)
											AND(isset($dom[$trid]['endy']))
											AND(isset($dom[$trid]['endpage']))
											AND($trwsp['endpage'] == $dom[$trid]['endpage'])){
											$dom[$table_el]['rowspans'][$rsk]['endy'] = max($dom[$trid]['endy'], $trwsp['endy']);
											$dom[$trid]['endy'] = $dom[$table_el]['rowspans'][$rsk]['endy'];
										}
										$rsk = 0;
									} else{
										$rsk++;
									}
								}
							}
							if($rowspan > 1){
								if(isset($this->footerlen[$this->page])){
									$this->footerpos[$this->page] = strlen($this->pages[$this->page]) - $this->footerlen[$this->page];
								} else{
									$this->footerpos[$this->page] = strlen($this->pages[$this->page]);
								}
								$trintmrkpos = $this->footerpos[$this->page];
								$trsid = array_push($dom[$table_el]['rowspans'], array('trid' => $trid, 'rowspan' => $rowspan, 'mrowspan' => $rowspan, 'colspan' => $colspan, 'startpage' => $this->page, 'startx' => $this->x, 'starty' => $this->y, 'intmrkpos' => $trintmrkpos));
							}
							$cellid = array_push($dom[$trid]['cellpos'], array('startx' => $this->x));
							if($rowspan > 1){
								$dom[$trid]['cellpos'][($cellid - 1)]['rowspanid'] =($trsid - 1);
							}
							if(isset($dom[$parentid]['bgcolor']) AND($dom[$parentid]['bgcolor'] !== false)){
								$dom[$trid]['cellpos'][($cellid - 1)]['bgcolor'] = $dom[$parentid]['bgcolor'];
							}
							$prevLastH= $this->lasth;
							$this->MultiCell($cellw, 0, $cell_content, false, $lalign, false, 2, '', '', true, 0, true);
							$this->lasth = $prevLastH;
							$this->cMargin = $currentcmargin;
							$dom[$trid]['cellpos'][($cellid - 1)]['endx'] = $this->x;
							if($rowspan <= 1){
								if(isset($dom[$trid]['endy'])){
									if($this->page == $dom[$trid]['endpage']){
										$dom[$trid]['endy'] = max($this->y, $dom[$trid]['endy']);
									} elseif($this->page > $dom[$trid]['endpage']){
										$dom[$trid]['endy'] = $this->y;
									}
								} else{
									$dom[$trid]['endy'] = $this->y;
								}
								if(isset($dom[$trid]['endpage'])){
									$dom[$trid]['endpage'] = max($this->page, $dom[$trid]['endpage']);
								} else{
									$dom[$trid]['endpage'] = $this->page;
								}
							} else{
								$dom[$table_el]['rowspans'][($trsid - 1)]['endx'] = $this->x;
								$dom[$table_el]['rowspans'][($trsid - 1)]['endy'] = $this->y;
								$dom[$table_el]['rowspans'][($trsid - 1)]['endpage'] = $this->page;
							}
							if(isset($dom[$table_el]['rowspans'])){
								foreach($dom[$table_el]['rowspans'] as $k => $trwsp){
									if($trwsp['rowspan'] > 0){
										if(isset($dom[$trid]['endpage'])){
											if($trwsp['endpage'] == $dom[$trid]['endpage']){
												$dom[$table_el]['rowspans'][$k]['endy'] = max($dom[$trid]['endy'], $trwsp['endy']);
											} elseif($trwsp['endpage'] < $dom[$trid]['endpage']){
												$dom[$table_el]['rowspans'][$k]['endy'] = $dom[$trid]['endy'];
												$dom[$table_el]['rowspans'][$k]['endpage'] = $dom[$trid]['endpage'];
											} else{
												$dom[$trid]['endy'] = $this->pagedim[$dom[$trid]['endpage']]['hk'] - $this->pagedim[$dom[$trid]['endpage']]['bm'];
											}
										}
									}
								}
							}
							$this->x +=($cellspacingx / 2);
						} else{
							if(!isset($opentagpos)){
								if(isset($this->footerlen[$this->page])){
									$this->footerpos[$this->page] = strlen($this->pages[$this->page]) - $this->footerlen[$this->page];
								} else{
									$this->footerpos[$this->page] = strlen($this->pages[$this->page]);
								}
								$opentagpos = $this->footerpos[$this->page];
							}
							$this->openHTMLTagHandler($dom, $key, $cell);
						}
					} else{
						$this->closeHTMLTagHandler($dom, $key, $cell);
					}
				} elseif(strlen($dom[$key]['value']) > 0){
					if(!empty($this->lispacer)){
						$this->SetFont($pfontname, $pfontstyle, $pfontsize);
						$this->lasth = $this->FontSize * $this->cell_height_ratio;
						$minstartliney = $this->y;
						$tmpx = $this->x;
						$lspace = $this->GetStringWidth($this->lispacer.'  ');
						if($this->rtl){
							$this->x += $lspace;
						} else{
							$this->x -= $lspace;
						}
						$this->Write($this->lasth, $this->lispacer, '', false, '', false, 0, false);
						$this->x = $tmpx;
						$this->lispacer = '';
						$this->SetFont($curfontname, $curfontstyle, $curfontsize);
						$this->lasth = $this->FontSize * $this->cell_height_ratio;
						if(is_numeric($pfontsize) AND($pfontsize > 0) AND is_numeric($curfontsize) AND($curfontsize > 0) AND($pfontsize != $curfontsize)){
							$this->y +=(($pfontsize - $curfontsize) / $this->k);
							$minstartliney = min($this->y, $minstartliney);
						}
					}
					$this->htmlvspace = 0;
					if($this->rtl OR $this->tmprtl){
						$len1 = strlen($dom[$key]['value']);
						$lsp = $len1 - strlen(ltrim($dom[$key]['value']));
						$rsp = $len1 - strlen(rtrim($dom[$key]['value']));
						$tmpstr = '';
						if($rsp > 0){
							$tmpstr .= substr($dom[$key]['value'], -$rsp);
						}
						$tmpstr .= trim($dom[$key]['value']);
						if($lsp > 0){
							$tmpstr .= substr($dom[$key]['value'], 0, $lsp);
						}
						$dom[$key]['value'] = $tmpstr;
					}
					if($newline){
						if(($this->rtl OR $this->tmprtl)){
							$dom[$key]['value'] = rtrim($dom[$key]['value']);
						} else{
							$dom[$key]['value'] = ltrim($dom[$key]['value']);
						}
						$newline = false;
						$firstblock = true;
					} else{
						$firstblock = false;
					}
					if($this->HREF){
						$strrest = $this->addHtmlLink($this->HREF, $dom[$key]['value'], $wfill, true);
					} else{
						$ctmpmargin = $this->cMargin;
						$this->cMargin = 0;
						$strrest = $this->Write($this->lasth, $dom[$key]['value'], '', $wfill, '', false, 0, true, $firstblock);
						$this->cMargin = $ctmpmargin;
					}
					if(strlen($strrest) > 0){
						$this->newline = true;
						if($cell){
							if($this->rtl){
								$this->x -= $this->cMargin;
							} else{
								$this->x += $this->cMargin;
							}
						}
						if($strrest == $dom[$key]['value']){
							$loop++;
						} else{
							$loop = 0;
						}
						$dom[$key]['value'] = ltrim($strrest);
						if($loop < 3){
							$key--;
						}
					} else{
						$loop = 0;
					}
				}
				$key++;
			}
			if(isset($startlinex)){
				$yshift = $minstartliney - $startliney;
				if(($yshift > 0) OR($this->page > $startlinepage)){
					$yshift = 0;
				}
				if((isset($plalign) AND((($plalign == 'C') OR($plalign == 'J') OR(($plalign == 'R') AND(!$this->rtl)) OR(($plalign == 'L') AND($this->rtl))))) OR($yshift < 0)){
					$linew = abs($this->endlinex - $startlinex);
					$pstart = substr($this->pages[$startlinepage], 0, $startlinepos);
					if(isset($opentagpos) AND isset($this->footerlen[$startlinepage])){
						$this->footerpos[$startlinepage] = strlen($this->pages[$startlinepage]) - $this->footerlen[$startlinepage];
						$midpos = min($opentagpos, $this->footerpos[$startlinepage]);
					} elseif(isset($opentagpos)){
						$midpos = $opentagpos;
					} elseif(isset($this->footerlen[$startlinepage])){
						$this->footerpos[$startlinepage] = strlen($this->pages[$startlinepage]) - $this->footerlen[$startlinepage];
						$midpos = $this->footerpos[$startlinepage];
					} else{
						$midpos = 0;
					}
					if($midpos > 0){
						$pmid = substr($this->pages[$startlinepage], $startlinepos,($midpos - $startlinepos));
						$pend = substr($this->pages[$startlinepage], $midpos);
					} else{
						$pmid = substr($this->pages[$startlinepage], $startlinepos);
						$pend = '';
					}
					$tw = $w;
					if($this->lMargin != $prevlMargin){
						$tw +=($prevlMargin - $this->lMargin);
					}
					if($this->rMargin != $prevrMargin){
						$tw +=($prevrMargin - $this->rMargin);
					}
					$mdiff = abs($tw - $linew);
					if($plalign == 'C'){
						if($this->rtl){
							$t_x = -($mdiff / 2);
						} else{
							$t_x =($mdiff / 2);
						}
					} elseif(($plalign == 'R') AND(!$this->rtl)){
						$t_x = $mdiff;
					} elseif(($plalign == 'L') AND($this->rtl)){
						$t_x = -$mdiff;
					} else{
						$t_x = 0;
					}
					if(($t_x != 0) OR($yshift < 0)){
						$trx = sprintf('1 0 0 1 %.3f %.3f cm',($t_x * $this->k),($yshift * $this->k));
						$this->pages[$startlinepage] = $pstart."\nq\n".$trx."\n".$pmid."\nQ\n".$pend;
						$endlinepos = strlen($pstart."\nq\n".$trx."\n".$pmid."\nQ\n");
						if(isset($this->PageAnnots[$this->page])){
							foreach($this->PageAnnots[$this->page] as $pak => $pac){
								if($pak >= $pask){
									$this->PageAnnots[$this->page][$pak]['x'] += $t_x;
									$this->PageAnnots[$this->page][$pak]['y'] -= $yshift;
								}
							}
						}
						$this->y -= $yshift;
					}
				}
			}
			if($ln AND(!($cell AND($dom[$key-1]['value'] == 'table')))){
				$this->Ln($this->lasth);
			}
			$this->setGraphicVars($gvars);
			if($this->page > $prevPage){
				$this->lMargin = $this->pagedim[$this->page]['olm'];
				$this->rMargin = $this->pagedim[$this->page]['orm'];
			}
			unset($dom);
		}
		protected function openHTMLTagHandler(&$dom, $key, $cell=false){
			$tag = $dom[$key];
			$parent = $dom[($dom[$key]['parent'])];
			$firstorlast =($key == 1);
			if(isset($tag['attribute']['dir'])){
				$this->tmprtl = $tag['attribute']['dir'] == 'rtl' ? 'R' : 'L';
			} else{
				$this->tmprtl = false;
			}
			switch($tag['value']){
				case 'table':{
					$cp = 0;
					$cs = 0;
					$dom[$key]['rowspans'] = array();
					if(isset($tag['attribute']['cellpadding'])){
						$cp = $this->pixelsToUnits($tag['attribute']['cellpadding']);
						$this->oldcMargin = $this->cMargin;
						$this->cMargin = $cp;
					}
					if(isset($tag['attribute']['cellspacing'])){
						$cs = $this->pixelsToUnits($tag['attribute']['cellspacing']);
					}
					$this->checkPageBreak((2 * $cp) +(2 * $cs) + $this->lasth);
					break;
				}
				case 'tr':{
					$dom[$key]['cellpos'] = array();
					break;
				}
				case 'hr':{
					$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
					$this->htmlvspace = 0;
					if((isset($tag['attribute']['width'])) AND($tag['attribute']['width'] != '')){
						$hrWidth = $this->pixelsToUnits($tag['attribute']['width']);
					} else{
						$hrWidth = $this->w - $this->lMargin - $this->rMargin;
					}
					$x = $this->GetX();
					$y = $this->GetY();
					$prevlinewidth = $this->GetLineWidth();
					$this->Line($x, $y, $x + $hrWidth, $y);
					$this->SetLineWidth($prevlinewidth);
					$this->addHTMLVertSpace(1, $cell, '', !isset($dom[($key + 1)]), $tag['value'], false);
					break;
				}
				case 'a':{
					if(array_key_exists('href', $tag['attribute'])){
						$this->HREF = $tag['attribute']['href'];
					}
					break;
				}
				case 'img':{
					if(isset($tag['attribute']['src'])){
						if($tag['attribute']['src'][0] == '/'){
							$tag['attribute']['src'] = $_SERVER['DOCUMENT_ROOT'].$tag['attribute']['src'];
						}
						$tag['attribute']['src'] = str_replace(K_PATH_URL, K_PATH_MAIN, $tag['attribute']['src']);
						if(!isset($tag['attribute']['width'])){
							$tag['attribute']['width'] = 0;
						}
						if(!isset($tag['attribute']['height'])){
							$tag['attribute']['height'] = 0;
						}
							$tag['attribute']['align'] = 'bottom';
						switch($tag['attribute']['align']){
							case 'top':{
								$align = 'T';
								break;
							}
							case 'middle':{
								$align = 'M';
								break;
							}
							case 'bottom':{
								$align = 'B';
								break;
							}
							default:{
								$align = 'B';
								break;
							}
						}
						$fileinfo = pathinfo($tag['attribute']['src']);
						if(isset($fileinfo['extension']) AND(!empty($fileinfo['extension']))){
							$type = strtolower($fileinfo['extension']);
						}
						$prevy = $this->y;
						$xpos = $this->GetX();
						if(isset($dom[($key - 1)]) AND($dom[($key - 1)]['value'] == ' ')){
							if($this->rtl){
								$xpos += $this->GetStringWidth(' ');
							} else{
								$xpos -= $this->GetStringWidth(' ');
							}
						}
						if(($type == 'eps') OR($type == 'ai')){
							$this->ImageEps($tag['attribute']['src'], $xpos, $this->GetY(), $this->pixelsToUnits($tag['attribute']['width']), $this->pixelsToUnits($tag['attribute']['height']), '', true, $align);
						} else{
							$this->Image($tag['attribute']['src'], $xpos, $this->GetY(), $this->pixelsToUnits($tag['attribute']['width']), $this->pixelsToUnits($tag['attribute']['height']), '', '', $align);
						}
						switch($align){
							case 'T':{
								$this->y = $prevy;
								break;
							}
							case 'M':{
								$this->y =(($this->img_rb_y + $prevy -($tag['fontsize'] / $this->k)) / 2) ;
								break;
							}
							case 'B':{
								$this->y = $this->img_rb_y -($tag['fontsize'] / $this->k);
								break;
							}
						}
					}
					break;
				}
				case 'dl':{
					$this->listnum++;
					$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], false);
					break;
				}
				case 'dt':{
					$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
					break;
				}
				case 'dd':{
					if($this->rtl){
						$this->rMargin += $this->listindent;
					} else{
						$this->lMargin += $this->listindent;
					}
					$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
					break;
				}
				case 'ul':
				case 'ol':{
					$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], false);
					$this->listnum++;
					if($tag['value'] == 'ol'){
						$this->listordered[$this->listnum] = true;
					} else{
						$this->listordered[$this->listnum] = false;
					}
					if(isset($tag['attribute']['start'])){
						$this->listcount[$this->listnum] = intval($tag['attribute']['start']) - 1;
					} else{
						$this->listcount[$this->listnum] = 0;
					}
					if($this->rtl){
						$this->rMargin += $this->listindent;
					} else{
						$this->lMargin += $this->listindent;
					}
					break;
				}
				case 'li':{
					$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
					if($this->listordered[$this->listnum]){
						if(isset($tag['attribute']['value'])){
							$this->listcount[$this->listnum] = intval($tag['attribute']['value']);
						}
						$this->listcount[$this->listnum]++;
						if($this->rtl){
							$this->lispacer = '.'.($this->listcount[$this->listnum]);
						} else{
							$this->lispacer =($this->listcount[$this->listnum]).'.';
						}
					} else{
						$this->lispacer = $this->lisymbol;
					}
					break;
				}
				case 'blockquote':{
					if($this->rtl){
						$this->rMargin += $this->listindent;
					} else{
						$this->lMargin += $this->listindent;
					}
					$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], false);
					break;
				}
				case 'br':{
					$this->Ln('', $cell);
					break;
				}
				case 'div':{
					$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], false);
					break;
				}
				case 'p':{
					$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], false);
					break;
				}
				case 'sup':{
					$this->SetXY($this->GetX(), $this->GetY() -((0.7 * $this->FontSizePt) / $this->k));
					break;
				}
				case 'sub':{
					$this->SetXY($this->GetX(), $this->GetY() +((0.3 * $this->FontSizePt) / $this->k));
					break;
				}
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'h6':{
					$this->addHTMLVertSpace(1, $cell,($tag['fontsize'] * 1.5) / $this->k, $firstorlast, $tag['value'], false);
					break;
				}
				default:{
					break;
				}
			}
		}
		protected function closeHTMLTagHandler(&$dom, $key, $cell=false){
			$tag = $dom[$key];
			$parent = $dom[($dom[$key]['parent'])];
			$firstorlast =((!isset($dom[($key + 1)])) OR((!isset($dom[($key + 2)])) AND($dom[($key + 1)]['value'] == 'marker')));
			switch($tag['value']){
				case 'tr':{
					$table_el = $dom[($dom[$key]['parent'])]['parent'];
					if(!isset($parent['endy'])){
						$dom[($dom[$key]['parent'])]['endy'] = $this->y;
						$parent['endy'] = $this->y;
					}
					if(!isset($parent['endpage'])){
						$dom[($dom[$key]['parent'])]['endpage'] = $this->page;
						$parent['endpage'] = $this->page;
					}
					if(isset($dom[$table_el]['rowspans'])){
						foreach($dom[$table_el]['rowspans'] as $k => $trwsp){
							$dom[$table_el]['rowspans'][$k]['rowspan'] -= 1;
							if($dom[$table_el]['rowspans'][$k]['rowspan'] == 0){
								if($dom[$table_el]['rowspans'][$k]['endpage'] == $parent['endpage']){
									$dom[($dom[$key]['parent'])]['endy'] = max($dom[$table_el]['rowspans'][$k]['endy'], $parent['endy']);
								} elseif($dom[$table_el]['rowspans'][$k]['endpage'] > $parent['endpage']){
									$dom[($dom[$key]['parent'])]['endy'] = $dom[$table_el]['rowspans'][$k]['endy'];
									$dom[($dom[$key]['parent'])]['endpage'] = $dom[$table_el]['rowspans'][$k]['endpage'];
								}
							}
						}
					}
					$this->setPage($parent['endpage']);
					$this->y = $parent['endy'];
					if(isset($dom[$table_el]['attribute']['cellspacing'])){
						$cellspacing = $this->pixelsToUnits($dom[$table_el]['attribute']['cellspacing']);
						$this->y += $cellspacing;
					}
					$this->Ln(0, $cell);
					$this->x = $parent['startx'];
					if($this->page > $parent['startpage']){
						if(($this->rtl) AND($this->pagedim[$this->page]['orm'] != $this->pagedim[$parent['startpage']]['orm'])){
							$this->x +=($this->pagedim[$this->page]['orm'] - $this->pagedim[$parent['startpage']]['orm']);
						} elseif((!$this->rtl) AND($this->pagedim[$this->page]['olm'] != $this->pagedim[$parent['startpage']]['olm'])){
							$this->x +=($this->pagedim[$this->page]['olm'] - $this->pagedim[$parent['startpage']]['olm']);
						}
					}
					break;
				}
				case 'table':{
					$table_el = $parent;
					if((isset($table_el['attribute']['border']) AND($table_el['attribute']['border'] > 0))
						OR(isset($table_el['style']['border']) AND($table_el['style']['border'] > 0))){
							$border = 1;
					} else{
						$border = 0;
					}
					foreach($dom[($dom[$key]['parent'])]['trids'] as $j => $trkey){
						if(isset($dom[($dom[$key]['parent'])]['rowspans'])){
							foreach($dom[($dom[$key]['parent'])]['rowspans'] as $k => $trwsp){
								if($trwsp['trid'] == $trkey){
									$dom[($dom[$key]['parent'])]['rowspans'][$k]['mrowspan'] -= 1;
								}
								if(isset($prevtrkey) AND($trwsp['trid'] == $prevtrkey) AND($trwsp['mrowspan'] >= 0)){
									$dom[($dom[$key]['parent'])]['rowspans'][$k]['trid'] = $trkey;
								}
							}
						}
						if(isset($prevtrkey) AND($dom[$trkey]['startpage'] > $dom[$prevtrkey]['endpage'])){
							$pgendy = $this->pagedim[$dom[$prevtrkey]['endpage']]['hk'] - $this->pagedim[$dom[$prevtrkey]['endpage']]['bm'];
							$dom[$prevtrkey]['endy'] = $pgendy;
							if(isset($dom[($dom[$key]['parent'])]['rowspans'])){
								foreach($dom[($dom[$key]['parent'])]['rowspans'] as $k => $trwsp){
									if(($trwsp['trid'] == $trkey) AND($trwsp['mrowspan'] == 1) AND($trwsp['endpage'] == $dom[$prevtrkey]['endpage'])){
										$dom[($dom[$key]['parent'])]['rowspans'][$k]['endy'] = $pgendy;
										$dom[($dom[$key]['parent'])]['rowspans'][$k]['mrowspan'] = -1;
									}
								}
							}
						}
						$prevtrkey = $trkey;
						$table_el = $dom[($dom[$key]['parent'])];
					}
					foreach($table_el['trids'] as $j => $trkey){
						$parent = $dom[$trkey];
						foreach($parent['cellpos'] as $k => $cellpos){
							if(isset($cellpos['rowspanid']) AND($cellpos['rowspanid'] >= 0)){
								$cellpos['startx'] = $table_el['rowspans'][($cellpos['rowspanid'])]['startx'];
								$cellpos['endx'] = $table_el['rowspans'][($cellpos['rowspanid'])]['endx'];
								$endy = $table_el['rowspans'][($cellpos['rowspanid'])]['endy'];
								$startpage = $table_el['rowspans'][($cellpos['rowspanid'])]['startpage'];
								$endpage = $table_el['rowspans'][($cellpos['rowspanid'])]['endpage'];
							} else{
								$endy = $parent['endy'];
								$startpage = $parent['startpage'];
								$endpage = $parent['endpage'];
							}
							if($endpage > $startpage){
								for($page=$startpage; $page <= $endpage; $page++){
									$this->setPage($page);
									if($page == $startpage){
										$this->y = $parent['starty'];
										$ch = $this->getPageHeight() - $parent['starty'] - $this->getBreakMargin();
										$cborder = $border ? 'LTR' : 0;
									} elseif($page == $endpage){
										$this->y = $this->tMargin;
										$ch = $endy - $this->tMargin;
										$cborder = $border ? 'LRB' : 0;
									} else{
										$this->y = $this->tMargin;
										$ch = $this->getPageHeight() - $this->tMargin - $this->getBreakMargin();
										$cborder = $border ? 'LR' : 0;
									}
									if(isset($cellpos['bgcolor']) AND($cellpos['bgcolor']) !== false){
										$this->SetFillColorArray($cellpos['bgcolor']);
										$fill = true;
									} else{
										$fill = false;
									}
									$cw = abs($cellpos['endx'] - $cellpos['startx']);
									$this->x = $cellpos['startx'];
									if($page > $startpage){
										if(($this->rtl) AND($this->pagedim[$page]['orm'] != $this->pagedim[$startpage]['orm'])){
											$this->x -=($this->pagedim[$page]['orm'] - $this->pagedim[$startpage]['orm']);
										} elseif((!$this->rtl) AND($this->pagedim[$page]['lm'] != $this->pagedim[$startpage]['olm'])){
											$this->x +=($this->pagedim[$page]['olm'] - $this->pagedim[$startpage]['olm']);
										}
									}
									if(!$this->opencell){
										$cborder = $border;
									}
									$ccode = $this->FillColor."\n".$this->getCellCode($cw, $ch, '', $cborder, 1, '', $fill);
									if($cborder OR $fill){
										$pstart = substr($this->pages[$this->page], 0, $this->intmrk[$this->page]);
										$pend = substr($this->pages[$this->page], $this->intmrk[$this->page]);
										$this->pages[$this->page] = $pstart.$ccode."\n".$pend;
										$this->intmrk[$this->page] += strlen($ccode."\n");
									}
								}
							} else{
								$this->setPage($startpage);
								$ch = $endy - $parent['starty'];
								if(isset($cellpos['bgcolor']) AND($cellpos['bgcolor']) !== false){
									$this->SetFillColorArray($cellpos['bgcolor']);
									$fill = true;
								} else{
									$fill = false;
								}
								$cw = abs($cellpos['endx'] - $cellpos['startx']);
								$this->x = $cellpos['startx'];
								$this->y = $parent['starty'];
								$ccode = $this->FillColor."\n".$this->getCellCode($cw, $ch, '', $border, 1, '', $fill);
								if($border OR $fill){
									$pstart = substr($this->pages[$this->page], 0, $this->intmrk[$this->page]);
									$pend = substr($this->pages[$this->page], $this->intmrk[$this->page]);
									$this->pages[$this->page] = $pstart.$ccode."\n".$pend;
									$this->intmrk[$this->page] += strlen($ccode."\n");
								}
							}
						}
						if(isset($table_el['attribute']['cellspacing'])){
							$cellspacing = $this->pixelsToUnits($table_el['attribute']['cellspacing']);
							$this->y += $cellspacing;
						}
						$this->Ln(0, $cell);
						$this->x = $parent['startx'];
						if($endpage > $startpage){
							if(($this->rtl) AND($this->pagedim[$endpage]['orm'] != $this->pagedim[$startpage]['orm'])){
								$this->x +=($this->pagedim[$endpage]['orm'] - $this->pagedim[$startpage]['orm']);
							} elseif((!$this->rtl) AND($this->pagedim[$endpage]['olm'] != $this->pagedim[$startpage]['olm'])){
								$this->x +=($this->pagedim[$endpage]['olm'] - $this->pagedim[$startpage]['olm']);
							}
						}
					}
					if(isset($parent['cellpadding'])){
						$this->cMargin = $this->oldcMargin;
					}
					$this->lasth = $this->FontSize * $this->cell_height_ratio;
					break;
				}
				case 'a':{
					$this->HREF = '';
					break;
				}
				case 'sup':{
					$this->SetXY($this->GetX(), $this->GetY() +((0.7 * $parent['fontsize']) / $this->k));
					break;
				}
				case 'sub':{
					$this->SetXY($this->GetX(), $this->GetY() -((0.3 * $parent['fontsize'])/$this->k));
					break;
				}
				case 'div':{
					$this->addHTMLVertSpace(1, $cell, '', $firstorlast, $tag['value'], true);
					break;
				}
				case 'blockquote':{
					if($this->rtl){
						$this->rMargin -= $this->listindent;
					} else{
						$this->lMargin -= $this->listindent;
					}
					$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
					break;
				}
				case 'p':{
					$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
					break;
				}
				case 'dl':{
					$this->listnum--;
					if($this->listnum <= 0){
						$this->listnum = 0;
						$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
					}
					break;
				}
				case 'dt':{
					$this->lispacer = '';
					$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], true);
					break;
				}
				case 'dd':{
					$this->lispacer = '';
					if($this->rtl){
						$this->rMargin -= $this->listindent;
					} else{
						$this->lMargin -= $this->listindent;
					}
					$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], true);
					break;
				}
				case 'ul':
				case 'ol':{
					$this->listnum--;
					$this->lispacer = '';
					if($this->rtl){
						$this->rMargin -= $this->listindent;
					} else{
						$this->lMargin -= $this->listindent;
					}
					if($this->listnum <= 0){
						$this->listnum = 0;
						$this->addHTMLVertSpace(2, $cell, '', $firstorlast, $tag['value'], true);
					}
					$this->lasth = $this->FontSize * $this->cell_height_ratio;
					break;
				}
				case 'li':{
					$this->lispacer = '';
					$this->addHTMLVertSpace(0, $cell, '', $firstorlast, $tag['value'], true);
					break;
				}
				case 'h1':
				case 'h2':
				case 'h3':
				case 'h4':
				case 'h5':
				case 'h6':{
					$this->addHTMLVertSpace(1, $cell,($parent['fontsize'] * 1.5) / $this->k, $firstorlast, $tag['value'], true);
					break;
				}
				default :{
					break;
				}
			}
			$this->tmprtl = false;
		}
		protected function addHTMLVertSpace($n, $cell=false, $h='', $firstorlast=false, $tag='', $closing=false){
			if($firstorlast){
				$this->Ln(0, $cell);
				$this->htmlvspace = 0;
				return;
			}
			if(isset($this->tagvspaces[$tag][intval($closing)]['n'])){
				$n = $this->tagvspaces[$tag][intval($closing)]['n'];
			}
			if(isset($this->tagvspaces[$tag][intval($closing)]['h'])){
				$h = $this->tagvspaces[$tag][intval($closing)]['h'];
			}
			if(is_string($h)){
				$vsize = $n * $this->lasth;
			} else{
				$vsize = $n * $h;
			}
			if($vsize > $this->htmlvspace){
				$this->Ln(($vsize - $this->htmlvspace), $cell);
				$this->htmlvspace = $vsize;
			}
		}
		public function setLIsymbol($symbol='-'){
			$this->lisymbol = $symbol;
		}
		public function SetBooklet($booklet=true, $inner=-1, $outer=-1){
			$this->booklet = $booklet;
			if($inner >= 0){
				$this->lMargin = $inner;
			}
			if($outer >= 0){
				$this->rMargin = $outer;
			}
		}
		protected function swapMargins($reverse=true){
			if($reverse){
				$mtemp = $this->original_lMargin;
				$this->original_lMargin = $this->original_rMargin;
				$this->original_rMargin = $mtemp;
				$deltam = $this->original_lMargin - $this->original_rMargin;
				$this->lMargin += $deltam;
				$this->rMargin -= $deltam;
			}
		}
		public function setHtmlVSpace($tagvs){
			$this->tagvspaces = $tagvs;
		}
		public function setListIndentWidth($width){
			return $this->customlistindent = floatval($width);
		}
		public function setOpenCell($isopen){
			$this->opencell = $isopen;
		}
		protected function getGraphicVars(){
			$grapvars = array(
				'FontFamily' => $this->FontFamily,
				'FontStyle' => $this->FontStyle,
				'FontSizePt' => $this->FontSizePt,
				'rMargin' => $this->rMargin,
				'lMargin' => $this->lMargin,
				'cMargin' => $this->cMargin,
				'linestyleWidth' => $this->linestyleWidth,
				'linestyleCap' => $this->linestyleCap,
				'linestyleJoin' => $this->linestyleJoin,
				'linestyleDash' => $this->linestyleDash,
				'DrawColor' => $this->DrawColor,
				'FillColor' => $this->FillColor,
				'TextColor' => $this->TextColor,
				'ColorFlag' => $this->ColorFlag,
				'bgcolor' => $this->bgcolor,
				'fgcolor' => $this->fgcolor,
				'htmlvspace' => $this->htmlvspace,
				'lasth' => $this->lasth
				);
			return $grapvars;
		}
		protected function setGraphicVars($gvars){
			$this->FontFamily = $gvars['FontFamily'];
			$this->FontStyle = $gvars['FontStyle'];
			$this->FontSizePt = $gvars['FontSizePt'];
			$this->rMargin = $gvars['rMargin'];
			$this->lMargin = $gvars['lMargin'];
			$this->cMargin = $gvars['cMargin'];
			$this->linestyleWidth = $gvars['linestyleWidth'];
			$this->linestyleCap = $gvars['linestyleCap'];
			$this->linestyleJoin = $gvars['linestyleJoin'];
			$this->linestyleDash = $gvars['linestyleDash'];
			$this->DrawColor = $gvars['DrawColor'];
			$this->FillColor = $gvars['FillColor'];
			$this->TextColor = $gvars['TextColor'];
			$this->ColorFlag = $gvars['ColorFlag'];
			$this->bgcolor = $gvars['bgcolor'];
			$this->fgcolor = $gvars['fgcolor'];
			$this->htmlvspace = $gvars['htmlvspace'];
			$this->_out(''.$this->linestyleWidth.' '.$this->linestyleCap.' '.$this->linestyleJoin.' '.$this->linestyleDash.' '.$this->DrawColor.' '.$this->FillColor.'');
			if(!empty($this->FontFamily)){
				$this->SetFont($this->FontFamily, $this->FontStyle, $this->FontSizePt);
			}
		}
	}
}
?>
