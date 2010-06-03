<div class="box">
    {if $parent_id == $id_post}
    <h2>{@havefnubb~main.censor.this.thread.from.this.message@} : "{$title|eschtml}"</h2>
    {else}
    <h2>{@havefnubb~main.censor.this.message@} : "{$title|eschtml}"</h2>
    {/if}
    <div class="block">
    {form $form, 'havefnubb~posts:savecensor',array('id_post'=>$id_post,'parent_id'=>$parent_id)}
        {@havefnubb~main.censor.description@}
        <fieldset>
            {if $parent_id == $id_post}
            <legend>{@havefnubb~main.censor.this.thread.from.this.message@} : "{$title|eschtml}"</legend>
            {else}
            <legend>{@havefnubb~main.censor.this.message@} : "{$title|eschtml}"</legend>
            {/if}
            {ctrl_label 'censored_msg'} {ctrl_control 'censored_msg'} <br/>
            {formsubmit 'validate'} {gobackto 'havefnubb~main.go.back.to'}
        </fieldset>
    {/form}
    </div>
</div>
