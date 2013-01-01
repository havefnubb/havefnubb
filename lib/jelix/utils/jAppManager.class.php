<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage utils
* @author     Loic Mathaud
* @contributor Laurent Jouanneau
* @copyright  2006 Loic Mathaud, 2010-2011 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jAppManager{
	private function __construct(){}
	public static function close($message=''){
		file_put_contents(jApp::configPath('CLOSED'),$message);
	}
	public static function open(){
		if(file_exists(jApp::configPath('CLOSED')))
			unlink(jApp::configPath('CLOSED'));
	}
	public static function isOpened(){
		return !file_exists(jApp::configPath('CLOSED'));
	}
	public static function clearTemp($path=''){
		if($path==''){
			$path=jApp::tempBasePath();
			if($path==''){
				throw new Exception("default temp base path is not defined",1);
			}
		}
		if($path==DIRECTORY_SEPARATOR||$path==''||$path=='/'){
			throw new Exception('given temp path is invalid',2);
		}
		if(!file_exists($path))
			throw new Exception('given temp path does not exists',3);
		if(!is_writeable($path))
			throw new Exception('given temp path does not exists',4);
		jFile::removeDir($path,false);
	}
}
