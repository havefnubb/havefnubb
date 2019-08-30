<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  feeds
* @author      Yannick Le Guédart
* @contributor Laurent Jouanneau
* @contributor  Sebastien Romieu
* @contributor  Florian Lonqueu-Brochard
* @copyright   2006 Yannick Le Guédart
* @copyright   2006-2010 Laurent Jouanneau
* @copyright   2010 Sébastien Romieu
* @copyright   2012 Florian Lonqueu-Brochard
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'utils/jXMLFeedInfo.class.php');
class jAtom10Info extends jXMLFeedInfo{
	public $id;
	public $selfLink;
	public $authors=array();
	public $otherLinks=array();
	public $contributors=array();
	public $icon;
	public $generatorVersion;
	public $generatorUrl;
	function __construct()
	{
		$this->_mandatory=array('title','id','updated');
	}
	public function setFromXML(SimpleXMLElement $feed){
		$dt=new jDateTime();
		foreach($feed->category as $cat){
			if($cat['term']!=null)
				$this->categories[]=(string)$cat['term'];
		}
		$this->description=(string)$feed->subtitle;
		if($feed->subtitle['type'])
			$this->descriptionType=(string)$feed->subtitle['type'];
		$this->generator=(string)$feed->generator;
		$this->image=(string)$feed->logo;
		$this->title=(string)$feed->title;
		$this->copyright=(string)$feed->rights;
		if((string)$feed->updated!=''){
			$dt->setFromString((string)$feed->updated,jDateTime::ISO8601_FORMAT);
			$this->updated=$dt->toString(jDateTime::DB_DTFORMAT);
		}
		$attrs_links=array('href','rel','type','hreflang','title','length');
		foreach($feed->link as $l){
				if(($l['rel']=='alternate'||$l['rel']==null)&&$l['href']!=null)
					$this->webSiteUrl=(string)$l['href'];
				else if($l['rel']=='self'&&$l['href']!=null)
					$this->selfLink=(string)$l['href'];
				else{
					$link=array();
					foreach($attrs_links as $a){
						if($l[$a]!=null)
							$link[$a]=(string)$l[$a];
					}
					$this->otherLinks[]=$link;
				}
		}
		foreach($feed->author as $author){
			$this->authors[]=array('name'=>(string)$author->name,'email'=>(string)$author->email,'uri'=>(string)$author->uri);
		}
		foreach($feed->contributor as $contrib){
			$this->contributors[]=array('name'=>(string)$contrib->name,'email'=>(string)$contrib->email,'uri'=>(string)$contrib->uri);
		}
		$this->generatorUrl=(string)$feed->generator['url'];
		$this->generatorVersion=(string)$feed->generator['version'];
		$this->icon=(string)$feed->icon;
		$this->id=(string)$feed->id;
	}
}
