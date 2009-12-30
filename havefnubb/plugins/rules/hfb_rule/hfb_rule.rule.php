<?php
/**
 * wikirenderer3 (wr3) syntax to xhtml
 *
 * @package WikiRenderer
 * @subpackage rules
 * @author Laurent Jouanneau
 * @copyright 2003-2006 Laurent Jouanneau
 * @link http://wikirenderer.berlios.de
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public 2.1
 * License as published by the Free Software Foundation.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */
require_once WIKIRENDERER_PATH.'rules/wr3_to_xhtml.php';
/**
 * hfb_rule class
 */
class hfb_rule extends wr3_to_xhtml {
	/**
	  * @var array   liste des tags inline
	 */
	 public $inlinetags= array( 'wr3xhtml_strong','wr3xhtml_em','wr3xhtml_code','wr3xhtml_q',
	  'wr3xhtml_cite','wr3xhtml_acronym','wr3xhtml_link', 'wr3xhtml_image',
	  'wr3xhtml_anchor', 'wr3xhtml_footnote');

	 public $defaultTextLineContainer = 'WikiHtmlTextLine';

	 public $availabledTextLineContainers = array('WikiHtmlTextLine');

	 /**
	 * liste des balises de type bloc reconnus par WikiRenderer.
	 */
	 public $bloctags = array('wr3xhtml_title', 'wr3xhtml_list', 'wr3xhtml_pre','wr3xhtml_hr',
						   'wr3xhtml_blockquote','wr3xhtml_definition','wr3xhtml_table', 'wr3xhtml_p');


	 public $simpletags = array('%%%'=>'<br />');


	 // la syntaxe wr3 contient la possibilité de mettre des notes de bas de page
	 // celles-ci seront stockées ici, avant leur incorporation é la fin du texte.
	 public $footnotes = array();
	 public $footnotesId='';
	 public $footnotesTemplate = '<div class="footnotes"><h4>Notes</h4>%s</div>';

	  /**
	  * methode invoquée avant le parsing
	  */
	 public function onStart($texte){
		  global $gJConfig;

		  $this->footnotesId = rand(0,30000);
		  $this->footnotes = array(); // on remet é zero les footnotes
		  return $texte;
	  }

	 /**
	  * methode invoquée aprés le parsing
	  */
	  public function onParse($finalTexte){
		  // on rajoute les notes de bas de pages.
		  if(count($this->footnotes)){
			  $footnotes = implode("\n",$this->footnotes);
			  $finalTexte .= str_replace('%s', $footnotes, $this->footnotesTemplate);
		  }
		  return $finalTexte;
	  }
	  /**
	   * lets manage some smileys *<:o)
	   */
	  public function __construct() {
		  global $gJConfig;
		  $this->simpletags = array(
				  '%%%'=>'<br />',
				 '>:)'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_evilgrin.png" alt="evilgrin" />',
				  ':D'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_grin.png" alt="grin" />',
			   ':lol:'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_happy.png" alt="happy" />',
				 ':-)'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_smile.png" alt="smile" />',
				  ':)'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_smile.png" alt="smile" />',
				  ':O'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_surprised.png" alt="surprised" />',
				  ':o'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_surprised.png" alt="surprised" />',
				  ':P'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_tongue.png" alt="tongue" />',
				  ':p'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_tongue.png" alt="tongue" />',
				  ':('=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_unhappy.png" alt="unhappy" />',
				 ':-('=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_unhappy.png" alt="unhappy" />',
				  ':}'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_waii.png" alt="waii" />',
				 ':-}'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_waii.png" alt="waii" />',
				  ';)'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_wink.png" alt="wink" />',
				 ';-)'=>'<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'].'/emoticon_wink.png" alt="wink" />',
				  );
	  }
}
