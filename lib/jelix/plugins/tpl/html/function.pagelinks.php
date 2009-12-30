<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package    jelix
* @subpackage jtpl_plugin
* @author     Jouanneau Laurent
* @copyright  2007 Jouanneau laurent
* @contributor Christian Tritten (christian.tritten@laposte.net)
* @copyright  2007 Christian Tritten
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/
function jtpl_function_html_pagelinks($tpl, $action, $actionParams, $itemsTotal, $offset, $pageSize = 15,
									  $paramName = 'offset', $displayProperties = array())
{
	$offset = intval($offset);
	if($offset <= 0)
		$offset = 0;
	$itemsTotal = intval($itemsTotal);
	$pageSize = intval($pageSize);
	if($pageSize < 1)
		$pageSize = 1;
	if($itemsTotal > $pageSize){
		$jUrlEngine = jUrl::getEngine();
		$urlaction = jUrl::get($action, $actionParams, jUrl::JURLACTION);
		$defaultDisplayProperties = array('start-label' => '|&lt;',
										  'prev-label'  => '&lt;',
										  'next-label'  => '&gt;',
										  'end-label'   => '&gt;|',
										  'area-size'   => 0);
		if(is_array($displayProperties) && count($displayProperties) > 0)
			$displayProperties = array_merge($defaultDisplayProperties, $displayProperties);
		else
			$displayProperties = $defaultDisplayProperties;
		$pages = array();
		$currentPage = 1;
		$numpage = 1;
		$prevBound = 0;
		$nextBound = 0;
		for($curidx = 0; $curidx < $itemsTotal; $curidx += $pageSize){
			if($offset >= $curidx && $offset < $curidx + $pageSize){
				$pages[$numpage] = '<li class="pagelinks-current">'.$numpage.'</li>';
				$prevBound = $curidx - $pageSize;
				$nextBound = $curidx + $pageSize;
				$currentPage = $numpage;
			} else{
				$urlaction->params[$paramName] = $curidx;
				$url = $jUrlEngine->create($urlaction);
				$pages[$numpage] = '<li><a href="'.$url->toString(true).'">'.$numpage.'</a></li>';
			}
			$numpage++;
		}
		$urlaction->params[$paramName] = 0;
		$urlStartPage = $jUrlEngine->create($urlaction);
		$urlaction->params[$paramName] = $prevBound;
		$urlPrevPage = $jUrlEngine->create($urlaction);
		$urlaction->params[$paramName] = $nextBound;
		$urlNextPage = $jUrlEngine->create($urlaction);
		$urlaction->params[$paramName] =(count($pages) - 1) * $pageSize;
		$urlEndPage = $jUrlEngine->create($urlaction);
		echo '<ul class="pagelinks">';
		if(!empty($displayProperties['start-label'])){
			echo '<li class="pagelinks-start';
			if($prevBound >= 0){
				echo '"><a href="', $urlStartPage->toString(true), '">', $displayProperties['start-label'], '</a>';
			} else{
				echo ' pagelinks-disabled">',$displayProperties['start-label'] ;
			}
			echo '</li>', "\n";
		}
		if(!empty($displayProperties['prev-label'])){
			echo '<li class="pagelinks-prev';
			if($prevBound >= 0){
				echo '"><a href="', $urlPrevPage->toString(true), '">', $displayProperties['prev-label'], '</a>';
			} else{
				echo ' pagelinks-disabled">',$displayProperties['prev-label'] ;
			}
			echo '</li>', "\n";
		}
		foreach($pages as $key => $page){
			if($displayProperties['area-size'] == 0 ||($currentPage - $displayProperties['area-size'] <= $key)
				&&($currentPage + $displayProperties['area-size'] >= $key))
				echo $page, "\n";
		}
		if(!empty($displayProperties['next-label'])){
			echo '<li class="pagelinks-next';
			if($nextBound < $itemsTotal){
				echo '"><a href="', $urlNextPage->toString(true), '">', $displayProperties['next-label'], '</a>';
			} else{
				echo ' pagelinks-disabled">',$displayProperties['next-label'] ;
			}
			   echo '</li>', "\n";
		}
		if(!empty($displayProperties['end-label'])){
			echo '<li class="pagelinks-end';
			if($nextBound < $itemsTotal){
				echo '"><a href="', $urlEndPage->toString(true), '">', $displayProperties['end-label'], '</a>';
			} else{
				echo ' pagelinks-disabled">',$displayProperties['end-label'] ;
			}
			   echo '</li>', "\n";
		}
		echo '</ul>';
	}
	else echo '<ul class="pagelinks"><li class="pagelinks-current">1</li></ul>';
}