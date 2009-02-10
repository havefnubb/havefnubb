<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~member.passwordform.header@}</h3>    
</div>
<div id="loginbox">
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