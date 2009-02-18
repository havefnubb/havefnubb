<div id="breadcrumbtop" class="headbox">
    <h3>{@hfnusearch~search.results@}</h3>    
</div>
<div id="result">
{if $count == 0}
No Result
{else}
{for $i = 0 ; $i < count($datas) ; $i++ }
<h3 class="result-header">{$datas[$i]['subject']|eschtml}</a></h3>
<span class="result-author">posted by {$datas[$i]['member_login']}</span> - {$datas[$i]['date_created']|jdatetime:'db_datetime':'lang_datetime'} <br/>
<p class="result-msg">{$datas[$i]['message']|eschtml}</p>
{/for}
{/if}
</div>