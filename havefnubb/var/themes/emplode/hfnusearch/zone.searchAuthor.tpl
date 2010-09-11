{form $form , 'hfnusearch~default:query'}
<fieldset>
    <div class="legend"><h3>{@hfnusearch~forum.search.author@}</h3></div>
    <div class="form_row">
        {@hfnusearch~forum.search.post.from.an.author@}<br/>
        <div class="form_property">
            {ctrl_label 'hfnu_q'}
        </div>
        <div class="form_value">
             {ctrl_control 'hfnu_q'}
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
    <div class="form_row form_row_submit">
        <div class="form_value">
            {formsubmit 'validate'}
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
</fieldset>
{/form}
