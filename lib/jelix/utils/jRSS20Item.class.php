<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  utils
* @author      Loic Mathaud
* @author      Yannick Le Guédart
* @contributor Laurent Jouanneau
* @contributor  Sebastien Romieu
* @contributor  Florian Lonqueu-Brochard
* @copyright   2005-2006 Loic Mathaud
* @copyright   2006 Yannick Le Guédart
* @copyright   2006-2010 Laurent Jouanneau
* @copyright   2010 Sébastien Romieu
* @copyright   2012 Florian Lonqueu-Brochard
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'utils/jXMLFeedItem.class.php');
class jRSS20Item extends jXMLFeedItem{
	public $comments;
	public $enclosure;
	public $idIsPermalink;
	public $sourceUrl;
	public $sourceTitle;
	public function setFromXML(SimpleXMLElement $item){
		$dt=new jDateTime();
		$resultat=explode(" ",(string)$item->author);
		foreach($resultat as $mot){
			if(jFilter::isEmail($mot))
					$this->authorEmail=$mot;
			else
					$this->authorName.=' '.$mot;
		}
		$categorie=$item->category;
		foreach($categorie as $cat){
			$this->categories[]=(string)$cat;
		}
		$this->content=(string)$item->description;
		$this->id=(string)$item->guid;
		$this->link=(string)$item->link;
		if((string)$item->pubDate!=''){
			$dt->setFromString((string)$item->pubDate,jDateTime::RFC2822_FORMAT);
			$this->published=$dt->toString(jDateTime::DB_DTFORMAT);
		}
		$this->title=(string)$item->title;
		$this->idIsPermalink=(isset($item->guid['isPermaLink'])&&$item->guid['isPermaLink']=='true')? true : false;
		$this->sourceTitle=(string)$item->source;
		$this->sourceUrl=isset($item->source['url'])? (string)$item->source['url'] : '';
		$this->comments=(string)$item->comments;
		if(isset($item->enclosure['url'])){
			$this->enclosure=array();
			$attrs=array('url','length','type');
			foreach($attrs as $a){
				$this->enclosure[$a]=isset($item->enclosure[$a])? (string)$item->enclosure[$a] : '';
			}
		}
	}
}
