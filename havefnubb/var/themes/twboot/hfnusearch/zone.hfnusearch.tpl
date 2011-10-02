{meta_html css $j_themepath .'css/hfnusearch.css'}
{meta_html js  $j_jelixwww . 'jquery/jquery.js'}
{meta_html js  $j_basepath . 'hfnu/js/jquery.autocomplete.pack.js'}
{meta_html css $j_basepath . 'hfnu/js/jquery.autocomplete.css'}
{$javascript}
<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@hfnusearch~search.search.perform@}</li>
</ul>

{hook 'hfbBeforeSearch'}
<div>
    <h3>{@hfnusearch~search.search.perform@}</h3>
    <div id="post-message">{jmessage}</div>
    <form id="jforms_hfnusearch" action="{formurl 'hfnusearch~default:query'}" method="post">
    <fieldset>
    <legend>{@hfnusearch~search.in.all.forums@}</legend>
    <div class="clearfix">
        {formurlparam 'hfnusearch~default:query'}
        <input type="hidden" name="perform_search_in" value="words"/>
        <label class="jforms-label" for="hfnu_q">{@hfnusearch~search.hfnu_q.search@}</label>
        <div class="input">
            <input type="text" id="hfnu_q" name="hfnu_q" size="31" />
        </div>
    </div>
    </fieldset>
    <div class="actions">
        <input class="submit" type="submit" name="validate" value="{@hfnusearch~forum.search.okBt@}" />
    </div>
    </form>
{hook 'hfbSearch'}
</div>
{hook 'hfbAfterSearch'}
