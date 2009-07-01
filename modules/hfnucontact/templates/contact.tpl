<div id="breadcrumbtop" class="headbox">
    <h3><span>{@hfnucontact~contact.send.an.email@}</span></h3>
</div>
<div id="post-message">{jmessage}</div>
<div id="sendmail">
    {form $form, 'hfnucontact~default:send_a_message'}
    
    <p>{ctrl_label 'subject'} </p>
    <p>{ctrl_control 'subject'} </p>
    <p>{ctrl_label 'message'} </p>
    <p>{ctrl_control 'message'} </p>
    <p>{ctrl_label 'captcha'} </p>
    <p>{ctrl_control 'captcha'} </p>
    
    <div>{formsubmit 'validate'} {formreset 'cancel'}</div>
    {/form}
</div>