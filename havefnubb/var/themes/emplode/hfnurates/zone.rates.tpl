{meta_html css $j_basepath.'hfnu/images/star-rating/jquery.rating.css'}
{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/include/jquery.include.js'}
{meta_html js $j_basepath.'hfnu/js/jquery.MetaData.js'}
{meta_html js $j_basepath.'hfnu/js/jquery.form.js'}
{meta_html js $j_basepath.'hfnu/js/jquery.rating.pack.js'}
{* javascript and ajax code *}
{$js}
<div class="rates-result">{$result}</div>
<form id="form{$id_source}" action="{formurl 'hfnurates~default:rate_it'}" method="post">
	<div class="post-rates">
	<input type="hidden" value="{$id_source}" id="id_source" name="id_source"/>
	<input type="hidden" value="{$source}" id="source" name="source"/>
	<input type="hidden" value="{$return_url}" name="return_url" />
	{foreach $return_url_params as $key => $value}
	<input type="hidden" value="{$value}" name="return_url_params[{$key}]" />
	{/foreach}
	<input name="star1" type="radio" class="starsrating" value="1" title="{@hfnurates~main.very.poor@}" {if $checked < 21 and $checked > 0 }checked="checked"{/if}/><span class="star-legend">{@hfnurates~main.very.poor@}</span>
	<input name="star1" type="radio" class="starsrating" value="2" title="{@hfnurates~main.poor@}" {if $checked < 41 and $checked > 20}checked="checked"{/if}/><span class="star-legend">{@hfnurates~main.poor@}</span>
	<input name="star1" type="radio" class="starsrating" value="3" title="{@hfnurates~main.ok@}" {if $checked < 61 and $checked > 40}checked="checked"{/if}/><span class="star-legend">{@hfnurates~main.ok@}</span>
	<input name="star1" type="radio" class="starsrating" value="4" title="{@hfnurates~main.good@}" {if $checked < 81 and $checked > 60}checked="checked"{/if}/><span class="star-legend">{@hfnurates~main.good@}</span>
	<input name="star1" type="radio" class="starsrating" value="5" title="{@hfnurates~main.very.good@}" {if $checked  > 80}checked="checked"{/if}/><span class="star-legend">{@hfnurates~main.very.good@}</span>
	<input type="submit" class="submit-rates" value="{@hfnurates~main.lets.rate@}" /><br/>
	<span id="rating-hover" style="margin:0 0 0 20px;">{@hfnurates~main.your.rate.will.be@}</span>
	</div>
</form>
<div id="post-rates-msg"></div>
