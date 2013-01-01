<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
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
require_once(JELIX_LIB_PATH.'utils/jXMLFeedItem.class.php');
class jAtom10Item extends jXMLFeedItem{
	public $authorUri;
	public $otherAuthors=array();
	public $contributors=array();
	public $otherLinks=array();
	public $summary;
	public $summaryType;
	public $source;
	public $copyright;
	public $updated;
	public function setFromXML(SimpleXMLElement $item){
		$dt=new jDateTime();
		$this->authorEmail=(string)$item->author->email;
		$this->authorName=(string)$item->author->name;
		foreach($item->category as $cat){
			if($cat['term']!=null)
				$this->categories[]=(string)$cat['term'];
		}
		$this->content=(string)$item->content;
		if($item->content['type'])
			$this->contentType=(string)$item->content['type'];
		$this->source=(string)$item->source;
		$this->id=(string)$item->id;
		if((string)$item->published!=''){
			$dt->setFromString((string)$item->published,jDateTime::ISO8601_FORMAT);
			$this->published=$dt->toString(jDateTime::DB_DTFORMAT);
		}
		$this->title=(string)$item->title;
		$this->authorUri=(string)$item->author->uri;
		$this->copyright=(string)$item->rights;
		foreach($item->contributor as $contrib){
			$this->contributors[]=array('name'=>(string)$contrib->name,'email'=>(string)$contrib->email,'uri'=>(string)$contrib->uri);
		}
		$i=0;
		foreach($item->author as $author){
			if($i==0){
				$this->authorEmail=(string)$author->email;
				$this->authorName=(string)$author->name;
				$this->authorUri=(string)$author->uri;
			}
			else{
				$this->otherAuthors[]=array('name'=>(string)$author->name,'email'=>(string)$author->email,'uri'=>(string)$author->uri);
			}
			$i++;
		}
		$attrs_links=array('href','rel','type','hreflang','title','length');
		foreach($item->link as $l){
				if(($l['rel']=='alternate'||$l['rel']==null)&&$l['href']!=null)
					$this->link=(string)$l['href'];
				else{
					$link=array();
					foreach($attrs_links as $a){
						if($l[$a]!=null)
							$link[$a]=(string)$l[$a];
					}
					$this->otherLinks[]=$link;
				}
		}
		$this->summary=(string)$item->summary;
		if($feed->summary['type'])
			$this->summaryType=(string)$feed->summary['type'];
		if((string)$item->updated!=''){
			$dt->setFromString((string)$item->updated,jDateTime::ISO8601_FORMAT);
			$this->updated=$dt->toString(jDateTime::DB_DTFORMAT);
		}
	}
}
