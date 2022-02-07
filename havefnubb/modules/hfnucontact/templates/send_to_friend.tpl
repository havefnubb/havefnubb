<div id="post-message">{jmessage}</div>
<div class="box">
	<h2>{@hfnucontact~contact.send.an.email.to.a.friend@}</h2>
	<div class="box-content">
	{form $form, $action}
	<fieldset>
		<legend>{@hfnucontact~contact.send.an.email.to.a.friend@}</legend>
		<p>
		{ctrl_label 'email_to'}<br/>
		{ctrl_control 'email_to'}
		</p>
		<p>
		{ctrl_label 'subject'}<br/>
		{ctrl_control 'subject'}
		</p>
		<p>
		{ctrl_label 'message'}<br/>
		{ctrl_control 'message'}
		</p>
		<p>
		{ctrl_label 'captcha'} <br/>
		{ctrl_control 'captcha'}
		</p>
		{formsubmit 'validate'} {formreset 'reset'} {gobackto 'contact.go.back.to'}
	</fieldset>
	{/form}
	</div>
</div>
