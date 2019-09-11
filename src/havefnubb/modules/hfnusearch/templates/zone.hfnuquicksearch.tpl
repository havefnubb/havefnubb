{meta_html assets 'hfnusearch'}
{$javascript}
<form action="{formurl 'hfnusearch~default:query'}" method="post" id="quicksearch">
<fieldset>
    <legend>{@hfnusearch~search.quick.search@}</legend>
    {formurlparam 'hfnusearch~default:query'}
    <input type="hidden" name="perform_search_in" value="words"/>
    <label class="jforms-label" for="hfnu_q">{@hfnusearch~search.hfnu_q.search@}</label>
    <input type="text" id="hfnu_q" name="hfnu_q" size="31" />
    <input class="jforms-submit" type="submit" name="validate" value="{@hfnusearch~forum.search.okBt@}" />
    <a href="{jurl 'hfnusearch~default:index'}">{@hfnusearch~forum.advanced.search@}</a>
</fieldset>
</form>
