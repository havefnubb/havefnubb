<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage feeds
* @author     Yannick Le Guédart
* @contributor Laurent Jouanneau
* @copyright  2006 Yannick Le Guédart
* @copyright  2006-2009 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */
abstract class jXMLFeedItem{
	public $id;
	public $title;
	public $link;
	public $published;
	public $authorName;
	public $authorEmail;
	public $categories=array();
	public $content;
	public $contentType='text';
	protected $_mandatory=array();
	public abstract function  setFromXML(SimpleXMLElement $xml_element);
}
