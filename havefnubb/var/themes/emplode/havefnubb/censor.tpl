<div class="censor">
    {form $form, 'havefnubb~posts:savecencor',array('id_post'=>$id_post,'thread_id'=>$thread_id)}
    <fieldset>
        {if $thread_id == $id_post}
        <div class="legend"><h3>{@havefnubb~main.censor.this.thread.from.this.message@} : "{$title|eschtml}"</h3></div>
        {else}
        <div class="legend"><h3>{@havefnubb~main.censor.this.message@} : "{$title|eschtml}"</h3></div>
        {/if}
        <div class="form_row">
            <p>{@havefnubb~main.censor.description@}</p>
            <div class="form_property">{ctrl_label 'censored_msg'}</div>
            <div class="form_data">{ctrl_control 'censored_msg'}</div>
            <div class="clearer">&nbsp;</div>
        </div>
        <div class="form_row form_row_submit">
            <div class="form_value">
            {formsubmit 'validate'} {gobackto 'havefnubb~main.go.back.to'}
            </div>
            <div class="clearer">&nbsp;</div>
        </div>
    </fieldset>
    {/form}
    </div>
</div>
