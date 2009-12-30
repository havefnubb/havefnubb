<div id="breadcrumbtop" class="headbox box_title">
	<h3>{@havefnubb~member.passwordform.header@}</h3>
</div>
<div id="loginbox">
{form $form,'jcommunity~password:send', array()}
<fieldset>
	<div class="legend">{@havefnubb~member.passwordform.description@}</div>
	<div class="form_row">
		<div class="form_property">{ctrl_label 'pass_login'} :</div><div class="form_value">{ctrl_control 'pass_login'}</div>
		<div class="form_property">{ctrl_label 'pass_email'} :</div><div class="form_value">{ctrl_control 'pass_email'}</div>
		<div class="clearer">&nbsp;</div>
	</div>
	<p>{@havefnubb~member.passwordform.mail.description@}</p>
	<div class="form_row form_row_submit">{formsubmit}</div>
</fieldset>
{/form}
</div>
