<div class="box">
    <h2>{@havefnubb~member.passwordform.header@}</h2>
    <div class="block loginbox">
    {form $form,'jcommunity~password:send', array()}
    <fieldset>
        <legend>{@havefnubb~member.passwordform.header@}</legend>
        <p>{@havefnubb~member.passwordform.description@}</p>
        <div>{ctrl_label 'pass_login'} :</div><div>{ctrl_control 'pass_login'}</div>
        <div>{ctrl_label 'pass_email'} :</div><div >{ctrl_control 'pass_email'}</div>
        <p>{@havefnubb~member.passwordform.mail.description@}</p>
        <div class="form_row form_row_submit">{formsubmit}</div>
    </fieldset>
    {/form}
    </div>
</div>
