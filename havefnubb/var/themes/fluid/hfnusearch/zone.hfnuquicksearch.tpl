{meta_html css $j_themepath .'css/hfnusearch.css'}
{meta_html js  $j_jelixwww . 'jquery/jquery.js'}
{meta_html js  $j_basepath . 'hfnu/js/jquery.autocomplete.pack.js'}
{meta_html css $j_basepath . 'hfnu/js/jquery.autocomplete.css'}
{$javascript}
<form action="{formurl 'hfnusearch~default:query'}" method="post">
<fieldset>
    <legend>{@hfnusearch~search.quick.search@}</legend>
    {formurlparam 'hfnusearch~default:query'}    
    <input type="hidden" name="perform_search_in" value="words"/>
    {@hfnusearch~search.hfnu_q.search@} <input type="text" id="hfnu_q" name="hfnu_q" size="31" />
    <input class="jforms-submit" type="submit" name="validate" value="{@hfnusearch~forum.search.okBt@}" />
</fieldset>       
</form>
