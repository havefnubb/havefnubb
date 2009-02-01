<div id="profile">
<h3>{@havefnubb~member.registration.confirmation.activation.of.your.account@}</h3>

<p>{@havefnubb~member.registration.confirmation.activation.description@}</p>

<p>{@havefnubb~member.registration.confirmation.activation.description.line2@}</p>

{form $form,'jcommunity~registration:confirm', array()}
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