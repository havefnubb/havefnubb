{meta_html assets 'hfnurates'}
<div class="post-rates">
    <div class="rates-result">{$result}</div>
    <form id="form{$id_source}" action="{formurl 'hfnurates~default:rate_it'}"
          data-ajax-action="{jurl 'hfnurates~default:rate_ajax_it'}"
          method="post" class="rating-form">
        <div>
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
        <input type="submit" class="submit" value="{@hfnurates~main.lets.rate@}" /><br/>
        <span class="rating-hover" style="margin:0 0 0 20px;">{@hfnurates~main.your.rate.will.be@}</span>
        </div>
    </form>
    <div class="post-rates-msg" style="display:none">{@hfnurates~main.thanks.you.for.rating@}</div>
</div>
