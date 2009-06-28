<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~member.registration.confirmation.activation.of.your.account@}</h3>    
</div>
<div id="profile">
<p>{@havefnubb~member.registration.confirmation.activation.description@}</p>

<p>{@havefnubb~member.registration.confirmation.activation.description.line2@}</p>

{form $form,'jcommunity~registration:confirm', array()}
<fieldset>
    <legend>{@havefnubb~member.registration.confirmation.activation@}</legend>
    {formcontrols}
    <p>{ctrl_label} : {ctrl_control}</p>
    {/formcontrols}
</fieldset>
<p>{formsubmit}</p>
{/form}

</div>