{form $form , 'hfnusearch~default:query'}
<fieldset>
	<legend>{@hfnusearch~forum.search.forums@}</legend>
	<div class="form_row">
		<div class="form_value">
			<strong>{@hfnusearch~forum.search.in.a.particular.forum@}</strong>
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
	<div class="form_row">
		<div class="form_property">
			{ctrl_label 'param'}
		</div>
		<div class="form_value">
			{ctrl_control 'param'}
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
