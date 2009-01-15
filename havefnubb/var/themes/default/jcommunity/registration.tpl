<div id="loginbox">
<h3 class="headbox">{@havefnubb~member.registration.account.creation@}</h3>

<p>{@havefnubb~member.registration.account.service.description@}</p>

{form $form,'jcommunity~registration:save', array()}
<fieldset>
    <legend>{@havefnubb~main.informations@}</legend>
    <p>{ctrl_label 'reg_login'} : {ctrl_control 'reg_login'}</p>
    <p>{ctrl_label 'reg_email'} : {ctrl_control 'reg_email'}</p>
</fieldset>
<p>{@havefnubb~member.registration.account.mail.description@}</p>
<p>{formsubmit}</p>
{/form}
</div>