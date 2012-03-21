<div class="box">
    <h2>{@havefnubb~main.split.this.thread.from.this.message@} : "{$title|eschtml}"</h2>
    <div class="block">
{if $step == 1}
    {form $form, 'havefnubb~postsmgr:splitedTo',array('id_forum'=>$id_forum,'id_post'=>$id_post,'thread_id'=>$thread_id)}
        <fieldset>
            <legend>{@havefnubb~main.split.this.thread.from.this.message@} : "{$title|eschtml}"</legend>
            {ctrl_label 'choice'} {ctrl_control 'choice'} {formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
        </fieldset>
    {/form}
    </div>
</div>
{elseif $step == 2}
ok
{/if}
