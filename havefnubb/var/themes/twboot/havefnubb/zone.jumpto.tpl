<div class="alert-message block-message info" id="act_jumpto" data-alert="alert">
    <a class="close" href="#">×</a>
    <strong>{@havefnubb~forum.jumpto.jumpto@}</strong>
        {form $form, 'havefnubb~posts:goesto'}
        <div class="clearfix">
            {ctrl_label 'id_forum'}
            <div class="input">{ctrl_control 'id_forum'} {formsubmit 'validate'} {formreset 'reset'}</div>
        </div>
        {/form}
</div>
