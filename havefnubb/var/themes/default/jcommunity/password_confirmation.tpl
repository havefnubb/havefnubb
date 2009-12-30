<div class="box">
	<h3>{@havefnubb~member.password.confirmation.activation.of.your.password@}</h3>

	<div class="block loginbox">
	<p>{@havefnubb~member.password.confirmation.activation.description@}</p>

	{form $form,'jcommunity~password:confirm', array()}
	<fieldset>
		<legend>{@havefnubb~member.registration.confirmation.activation@}</legend>
		<ul>
		{formcontrols}
		<li>{ctrl_label} : {ctrl_control}</li>
		{/formcontrols}
		</ul>
		<div >{formsubmit}</div>
	</fieldset>
	{/form}
	</div>
</div>
