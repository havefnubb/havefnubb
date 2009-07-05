<?php
/**
* @package   havefnubb
* @subpackage hfnurates
* @author    FoxMaSk
* @copyright 2008 FoxMaSk
* @link      http://forge.jelix.org/projects/havefnubb
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ratesZone extends jZone {

    protected $_tplname='zone.rates';
    
    protected function _prepareTpl(){
        $id_source = $this->param('id_source');
		if (! $id_source ) return;
		$source = $this->param('source');  
        if (! $source ) return;		
		
		$url = jUrl::get('hfnurates~default:ait');		
		$js = '		
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
    var options = { 
        success:   showResponse,
        url:       "'.$url.'",
        type:      "post",     
        dataType:  "text",
    };         
    $(\'.starsrating\').rating({
        focus: function(value, link){
          var tip = $(\'#rating-hover\');
          tip[0].data = tip[0].data || tip.html();
          tip.html(link.title || \'value: \'+value);
        },
        blur: function(value, link){
          var tip = $(\'#rating-hover\');
          $(\'#rating-hover\').html(tip[0].data || \'\');
        },
        callback: function(value, link){
            $(this.form).ajaxSubmit(options);
        }
   });
});
function showResponse(response) {
     $(\'#post-rates-msg\').html(\''.jLocale::get('hfnurates~main.thanks.you.for.rating').'\');
}
//]]>
</script>';

		$rates 	= jClasses::getService('hfnurates~rates');
		$data 	= $rates->getTotalRatesBySource($id_source,$source);		

		$this->_tpl->assign('js',$js);
		$this->_tpl->assign('id_source',$id_source);
		$this->_tpl->assign('level',$level);
    }    
}