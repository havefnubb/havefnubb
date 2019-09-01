<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage dao
* @author      Laurent Jouanneau
* @copyright   2005-2012 Laurent Jouanneau
* Idea of this class was get originally from the Copix project
* (CopixDaoCompiler, Copix 2.3dev20050901, http://www.copix.org)
* no more line of code are copyrighted by CopixTeam
*
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once(JELIX_LIB_PATH.'dao/jDaoParser.class.php');
class jDaoCompiler  implements jISimpleCompiler{
	public function compile($selector){
		$daoPath=$selector->getPath();
		$doc=new DOMDocument();
		if(!$doc->load($daoPath)){
			throw new jException('jelix~daoxml.file.unknown',$daoPath);
		}
		if($doc->documentElement->namespaceURI!=JELIX_NAMESPACE_BASE.'dao/1.0'){
			throw new jException('jelix~daoxml.namespace.wrong',array($daoPath,$doc->namespaceURI));
		}
		$tools=jApp::loadPlugin($selector->driver,'db','.dbtools.php',$selector->driver.'DbTools');
		if(is_null($tools))
			throw new jException('jelix~db.error.driver.notfound',$selector->driver);
		$parser=new jDaoParser($selector);
		$parser->parse(simplexml_import_dom($doc),$tools);
		require_once(jApp::config()->_pluginsPathList_db[$selector->driver].$selector->driver.'.daobuilder.php');
		$class=$selector->driver.'DaoBuilder';
		$generator=new $class($selector,$tools,$parser);
		$compiled='<?php ';
		$compiled.="\nif (jApp::config()->compilation['checkCacheFiletime']&&(\n";
		$compiled.="\n filemtime('".$daoPath.'\') > '.filemtime($daoPath);
		$importedDao=$parser->getImportedDao();
		if($importedDao){
			foreach($importedDao as $selimpdao){
				$path=$selimpdao->getPath();
				$compiled.="\n|| filemtime('".$path.'\') > '.filemtime($path);
			}
		}
		$compiled.=")){ return false;\n}\nelse {\n";
		$compiled.=$generator->buildClasses()."\n return true; }";
		jFile::write($selector->getCompiledFilePath(),$compiled);
		return true;
	}
}
