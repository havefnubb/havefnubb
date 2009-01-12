<h2>Retrieve a new password</h2>

<p>If you have forgotten your password, fill the following form with your login
and with the email you have set in your profil.</p>

{form $form,'jcommunity~password:send', array()}
<fieldset>
    <p>{ctrl_label 'pass_login'} : {ctrl_control 'pass_login'}</p>
    <p>{ctrl_label 'pass_email'} : {ctrl_control 'pass_email'}</p>
</fieldset>
<p>An email will be sent with a key to allow you to choose a new password.</p>
<p>{formsubmit}</p>
{/form}
