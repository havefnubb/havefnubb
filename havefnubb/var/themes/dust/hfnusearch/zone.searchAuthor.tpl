{form $form , 'hfnusearch~default:query'}
<fieldset>
    <legend>{@hfnusearch~forum.search.author@}</legend>
    <div class="form_row">
        <div class="form_value">
            <strong>{@hfnusearch~forum.search.post.from.an.author@}</strong>
        </div>
        <div class="clearer">&nbsp;</div>
    </div>    
    <div class="form_row">
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