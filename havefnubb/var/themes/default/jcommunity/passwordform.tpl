<div id="loginbox">
<h3  class="up-and-down headbox">{@havefnubb~member.passwordform.header@}</h3>

<p>{@havefnubb~member.passwordform.description@}</p>

{form $form,'jcommunity~password:send', array()}
<fieldset>
    <p>{ctrl_label 'pass_login'} : {ctrl_control 'pass_login'}</p>
    <p>{ctrl_label 'pass_email'} : {ctrl_control 'pass_email'}</p>
</fieldset>
<p>{@havefnubb~member.passwordform.mail.description@}</p>
<p>{formsubmit}</p>
{/form}
</div>