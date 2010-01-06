<div id="post-message">{jmessage}</div>
<div class="send_to_friend">
	{form $form, $action}
	<fieldset>
		<div class="legend"><h3>{@hfnucontact~contact.send.an.email.to.a.friend@}</h3></div>
		<div class="form_row">
			<div class="form_property">{ctrl_label 'email_to'}</div>
			<div class="form_data">{ctrl_control 'email_to'}</div>
			<div class="clearer">&nbsp;</div>
		</div>
		<div class="form_row">
			<div class="form_property">{ctrl_label 'subject'}</div>
			<div class="form_data">{ctrl_control 'subject'}</div>
			<div class="clearer">&nbsp;</div>
		</div>
		<div class="form_row">
			<div class="form_property">{ctrl_label 'message'}</div>
			<div class="form_data">{ctrl_control 'message'}</div>
			<div class="clearer">&nbsp;</div>
		</div>
		<div class="form_row">
			<div class="form_property">{ctrl_label 'captcha'}</div>
			<div class="form_data">{ctrl_control 'captcha'}</div>
			<div class="clearer">&nbsp;</div>
		</div>
		<div class="form_row form_row_submit">
			<div class="form_value">
			{formsubmit 'validate'} {formreset 'reset'} {gobackto 'contact.go.back.to'}
			</div>
			<div class="clearer">&nbsp;</div>
		</div>
	</fieldset>
	{/form}
	</div>
</div>
