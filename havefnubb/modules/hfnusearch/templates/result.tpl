<div id="breadcrumbtop" class="headbox">
    <h3>{@hfnusearch~search.results@}</h3>    
</div>
<div id="result">
{if $count == 0}
No Result
{else}
{for $i = 0 ; $i < count($datas) ; $i++}
{foreach $datas[$i] as $subject => $message}
{$subject|eschtml}
{/foreach}
{/for}
{/if}
</div>