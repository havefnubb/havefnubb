<div id="breadcrumbtop" class="headbox box_title">
    <h3>{@havefnubb~member.password.confirmation.activation.of.your.password@}</h3>    
</div>
<div id="loginbox">
<p>{@havefnubb~member.password.confirmation.activation.description@}</p>

{form $form,'jcommunity~password:confirm', array()}
<fieldset>
    <legend>{@havefnubb~member.registration.confirmation.activation@}</legend>
    <ul>
    {formcontrols}
    <li>{ctrl_label} : {ctrl_control}</li>
    {/formcontrols}
    </ul>

    <div class="form_row form_row_submit">
        <div class="form_value">{formsubmit}</div>
        <div class="clearer">&nbsp;</div>
    </div>
</fieldset>    
{/form}

</div>