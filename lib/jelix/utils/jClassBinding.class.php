<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
 * @package     jelix
 * @subpackage  utils
 * @author      Christophe THIRIOT
 * @contributor Laurent Jouanneau
 * @copyright   2008 Christophe THIRIOT, 2008-2010 Laurent Jouanneau
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 * @since 1.1
 */
class jClassBinding{
	protected $fromSelector=null;
	protected $toSelector=null;
	protected $instance=null;
	public function __construct($selector){
		require_once($selector->getPath());
		$this->fromSelector=$selector;
	}
	public function to($toselector){
		$this->toSelector=new jSelectorClass($toselector);
		$this->instance=null;
		return $this;
	}
	public function toInstance($instance){
		$this->instance=$instance;
		$this->toSelector=null;
		return $this;
	}
	public function getInstance($singleton=true){
		if(true===$singleton&&$this->instance!==null){
			return $this->instance;
		}
		$this->instance=$this->_createInstance();
		return $this->instance;
	}
	protected function _createInstance(){
		if($this->toSelector===null){
			$this->instance=null;
			$this->toSelector=$this->_getClassSelector();
		}
		return jClasses::create($this->toSelector->toString());
	}
	public function getClassName(){
		if($this->instance!==null){
			return get_class($this->instance);
		}elseif($this->toSelector!==null){
			return $this->toSelector->className;
		}else{
			return $this->_getClassSelector()->className;
		}
	}
	protected function _getClassSelector(){
		$class_selector=null;
		if($this->toSelector===null&&$this->instance===null){
			$str_selector=$this->fromSelector->toString();
			$str_selector_long=$this->fromSelector->toString(true);
			global $gJConfig;
			if(isset($gJConfig->classbindings)&&count($gJConfig->classbindings)){
				$conf=$gJConfig->classbindings;
				$conf_selector=str_replace('~','-',$str_selector);
				$conf_selector_long=str_replace('~','-',$str_selector_long);
				$str_fromselector=null;
				if(isset($conf[$conf_selector])){
					$str_fromselector=$conf_selector;
				}elseif(isset($conf[$conf_selector_long])){
					$str_fromselector=$conf_selector_long;
				}
				if($str_fromselector!==null){
					$this->fromSelector=jSelectorFactory::create($str_selector_long,'iface');
					return $this->toSelector=new jSelectorClass($conf[$str_fromselector]);
				}
			}
			$constname=$this->fromSelector->className . '::JBINDING_BINDED_IMPLEMENTATION';
			if(defined($constname)){
				$class_selector=constant($constname);
				if($class_selector!==null)
					return $this->toSelector=new jSelectorClass($class_selector);
			}
			if(true===($this->fromSelector instanceof jSelectorClass)){
				return $this->toSelector=$this->fromSelector;
			}
			throw new jException('jelix~errors.bindings.nobinding',array($this->fromSelector->toString(true)));
		}
	}
}
