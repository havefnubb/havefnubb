{form $form , 'hfnusearch~default:query'}
<fieldset>
    <legend>{@hfnusearch~forum.search.forums@}</legend>
    <p><strong>{@hfnusearch~forum.search.in.a.particular.forum@}</strong></p>
    <div class="clearfix">
        {ctrl_label 'hfnu_q'}<div class="input">{ctrl_control 'hfnu_q'}</div>
    </div>
    <div class="clearfix">
        {ctrl_label 'param'}<div class="input">{ctrl_control 'param'}</div>
    </div>
</fieldset>
<div class="actions">
    {formsubmit 'validate'}
</div>
{/form}
