<div class="box" id="act_jumpto">
    <h3>{@havefnubb~forum.jumpto.jumpto@}</h3>
    <div class="box-content">
        {form $form, 'havefnubb~posts:goesto'}
        <div>{ctrl_label 'id_forum'} {ctrl_control 'id_forum'} {formsubmit 'validate'} {formreset 'reset'}</div>
        {/form}
    </div>
</div>
