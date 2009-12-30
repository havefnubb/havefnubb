<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/

/**
 * PHP class to creates array representations for common 1D barcodes to be used with TCPDF.
 * @package com.tecnick.tcpdf
 * @abstract Functions for generating string representation of common 1D barcodes.
 * @author Nicola Asuni
 * @copyright 2008 Nicola Asuni - Tecnick.com S.r.l (www.tecnick.com) Via Della Pace, 11 - 09044 - Quartucciu (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link http://www.tcpdf.org
 * @license http://www.gnu.org/copyleft/lesser.html LGPL
 * @version 1.0.001
 */
class TCPDFBarcode{
	protected $barcode_array;
	public function __construct($code, $type){
		$this->setBarcode($code, $type);
	}
	public function getBarcodeArray(){
		return $this->barcode_array;
	}
	public function setBarcode($code, $type){
		switch(strtoupper($type)){
			case 'C39':{
				$arrcode = $this->barcode_code39($code, false, false);
				break;
			}
			case 'C39+':{
				$arrcode = $this->barcode_code39($code, false, true);
				break;
			}
			case 'C39E':{
				$arrcode = $this->barcode_code39($code, true, false);
				break;
			}
			case 'C39E+':{
				$arrcode = $this->barcode_code39($code, true, true);
				break;
			}
			case 'I25':{
				$arrcode = $this->barcode_i25($code);
				break;
			}
			case 'C128A':{
				$arrcode = $this->barcode_c128($code, 'A');
				break;
			}
			case 'C128B':{
				$arrcode = $this->barcode_c128($code, 'B');
				break;
			}
			case 'C128C':{
				$arrcode = $this->barcode_c128($code, 'C');
				break;
			}
			case 'EAN13':{
				$arrcode = $this->barcode_ean13($code, 13);
				break;
			}
			case 'UPCA':{
				$arrcode = $this->barcode_ean13($code, 12);
				break;
			}
			case 'POSTNET':{
				$arrcode = $this->barcode_postnet($code);
				break;
			}
			case 'CODABAR':{
				$arrcode = $this->barcode_codabar($code);
				break;
			}
			default:{
				$this->barcode_array = false;
			}
		}
		$this->barcode_array = $arrcode;
	}
	protected function barcode_code39($code, $extended=false, $checksum=false){
		$chr['0'] = '111221211';
		$chr['1'] = '211211112';
		$chr['2'] = '112211112';
		$chr['3'] = '212211111';
		$chr['4'] = '111221112';
		$chr['5'] = '211221111';
		$chr['6'] = '112221111';
		$chr['7'] = '111211212';
		$chr['8'] = '211211211';
		$chr['9'] = '112211211';
		$chr['A'] = '211112112';
		$chr['B'] = '112112112';
		$chr['C'] = '212112111';
		$chr['D'] = '111122112';
		$chr['E'] = '211122111';
		$chr['F'] = '112122111';
		$chr['G'] = '111112212';
		$chr['H'] = '211112211';
		$chr['I'] = '112112211';
		$chr['J'] = '111122211';
		$chr['K'] = '211111122';
		$chr['L'] = '112111122';
		$chr['M'] = '212111121';
		$chr['N'] = '111121122';
		$chr['O'] = '211121121';
		$chr['P'] = '112121121';
		$chr['Q'] = '111111222';
		$chr['R'] = '211111221';
		$chr['S'] = '112111221';
		$chr['T'] = '111121221';
		$chr['U'] = '221111112';
		$chr['V'] = '122111112';
		$chr['W'] = '222111111';
		$chr['X'] = '121121112';
		$chr['Y'] = '221121111';
		$chr['Z'] = '122121111';
		$chr['-'] = '121111212';
		$chr['.'] = '221111211';
		$chr[' '] = '122111211';
		$chr['*'] = '121121211';
		$chr['$'] = '121212111';
		$chr['/'] = '121211121';
		$chr['+'] = '121112121';
		$chr['%'] = '111212121';
		$code = strtoupper($code);
		if($extended){
			$code = $this->encode_code39_ext($code);
		}
		if($code === false){
			return false;
		}
		if($checksum){
			$code .= $this->checksum_code39($code);
		}
		$code = '*'.$code.'*';
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		for($i=0; $i < strlen($code); $i++){
			$char = $code{$i};
			if(!isset($chr[$char])){
				return false;
			}
			for($j=0; $j < 9; $j++){
				if(($j % 2) == 0){
					$t = true;
				} else{
					$t = false;
				}
				$w = $chr[$char]{$j};
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				$k++;
			}
			$bararray['bcode'][$k] = array('t' => false, 'w' => 1, 'h' => 1, 'p' => 0);
			$bararray['maxw'] += 1;
			$k++;
		}
		return $bararray;
	}
	protected function encode_code39_ext($code){
		$encode = array(
			chr(0) => '%U', chr(1) => '$A', chr(2) => '$B', chr(3) => '$C',
			chr(4) => '$D', chr(5) => '$E', chr(6) => '$F', chr(7) => '$G',
			chr(8) => '$H', chr(9) => '$I', chr(10) => '$J', chr(11) => '£K',
			chr(12) => '$L', chr(13) => '$M', chr(14) => '$N', chr(15) => '$O',
			chr(16) => '$P', chr(17) => '$Q', chr(18) => '$R', chr(19) => '$S',
			chr(20) => '$T', chr(21) => '$U', chr(22) => '$V', chr(23) => '$W',
			chr(24) => '$X', chr(25) => '$Y', chr(26) => '$Z', chr(27) => '%A',
			chr(28) => '%B', chr(29) => '%C', chr(30) => '%D', chr(31) => '%E',
			chr(32) => ' ', chr(33) => '/A', chr(34) => '/B', chr(35) => '/C',
			chr(36) => '/D', chr(37) => '/E', chr(38) => '/F', chr(39) => '/G',
			chr(40) => '/H', chr(41) => '/I', chr(42) => '/J', chr(43) => '/K',
			chr(44) => '/L', chr(45) => '-', chr(46) => '.', chr(47) => '/O',
			chr(48) => '0', chr(49) => '1', chr(50) => '2', chr(51) => '3',
			chr(52) => '4', chr(53) => '5', chr(54) => '6', chr(55) => '7',
			chr(56) => '8', chr(57) => '9', chr(58) => '/Z', chr(59) => '%F',
			chr(60) => '%G', chr(61) => '%H', chr(62) => '%I', chr(63) => '%J',
			chr(64) => '%V', chr(65) => 'A', chr(66) => 'B', chr(67) => 'C',
			chr(68) => 'D', chr(69) => 'E', chr(70) => 'F', chr(71) => 'G',
			chr(72) => 'H', chr(73) => 'I', chr(74) => 'J', chr(75) => 'K',
			chr(76) => 'L', chr(77) => 'M', chr(78) => 'N', chr(79) => 'O',
			chr(80) => 'P', chr(81) => 'Q', chr(82) => 'R', chr(83) => 'S',
			chr(84) => 'T', chr(85) => 'U', chr(86) => 'V', chr(87) => 'W',
			chr(88) => 'X', chr(89) => 'Y', chr(90) => 'Z', chr(91) => '%K',
			chr(92) => '%L', chr(93) => '%M', chr(94) => '%N', chr(95) => '%O',
			chr(96) => '%W', chr(97) => '+A', chr(98) => '+B', chr(99) => '+C',
			chr(100) => '+D', chr(101) => '+E', chr(102) => '+F', chr(103) => '+G',
			chr(104) => '+H', chr(105) => '+I', chr(106) => '+J', chr(107) => '+K',
			chr(108) => '+L', chr(109) => '+M', chr(110) => '+N', chr(111) => '+O',
			chr(112) => '+P', chr(113) => '+Q', chr(114) => '+R', chr(115) => '+S',
			chr(116) => '+T', chr(117) => '+U', chr(118) => '+V', chr(119) => '+W',
			chr(120) => '+X', chr(121) => '+Y', chr(122) => '+Z', chr(123) => '%P',
			chr(124) => '%Q', chr(125) => '%R', chr(126) => '%S', chr(127) => '%T');
		$code_ext = '';
		for($i = 0 ; $i < strlen($code); $i++){
			if(ord($code{$i}) > 127){
				return false;
			}
			$code_ext .= $encode[$code{$i}];
		}
		return $code_ext;
	}
	protected function checksum_code39($code){
		$chars = array(
			'0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
			'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
			'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
			'W', 'X', 'Y', 'Z', '-', '.', ' ', '$', '/', '+', '%');
		$sum = 0;
		for($i=0 ; $i < strlen($code); $i++){
			$k = array_keys($chars, $code{$i});
			$sum += $k[0];
		}
		$j =($sum % 43);
		return $chars[$j];
	}
	protected function barcode_i25($code){
		$chr['0'] = '11221';
		$chr['1'] = '21112';
		$chr['2'] = '12112';
		$chr['3'] = '22111';
		$chr['4'] = '11212';
		$chr['5'] = '21211';
		$chr['6'] = '12211';
		$chr['7'] = '11122';
		$chr['8'] = '21121';
		$chr['9'] = '12121';
		$chr['A'] = '11';
		$chr['Z'] = '21';
		if((strlen($code) % 2) != 0){
			$code = '0'.$code;
		}
		$code = 'AA'.strtolower($code).'ZA';
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		for($i=0; $i < strlen($code); $i=$i+2){
			$char_bar = $code{$i};
			$char_space = $code{$i+1};
			if((!isset($chr[$char_bar])) OR(!isset($chr[$char_space]))){
				return false;
			}
			$seq = '';
			for($s=0; $s < strlen($chr[$char_bar]); $s++){
				$seq .= $chr[$char_bar]{$s} . $chr[$char_space]{$s};
			}
			for($j=0; $j < strlen($seq); $j++){
				if(($j % 2) == 0){
					$t = true;
				} else{
					$t = false;
				}
				$w = $seq{$j};
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				$k++;
			}
		}
		return $bararray;
	}
	protected function barcode_c128($code, $type='B'){
		$chr = array(
			'212222',
			'222122',
			'222221',
			'121223',
			'121322',
			'131222',
			'122213',
			'122312',
			'132212',
			'221213',
			'221312',
			'231212',
			'112232',
			'122132',
			'122231',
			'113222',
			'123122',
			'123221',
			'223211',
			'221132',
			'221231',
			'213212',
			'223112',
			'312131',
			'311222',
			'321122',
			'321221',
			'312212',
			'322112',
			'322211',
			'212123',
			'212321',
			'232121',
			'111323',
			'131123',
			'131321',
			'112313',
			'132113',
			'132311',
			'211313',
			'231113',
			'231311',
			'112133',
			'112331',
			'132131',
			'113123',
			'113321',
			'133121',
			'313121',
			'211331',
			'231131',
			'213113',
			'213311',
			'213131',
			'311123',
			'311321',
			'331121',
			'312113',
			'312311',
			'332111',
			'314111',
			'221411',
			'431111',
			'111224',
			'111422',
			'121124',
			'121421',
			'141122',
			'141221',
			'112214',
			'112412',
			'122114',
			'122411',
			'142112',
			'142211',
			'241211',
			'221114',
			'413111',
			'241112',
			'134111',
			'111242',
			'121142',
			'121241',
			'114212',
			'124112',
			'124211',
			'411212',
			'421112',
			'421211',
			'212141',
			'214121',
			'412121',
			'111143',
			'111341',
			'131141',
			'114113',
			'114311',
			'411113',
			'411311',
			'113141',
			'114131',
			'311141',
			'411131',
			'211412',
			'211214',
			'211232',
			'233111',
			'200000'
		);
		$keys = '';
		switch(strtoupper($type)){
			case 'A':{
				$startid = 103;
				$keys = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_';
				for($i = 0; $i < 32; $i++){
					$keys .= chr($i);
				}
				break;
			}
			case 'B':{
				$startid = 104;
				$keys = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~'.chr(127);
				break;
			}
			case 'C':{
				$startid = 105;
				$keys = '';
				if((strlen($code) % 2) != 0){
					return false;
				}
				for($i = 0; $i <= 99; $i++){
					$keys .= chr($i);
				}
				$new_code = '';
				for($i=0; $i <(strlen($code) / 2); $i++){
					$new_code .= chr(intval($code{(2 * $i)}.$code{(2 * $i + 1)}));
				}
				$code = $new_code;
				break;
			}
			default:{
				return false;
			}
		}
		$sum = $startid;
		for($i=0; $i < strlen($code); $i++){
			$sum +=(strpos($keys, $code{$i}) *($i+1));
		}
		$check =($sum % 103);
		$code = chr($startid).$code.chr($check).chr(106).chr(107);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$len = strlen($code);
		for($i=0; $i < $len; $i++){
			$ck = strpos($keys, $code{$i});
			if(($i == 0) OR($i >($len-4))){
				$seq = $chr[ord($code{$i})];
			} elseif(($ck >= 0) AND isset($chr[$ck])){
					$seq = $chr[$ck];
			} else{
				return false;
			}
			for($j=0; $j < 6; $j++){
				if(($j % 2) == 0){
					$t = true;
				} else{
					$t = false;
				}
				$w = $seq{$j};
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				$k++;
			}
		}
		return $bararray;
	}
	protected function barcode_ean13($code, $len=13){
		$code = str_pad($code, $len-1, '0', STR_PAD_LEFT);
		if($len == 12){
			$code = '0'.$code;
		}
		if(strlen($code) == 12){
			$sum=0;
			for($i=1;$i<=11;$i+=2){
				$sum +=(3 * $code{$i});
			}
			for($i=0; $i <= 10; $i+=2){
				$sum +=($code{$i});
			}
			$r = $sum % 10;
			if($r > 0){
				$r =(10 - $r);
			}
			$code .= $r;
		} else{
			$sum = 0;
			for($i=1; $i <= 11; $i+=2){
				$sum +=(3 * $code{$i});
			}
			for($i=0; $i <= 10; $i+=2){
				$sum += $code{$i};
			}
			if((($sum + $code{12}) % 10) != 0){
				return false;
			}
		}
		$codes = array(
			'A'=>array(
				'0'=>'0001101',
				'1'=>'0011001',
				'2'=>'0010011',
				'3'=>'0111101',
				'4'=>'0100011',
				'5'=>'0110001',
				'6'=>'0101111',
				'7'=>'0111011',
				'8'=>'0110111',
				'9'=>'0001011'),
			'B'=>array(
				'0'=>'0100111',
				'1'=>'0110011',
				'2'=>'0011011',
				'3'=>'0100001',
				'4'=>'0011101',
				'5'=>'0111001',
				'6'=>'0000101',
				'7'=>'0010001',
				'8'=>'0001001',
				'9'=>'0010111'),
			'C'=>array(
				'0'=>'1110010',
				'1'=>'1100110',
				'2'=>'1101100',
				'3'=>'1000010',
				'4'=>'1011100',
				'5'=>'1001110',
				'6'=>'1010000',
				'7'=>'1000100',
				'8'=>'1001000',
				'9'=>'1110100')
		);
		$parities = array(
			'0'=>array('A','A','A','A','A','A'),
			'1'=>array('A','A','B','A','B','B'),
			'2'=>array('A','A','B','B','A','B'),
			'3'=>array('A','A','B','B','B','A'),
			'4'=>array('A','B','A','A','B','B'),
			'5'=>array('A','B','B','A','A','B'),
			'6'=>array('A','B','B','B','A','A'),
			'7'=>array('A','B','A','B','A','B'),
			'8'=>array('A','B','A','B','B','A'),
			'9'=>array('A','B','B','A','B','A')
		);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$seq = '101';
		$p = $parities[$code{0}];
		for($i=1; $i < 7; $i++){
			$seq .= $codes[$p[$i-1]][$code{$i}];
		}
		$seq .= '01010';
		for($i=7; $i < 13; $i++){
			$seq .= $codes['C'][$code{$i}];
		}
		$seq .= '101';
		$len = strlen($seq);
		$w = 0;
		for($i=0; $i < $len; $i++){
			$w += 1;
			if(($i ==($len - 1)) OR(($i <($len - 1)) AND($seq{$i} != $seq{($i+1)}))){
				if($seq{$i} == '1'){
					$t = true;
				} else{
					$t = false;
				}
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				$k++;
				$w = 0;
			}
		}
		return $bararray;
	}
	protected function barcode_postnet($code){
		$barlen = Array(
			0 => Array(2,2,1,1,1),
			1 => Array(1,1,1,2,2),
			2 => Array(1,1,2,1,2),
			3 => Array(1,1,2,2,1),
			4 => Array(1,2,1,1,2),
			5 => Array(1,2,1,2,1),
			6 => Array(1,2,2,1,1),
			7 => Array(2,1,1,1,2),
			8 => Array(2,1,1,2,1),
			9 => Array(2,1,2,1,1)
		);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
		$k = 0;
		$code = str_replace('-', '', $code);
		$code = str_replace(' ', '', $code);
		$len = strlen($code);
		$sum = 0;
		for($i=0; $i < $len; $i++){
			$sum += intval($code{$i});
		}
		if(($sum % 10) == 0){
			return false;
		}
		$code .= ''.(10 -($sum % 10)).'';
		$len = strlen($code);
		$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
		$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
		$bararray['maxw'] += 2;
		for($i=0; $i < $len; $i++){
			for($j=0; $j < 5; $j++){
				$h = $barlen[$code{$i}][$j];
				$p = floor(1 / $h);
				$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
				$bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
				$bararray['maxw'] += 2;
			}
		}
		$bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
		$bararray['maxw'] += 1;
		return $bararray;
	}
	protected function barcode_codabar($code){
		$chr = array(
			'0' => '11111221',
			'1' => '11112211',
			'2' => '11121121',
			'3' => '22111111',
			'4' => '11211211',
			'5' => '21111211',
			'6' => '12111121',
			'7' => '12112111',
			'8' => '12211111',
			'9' => '21121111',
			'-' => '11122111',
			'$' => '11221111',
			':' => '21112121',
			'/' => '21211121',
			'.' => '21212111',
			'+' => '11222221',
			'A' => '11221211',
			'B' => '12121121',
			'C' => '11121221',
			'D' => '11122211'
		);
		$bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
		$k = 0;
		$w = 0;
		$seq = '';
		$code = 'A'.strtoupper($code).'A';
		$len = strlen($code);
		for($i=0; $i < $len; $i++){
			if(!isset($chr[$code{$i}])){
				return false;
			}
			$seq = $chr[$code{$i}];
			for($j=0; $j < 8; $j++){
				if(($j % 2) == 0){
					$t = true;
				} else{
					$t = false;
				}
				$w = $seq{$j};
				$bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
				$bararray['maxw'] += $w;
				$k++;
			}
		}
		return $bararray;
	}
}
?>
