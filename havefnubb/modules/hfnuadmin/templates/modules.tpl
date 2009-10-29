<h1>{@hfnuadmin~admin.modules.list@}</h1>
{if count($modules)}
{assign $count = count($modules)}
{for $i=0; $i < $count ;$i++}
<div class="modulelist">
    {$modules[$i]}            
</div>
{/for}
{/if}