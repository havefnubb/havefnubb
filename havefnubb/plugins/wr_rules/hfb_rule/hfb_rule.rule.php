<?php
/**
 * @package WikiRenderer
 * @subpackage rules
* @author     foxmask
* @contributor
* @copyright  2008 foxmask
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
require_once WIKIRENDERER_PATH.'rules/wr3_to_xhtml.php';
/**
 * Class that handles the own Havefnubb wiki syntax
 */
class hfb_rule extends wr3_to_xhtml {

	  /**
	   * lets manage some smileys *<:o)
	   */
	  public function __construct() {
		  global $gJConfig;
          $p = '<img src="'.$gJConfig->urlengine["basePath"].'hfnu/images/smileys/'.$gJConfig->smileys_pack['name'];
		  $this->simpletags = array(
				  '%%%'=>'<br />',
				 '>:)'=>$p.'/emoticon_evilgrin.png" alt="evilgrin" />',
				  ':D'=>$p.'/emoticon_grin.png" alt="grin" />',
			   ':lol:'=>$p.'/emoticon_happy.png" alt="happy" />',
				 ':-)'=>$p.'/emoticon_smile.png" alt="smile" />',
				  ':)'=>$p.'/emoticon_smile.png" alt="smile" />',
				  ':O'=>$p.'/emoticon_surprised.png" alt="surprised" />',
				  ':o'=>$p.'/emoticon_surprised.png" alt="surprised" />',
				  ':P'=>$p.'/emoticon_tongue.png" alt="tongue" />',
				  ':p'=>$p.'/emoticon_tongue.png" alt="tongue" />',
				  ':('=>$p.'/emoticon_unhappy.png" alt="unhappy" />',
				 ':-('=>$p.'/emoticon_unhappy.png" alt="unhappy" />',
				  ':}'=>$p.'/emoticon_waii.png" alt="waii" />',
				 ':-}'=>$p.'/emoticon_waii.png" alt="waii" />',
				  ';)'=>$p.'/emoticon_wink.png" alt="wink" />',
				 ';-)'=>$p.'/emoticon_wink.png" alt="wink" />',
				  );
	  }
}
