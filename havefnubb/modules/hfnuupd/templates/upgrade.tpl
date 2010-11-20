<div class="box">
    <h2>{@upgrade.upgrade@}</h2>
    <div class="block">
        {if $msg == ''}
        <p>{@upgrade.description@}</p>
        <a href="{jurl 'hfnuupd~default:run_1_4_0'}" alt="{@upgrade.run.the.upgrading@}">{@upgrade.run.the.upgrading@}</a>
        {else}
        {$msg}<br/>
        <a href="{jurl 'havefnubb~default:index'}"  alt="{@upgrade.back.to.forum@}">{@upgrade.back.to.forum@}</a>
        {/if}
    </div>
</div>
