<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> </h3>
</div>
<div id="post-split-to">
{if $step == 1}
    <h1>{@havefnubb~main.split.this.thread.from.this.message@} : "{$title|eschtml}"</h1>
    {form $form, 'havefnubb~posts:splitTo'}
    <fieldset>
    <p>{ctrl_label 'choice'} </p>
    {ctrl_control 'choice'}
    </fieldset>
    <div>{formsubmit 'validate'} {formreset 'cancel'}</div>
    {/form}
{elseif $step == 2}
ok
{/if}
</div>