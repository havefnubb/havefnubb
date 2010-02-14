<div id="breadcrumbtop" class="headbox box_title">
	<h5 id="user" class="user-image"><a href="{jurl 'jcommunity~account:edit' , array('user'=>$login)}">{@havefnubb~member.edit.account.header@}</a> - <a class="user-private-message user-image" href="{jurl 'havefnubb~members:mail'}" >{@havefnubb~member.internal.messenger@}</a> > <span class="user-edit-password user-image">{@havefnubb~member.pwd.change.of.password@}</span></h5>
</div>
<div id="profile">
{form $form,'havefnubb~members:savenewpwd', array('user'=>$login)}
<fieldset>
	<div class="legend">{@havefnubb~member.pwd.change.your.password@}</div>
	{formcontrols}
	<div class="form_row">
		<div class="form_property">{ctrl_label} </div>
		<div class="form_value">{ctrl_control} </div>
		<div class="clearer">&nbsp;</div>
	</div>
	{/formcontrols}
	<div class="form_row form_row_submit">
		<div class="form_value">{formsubmit}</div>
		<div class="clearer">&nbsp;</div>
	</div>
</fieldset>
{/form}
</div>
