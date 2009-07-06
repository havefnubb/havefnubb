{meta_html css $j_basepath.'images/star-rating/jquery.rating.css'}
{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/include/jquery.include.js'}
{meta_html js $j_jelixwww.'jquery/jquery.MetaData.js'}
{meta_html js $j_jelixwww.'jquery/jquery.form.js'}
{meta_html js $j_jelixwww.'jquery/jquery.rating.pack.js'}

{* javascript and ajax code *}
{$js}
<form id="form{$id_source}" action="{formurl 'hfnurates~default:rate_it'}" method="post">
    <div class="post-rates">          
    <input type="hidden" value="{$id_source}" id="id_source" name="id_source"/>
    <input type="hidden" value="post" id="source" name="source"/>
    <input type="hidden" value="{$redirect}" name="redirect" />
    <input name="star1" type="radio" class="starsrating" value="1" title="Very Poor"/>
    <input name="star1" type="radio" class="starsrating" value="2" title="Poor"/>
    <input name="star1" type="radio" class="starsrating" value="3" title="Ok"/>
    <input name="star1" type="radio" class="starsrating" value="4" title="Good"/>
    <input name="star1" type="radio" class="starsrating" value="5" title="Very Good"/>
    <input type="submit" value="Rate!" />
    <span id="rating-hover" style="margin:0 0 0 20px;">Rates is ?</span>      
    </div>
</form>
 
<div id="post-rates-msg"></div>