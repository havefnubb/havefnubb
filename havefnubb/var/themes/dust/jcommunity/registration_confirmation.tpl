<div class="box">
    <h2>{@havefnubb~member.registration.confirmation.activation.of.your.account@}</h2>
    <div class="block">
    {form $form,'jcommunity~registration:confirm', array()}
    <fieldset>
        <legend>{@havefnubb~member.registration.confirmation.activation@}</legend>
        <p>{@havefnubb~member.registration.confirmation.activation.description@}</p>
        <p>{@havefnubb~member.registration.confirmation.activation.description.line2@}</p>
        {formcontrols}
        <div>{ctrl_label} :</div><div>{ctrl_control}</div>
        {/formcontrols}
        <div>{formsubmit}</div>
    </fieldset>
    {/form}
    </div>
</div>
