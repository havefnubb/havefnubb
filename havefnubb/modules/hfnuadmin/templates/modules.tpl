<h1>{@hfnuadmin~admin.modules.list@}</h1>
{if count($modules)}
{for $i=0; $i<count($modules);$i++}
<div class="two-cols">
    <div class="col">
        {$modules[$i]}            
    </div>
</div>
{/for}
{/if}