<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package    jelix
 * @subpackage utils
 * @author     Laurent Jouanneau
 * @contributor Julien Issler
 * @copyright  2006 Laurent Jouanneau
 * @copyright 2008 Julien Issler
 * @link       http://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
class jZipCreator{
	protected $fileRecords = array();
	protected $centralDirectory = array();
	protected $centralDirOffset   = 0;
	public function addFile($filename, $zipFileName=''){
		if($zipFileName == '') $zipFileName = $filename;
		if(file_exists($filename)){
			$this->addContentFile($zipFileName, file_get_contents($filename), filemtime($filename));
		}else{
			throw new jException('jelix~errors.file.notexists', $filename);
		}
	}
	public function addDir($path, $zipDirPath='', $recursive = false){
		if(file_exists($path)){
			if($zipDirPath !='' && substr($zipDirPath,-1,1) != '/')
				$zipDirPath.='/';
			if(substr($path,-1,1) != '/')
				$path.='/';
			if($handle = opendir($path)){
				$this->addEmptyDir($zipDirPath,filemtime($path));
				while(($file = readdir($handle)) !== false){
					if($file == '.' || $file == '..')
						continue;
					if(!is_dir($path.$file))
						$this->addFile($path.$file, $zipDirPath.$file);
					else if($recursive)
						$this->addDir($path.$file,$zipDirPath.$file, true);
				}
				closedir($handle);
			}
		}else{
			throw new jException('jelix~errors.file.notexists', $path);
		}
	}
	public function addContentFile($zipFileName, $content, $filetime = 0){
		$filetime = $this->_getDOSTimeFormat($filetime);
		$zipFileName	 = str_replace('\\', '/', $zipFileName);
		$zippedcontent	= substr(gzcompress($content), 2, -4);
		$fileinfo  = $filetime.pack('V', crc32($content));
		$fileinfo .= pack('V', strlen($zippedcontent)). pack('V', strlen($content));
		$fileinfo .= pack('v', strlen($zipFileName))."\x00\x00";
		$filerecord = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00".$fileinfo.
			$zipFileName.$zippedcontent;
		$this->fileRecords[] = $filerecord;
		$this->_addCentralDirEntry($zipFileName, $fileinfo);
		$this->centralDirOffset += strlen($filerecord);
	}
	public function addEmptyDir($name, $time=0){
		$time = $this->_getDOSTimeFormat($time);
		$name = str_replace('\\', '/', $name);
		if(substr($name,-1,1)!=='/')
			$name .= '/';
		if($name == '/')
			return;
		$fileinfo = $time."\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00".
			pack('v', strlen($name))."\x00\x00";
		$filerecord = "\x50\x4b\x03\x04\x14\x00\x00\x00\x08\x00".$fileinfo.$name;
		$this->fileRecords[] = $filerecord;
		$this->_addCentralDirEntry($name, $fileinfo, true);
		$this->centralDirOffset += strlen($filerecord);
	}
	public function getContent(){
		$centraldir = implode('', $this->centralDirectory);
		$c = pack('v', count($this ->centralDirectory));
		return implode('', $this->fileRecords).$centraldir."\x50\x4b\x05\x06\x00\x00\x00\x00".$c.$c.
			pack('V', strlen($centraldir)).pack('V', $this ->centralDirOffset)."\x00\x00";
	}
	protected function _getDOSTimeFormat($timestamp){
		if($timestamp == 0)
			$timestamp = time();
		elseif($timestamp < 315529200)
			$timestamp = 315529200;
		$dt = getdate($timestamp);
		return pack('V',($dt['seconds'] >> 1) |($dt['minutes'] << 5) |($dt['hours'] << 11) |
				($dt['mday'] << 16) |($dt['mon'] << 21) |(($dt['year'] - 1980) << 25));
	}
	protected function _addCentralDirEntry($name, $info, $isDir = false){
		$cdrecord = "\x50\x4b\x01\x02\x00\x00\x14\x00\x00\x00\x08\x00".$info;
		$cdrecord .= "\x00\x00\x00\x00\x00\x00";
		if($isDir)
			$cdrecord .= pack('V', 16);
		else
			$cdrecord .= pack('V', 32);
		$cdrecord .= pack('V', $this ->centralDirOffset);
		$cdrecord .= $name;
		$this->centralDirectory[] = $cdrecord;
	}
}