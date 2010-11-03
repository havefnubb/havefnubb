<h1>{@modulesinfo~modulesinfo.modules.list.title@}</h1>
<p>{@modulesinfo~modulesinfo.modules.list.description@}</p>
{foreach $modules as $idx=>$key}
<div class="two-cols">
    <div class="col">
    {$key}
    </div>
</div>
{/foreach}

