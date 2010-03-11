<div id="post-message">{jmessage}</div>
<div class="box">
	<h2>{@hfnucontact~contact.send.an.email@}</h2>
	<div class="block">
	{form $form, $action}
	<fieldset>
		<legend>{jlocale 'hfnucontact~contact.send.an.email.to',$to}</legend>
		<p>
		{ctrl_label 'email_name'}<br/>
		{ctrl_control 'email_name'}
		</p>
		<p>
		{ctrl_label 'email_from'}<br/>
		{ctrl_control 'email_from'}
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
