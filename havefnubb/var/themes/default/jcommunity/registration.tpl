<div class="box">
    <h3>{@havefnubb~member.registration.account.creation@}</h3>
    <div class="box-content">
        <div id="post-message">{jmessage}</div>
        {form $form,'jcommunity~registration:save', array()}
        <fieldset>
            <p>{@havefnubb~member.registration.account.service.description@}</p>
            <legend>{@havefnubb~main.informations@}</legend>
            <div>{ctrl_label 'reg_login'} :</div><div>{ctrl_control 'reg_login'}</div>
            <div>{ctrl_label 'reg_email'} :</div><div>{ctrl_control 'reg_email'}</div>
            <div>{ctrl_label 'reg_captcha'} :</div><div>{ctrl_control 'reg_captcha'}</div>
            <p>{@havefnubb~member.registration.account.mail.description@}</p>
            <div >{formsubmit}</div>
        </fieldset>
        {/form}
    </div>
</div>
