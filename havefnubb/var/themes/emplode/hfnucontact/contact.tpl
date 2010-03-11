<div id="breadcrumbtop" class="headbox box_title">
	<h3><span>{@hfnucontact~contact.send.an.email@}</span></h3>
</div>
<div id="post-message">{jmessage}</div>

{form $form, 'hfnucontact~default:send_a_message'}
<div class="form_row">
	<div class="form_property">{ctrl_label 'email_name'}</div>
	<div class="form_value">{ctrl_control 'email_name'}</div>
	<div class="clearer">&nbsp;</div>
</div>
<div class="form_row">
	<div class="form_property">{ctrl_label 'email_from'}</div>
	<div class="form_value">{ctrl_control 'email_from'}</div>
	<div class="clearer">&nbsp;</div>
</div>
<div class="form_row">
	<div class="form_property">{ctrl_label 'subject'} </div>
	<div class="form_value">{ctrl_control 'subject'} </div>
	<div class="clearer">&nbsp;</div>
</div>

<div class="form_row">
	<div class="form_property">{ctrl_label 'message'} </div>
	<div class="form_value">{ctrl_control 'message'}</div>
	<div class="clearer">&nbsp;</div>
</div>

<div class="form_row">
	<div class="form_property">{ctrl_label 'captcha'} </div>
	<div class="form_value">{ctrl_control 'captcha'}</div>
	<div class="clearer">&nbsp;</div>
</div>

<div class="form_row form_row_submit">
	<div class="form_value">
	{formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
	</div>
	<div class="clearer">&nbsp;</div>
</div>
{/form}

