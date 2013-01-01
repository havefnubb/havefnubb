<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  core_response
* @author      Baptiste Toinot
* @contributor Laurent Jouanneau
* @copyright   2008 Baptiste Toinot, 2011-2012 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
require_once(JELIX_LIB_PATH.'tpl/jTpl.class.php');
class jResponseSitemap extends jResponse{
	protected $_type='sitemap';
	protected $allowedChangefreq=array('always','hourly','daily','weekly',
										'monthly','yearly','never');
	protected $maxSitemap=1000;
	protected $maxUrl=50000;
	protected $urlSitemap=array();
	protected $urlList=array();
	public $content;
	public $contentTpl;
	public function __construct(){
		$this->content=new jTpl();
		$this->contentTpl='jelix~sitemap';
		parent::__construct();
	}
	final public function output(){
		if($this->_outputOnlyHeaders){
			$this->sendHttpHeaders();
			return true;
		}
		$this->_httpHeaders['Content-Type']='application/xml;charset=UTF-8';
		if(count($this->urlSitemap)){
			$head='<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
			$foot='</sitemapindex>';
			$this->contentTpl='jelix~sitemapindex';
			$this->content->assign('sitemaps',$this->urlSitemap);
		}else{
			$head='<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
			$foot='</urlset>';
			$this->content->assign('urls',$this->urlList);
		}
		$content=$this->content->fetch($this->contentTpl);
		$this->sendHttpHeaders();
		echo '<?xml version="1.0" encoding="UTF-8"?>',"\n";
		echo $head,$content,$foot;
		return true;
	}
	public function addUrl($loc,$lastmod=null,$changefreq=null,$priority=null){
		if(isset($loc[2048])||count($this->urlList)>=$this->maxUrl){
			return false;
		}
		$url=new jSitemapUrl();
		$url->loc=jApp::coord()->request->getServerURI(). $loc;
		if(($timestamp=strtotime($lastmod))){
			$url->lastmod=date('c',$timestamp);
		}
		if($changefreq&&in_array($changefreq,$this->allowedChangefreq)){
			$url->changefreq=$changefreq;
		}
		if($priority&&is_numeric($priority)&&$priority>=0&&$priority<=1){
			$url->priority=sprintf('%0.1f',$priority);
		}
		$this->urlList[]=$url;
	}
	public function addSitemap($loc,$lastmod=null){
		if(isset($loc[2048])||count($this->urlSitemap)>=$this->maxSitemap){
			return false;
		}
		$sitemap=new jSitemapIndex();
		$sitemap->loc=jApp::coord()->request->getServerURI(). $loc;
		if(($timestamp=strtotime($lastmod))){
			$sitemap->lastmod=date('c',$timestamp);
		}
		$this->urlSitemap[]=$sitemap;
	}
	public function importFromUrlsXml(){
		$urls=$this->_parseUrlsXml();
		foreach($urls as $url){
			$this->addUrl($url);
		}
	}
	public function getUrlsFromUrlsXml(){
		return $this->_parseUrlsXml();
	}
	public function ping($uri){
		$parsed_url=parse_url($uri);
		if(!$parsed_url||!is_array($parsed_url)){
			return false;
		}
		$http=new jHttp($parsed_url['host']);
		$http->get($parsed_url['path'] . '?' . $parsed_url['query']);
		if($http->getStatus()!=200){
			return false;
		}
		return true;
	}
	protected function _parseUrlsXml(){
		$urls=array();
		$conf=&jApp::config()->urlengine;
		$significantFile=$conf['significantFile'];
		$basePath=$conf['basePath'];
		$epExt=($conf['multiview'] ? $conf['entrypointExtension']:'');
		$file=jApp::tempPath('compiled/urlsig/' . $significantFile . '.creationinfos.php');
		if(file_exists($file)){
			require $file;
			foreach($GLOBALS['SIGNIFICANT_CREATEURL'] as $selector=>$createinfo){
				if($createinfo[0]!=1&&$createinfo[0]!=4){
					continue;
				}
				if($createinfo[0]==4){
					foreach($createinfo as $k=>$createinfo2){
						if($k==0)continue;
						if($createinfo2[2]==true
						||count($createinfo2[3])){
							continue;
						}
						$urls[]=$basePath.($createinfo2[1]?$createinfo2[1].$epExt:'').$createinfo2[5];
					}
				}
				else if($createinfo[2]==true
						||count($createinfo[3])){
					continue;
				}
				else{
					$urls[]=$basePath.($createinfo[1]?$createinfo[1].$epExt:'').$createinfo[5];
				}
			}
		}
		return $urls;
	}
}
class jSitemapUrl{
	public $loc;
	public $lastmod;
	public $changefreq;
	public $priority;
}
class jSitemapIndex{
	public $loc;
	public $lastmod;
}
