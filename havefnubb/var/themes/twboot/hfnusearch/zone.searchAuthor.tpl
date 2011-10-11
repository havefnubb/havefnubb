{form $form , 'hfnusearch~default:query'}
<fieldset>
    <legend>{@hfnusearch~forum.search.author@}</legend>
    <p><strong>{@hfnusearch~forum.search.post.from.an.author@}</strong></p>
    <div class="clearfix">
        {ctrl_label 'hfnu_q'}<div class="input">{ctrl_control 'hfnu_q'}</div>
    </div>
</fieldset>
<div class="actions">
    {formsubmit 'validate'}
</div>
{/form}
