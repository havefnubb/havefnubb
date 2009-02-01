<div id="loginbox">
<h2>{@havefnubb~member.password.confirmation.activation.of.your.password@}</h2>

<p>{@havefnubb~member.password.confirmation.activation.description@}</p>

{form $form,'jcommunity~password:confirm', array()}
<fieldset>
    <legend>{@havefnubb~member.registration.confirmation.activation@}</legend>
    <ul>
    {formcontrols}
    <li>{ctrl_label} : {ctrl_control}</li>
    {/formcontrols}
    </ul>
</fieldset>
<p>{formsubmit}</p>
{/form}

</div>