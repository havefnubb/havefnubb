{meta_html js  $j_jelixwww . 'jquery/jquery.js'}
{meta_html js  $j_basepath . 'hfnu/js/jquery.autocomplete.pack.js'}
{meta_html css $j_basepath . 'hfnu/js/jquery.autocomplete.css'}
{$javascript}
<form class="pull-left" action="{formurl 'hfnusearch~default:query'}" method="post" id="quicksearch">
    {formurlparam 'hfnusearch~default:query'}
    <input type="hidden" name="perform_search_in" value="words"/>
    <!--label class="jforms-label" for="hfnu_q">{@hfnusearch~search.hfnu_q.search@}</label-->
    <input type="text" id="hfnu_q" name="hfnu_q" size="31" placeholder="{@hfnusearch~search.quick.search@}" />
    <!--input class="jforms-submit" type="submit" name="validate" value="{@hfnusearch~forum.search.okBt@}" /-->
    <!--a href="{jurl 'hfnusearch~default:index'}">{@hfnusearch~forum.advanced.search@}</a-->
</form>
