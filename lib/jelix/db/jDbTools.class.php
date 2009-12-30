<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Croes Gérald, Laurent Jouanneau
* @contributor Laurent Jouanneau, Gwendal Jouannic, Julien Issler
* @copyright  2001-2005 CopixTeam, 2005-2006 Laurent Jouanneau
* @copyright  2008 Gwendal Jouannic
* @copyright  2008 Julien Issler
*
* This class was get originally from the Copix project (CopixDbTools, CopixDbConnection, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this class was adapted/improved for Jelix by Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
 class jDbFieldProperties{
	public $type;
	public $name;
	public $notNull=true;
	public $primary=false;
	public $autoIncrement=false;
	public $default='';
	public $hasDefault = false;
	public $length = 0;
	public $sequence = false;
}
abstract class jDbTools{
	protected $_connector;
	function __construct( $connector){
		$this->_connector = $connector;
	}
	public function getTableList(){
		return $this->_getTableList();
	}
	public function getFieldList($tableName){
		return $this->_getFieldList($this->_connector->prefixTable($tableName));
	}
	abstract protected function _getTableList();
	abstract protected function _getFieldList($tableName);
	protected $dbmsStyle = array('/^\s*#/', '/;\s*$/');
	public function execSQLScript($file){
		$lines = file($file);
		$cmdSQL = '';
		$nbCmd = 0;
		 $style=$this->dbmsStyle;
		foreach((array)$lines as $key=>$line){
			if((!preg_match($style[0],$line))&&(strlen(trim($line))>0)){
				$cmdSQL.=$line;
				if(preg_match($style[1],$line)){
					$cmdSQL = preg_replace($style[1],'',$cmdSQL);
					$this->_connector->query($cmdSQL);
					$nbCmd++;
					$cmdSQL = '';
				}
			}
		}
		return $nbCmd;
   }
}