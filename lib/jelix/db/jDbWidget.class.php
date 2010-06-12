<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage db
* @author     Croes Gérald, Laurent Jouanneau
* @contributor Laurent Jouanneau
* @copyright  2001-2005 CopixTeam, 2005-2007 Laurent Jouanneau
*
* This class was get originally from the Copix project (CopixDbWidget, Copix 2.3dev20050901, http://www.copix.org)
* Some lines of code are still copyrighted 2001-2005 CopixTeam (LGPL licence).
* Initial authors of this Copix classes are Gerald Croes and Laurent Jouanneau,
* and this class was adapted/improved for Jelix by Laurent Jouanneau
*
* @link      http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class jDbWidget{
	private $_conn;
	function __construct($connection){
		$this->_conn=$connection;
	}
	public function  fetchFirst($query){
		$rs=$this->_conn->limitQuery($query,0,1);
		$result=$rs->fetch();
		return $result;
	}
	public function fetchFirstInto($query,$classname){
		$rs=$this->_conn->query($query);
		$rs->setFetchMode(8,$classname);
		$result=$rs->fetch();
		return $result;
	}
	public function fetchAll($query,$limitOffset=null,$limitCount=null){
		if($limitOffset===null||$limitCount===null){
			$rs=$this->_conn->query($query);
		}else{
			$rs=$this->_conn->limitQuery($query,$limitOffset,$limitCount);
		}
		return $rs->fetchAll();
	}
	public function fetchAllInto($query,$className,$limitOffset=null,$limitCount=null){
		if($limitOffset===null||$limitCount===null){
			$rs=$this->_conn->query($query);
		}else{
			$rs=$this->_conn->limitQuery($query,$limitOffset,$limitCount);
		}
		$result=array();
		if($rs){
			$rs->setFetchMode(8,$className);
			while($res=$rs->fetch()){
				$result[]=$res;
			}
		}
		return $result;
	}
}
