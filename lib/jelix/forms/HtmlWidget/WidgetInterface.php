<?php
/* comments & extra-whitespaces have been removed by jBuildTools*/
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @copyright   2012 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
namespace jelix\forms\HtmlWidget;
interface WidgetInterface{
	public function __construct($args);
	public function getId();
	public function getName();
	public function getValue();
	public function outputMetaContent($resp);
	public function outputHelp();
	public function outputLabel($format='',$editMode=true);
	public function outputControl();
	public function outputControlValue();
	public function setAttributes($attributes);
}
