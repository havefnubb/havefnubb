<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author Laurent Jouanneau
* @contributor Christophe Thiriot
* @contributor Bastien Jaillot
* @contributor Loic Mathaud
* @contributor Olivier Demah (#733)
* @contributor Cedric (fix bug ticket 56)
* @contributor Julien Issler
* @copyright   2005-2010 Laurent Jouanneau, 2006 Christophe Thiriot, 2006 Loic Mathaud, 2008 Bastien Jaillot, 2008 Olivier Demah, 2009-2010 Julien Issler
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jFile{
	public static function read($filename){
		return @file_get_contents($filename,false);
	}
	public static function write($file,$data){
		$_dirname=dirname($file);
		self::createDir($_dirname);
		if(!@is_writable($_dirname)){
			if(!@is_dir($_dirname)){
				throw new jException('jelix~errors.file.directory.notexists',array($_dirname));
			}
			throw new jException('jelix~errors.file.directory.notwritable',array($file,$_dirname));
		}
		$_tmp_file=tempnam($_dirname,'wrt');
		if(!($fd=@fopen($_tmp_file,'wb'))){
			$_tmp_file=$_dirname . '/' . uniqid('wrt');
			if(!($fd=@fopen($_tmp_file,'wb'))){
				throw new jException('jelix~errors.file.write.error',array($file,$_tmp_file));
			}
		}
		fwrite($fd,$data);
		fclose($fd);
		if($GLOBALS['gJConfig']->isWindows&&file_exists($file)){
			unlink($file);
		}
		rename($_tmp_file,$file);
		@chmod($file,0664);
		return true;
	}
	public static function createDir($dir){
		if(!file_exists($dir)){
			self::createDir(dirname($dir));
			mkdir($dir,0775);
		}
	}
	public static function removeDir($path,$deleteParent=true){
		if($path==''||$path=='/'||$path==DIRECTORY_SEPARATOR)
			throw new jException('jelix~errors.file.directory.cannot.remove.fs.root');
		$dir=new DirectoryIterator($path);
		foreach($dir as $dirContent){
			if($dirContent->isFile()||$dirContent->isLink()){
				unlink($dirContent->getPathName());
			}else{
				if(!$dirContent->isDot()&&$dirContent->isDir()){
					self::removeDir($dirContent->getPathName());
				}
			}
		}
		unset($dir);
		unset($dirContent);
		if($deleteParent){
			rmdir($path);
		}
	}
	public static function getMimeType($file){
		if(function_exists('finfo_open')){
			$finfo=finfo_open(FILEINFO_MIME_TYPE);
			$type=finfo_file($finfo,$file);
			finfo_close($finfo);
			return $type;
		}
		else
			return mime_content_type($file);
	}
}
