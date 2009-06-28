<div id="breadcrumbtop" class="headbox box_title">
    <h3>{@havefnubb~member.registration.account.creation@}</h3>    
</div>
<div id="post-message">{jmessage}</div>
<div id="loginbox">
{form $form,'jcommunity~registration:save', array()}
<fieldset>
    <p>{@havefnubb~member.registration.account.service.description@}</p>
    <div class="legend">{@havefnubb~main.informations@}</div>
    <div class="form_row">
        <div class="form_property">{ctrl_label 'reg_login'} :</div><div class="form_value">{ctrl_control 'reg_login'}</div>
        <div class="clearer">&nbsp;</div>
    </div>        
    <div class="form_row">
        <div class="form_property">{ctrl_label 'reg_email'} :</div><div class="form_value">{ctrl_control 'reg_email'}</div>
        <div class="clearer">&nbsp;</div>
    </div>        
    <div class="form_row">        
        <div class="form_property">{ctrl_label 'reg_captcha'} :</div><div class="form_value">{ctrl_control 'reg_captcha'}</div>        
        <div class="clearer">&nbsp;</div>
    </div>
    <p>{@havefnubb~member.registration.account.mail.description@}</p>
    <div class="form_row form_row_submit">{formsubmit}</div>    
</fieldset>
{/form}
</div>