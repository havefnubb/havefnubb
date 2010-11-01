<h1>{@hfnuadmin~admin.modules.list@}</h1>
<p>{@hfnuadmin~admin.modules.list.description@}</p>
{foreach $modules as $idx=>$key}
<div class="two-cols">
    <div class="col">
    {$key}
    </div>
</div>
{/foreach}

