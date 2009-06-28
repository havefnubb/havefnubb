<div id="breadcrumbtop" class="headbox box_title">
    <h3>{@havefnubb~member.registration.confirmation.activation.of.your.account@}</h3>    
</div>
<div id="profile">
{form $form,'jcommunity~registration:confirm', array()}
<fieldset>
    <p>{@havefnubb~member.registration.confirmation.activation.description@}</p>
    
    <p>{@havefnubb~member.registration.confirmation.activation.description.line2@}</p>
    
    <div class="legend">{@havefnubb~member.registration.confirmation.activation@}</div>
    {formcontrols}
    <div class="form_row">
        <div class="form_property">{ctrl_label} :</div><div class="form_value">{ctrl_control}</div>
        <div class="clearer">&nbsp;</div>
    </div>    
    {/formcontrols}
    <div class="form_row form_row_submit">{formsubmit}</div> 
</fieldset>
{/form}
</div>