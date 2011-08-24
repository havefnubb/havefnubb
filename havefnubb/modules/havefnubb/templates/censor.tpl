<div class="box">
    {if $thread_id == $id_post}
    <h3>{@havefnubb~main.censor.this.thread.from.this.message@} : "{$title|eschtml}"</h3>
    {else}
    <h3>{@havefnubb~main.censor.this.message@} : "{$title|eschtml}"</h3>
    {/if}
    <div class="box-content">
    {form $form, 'havefnubb~posts:savecensor',array('id_post'=>$id_post,'thread_id'=>$thread_id)}
        {@havefnubb~main.censor.description@}
        <fieldset>
            {if $thread_id == $id_post}
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
