<div id="post-message">{jmessage}</div>
<div class="box">
    <h2>{@hfnucontact~contact.send.an.email@}</h2>
    <div class="block">
    {form $form, 'hfnucontact~default:send_a_message'}
    <fieldset>
    <legend>{@hfnucontact~contact.send.an.email@}</legend>    
    <p>
    {ctrl_label 'subject'}<br/>
    {ctrl_control 'subject'}
    </p>
    <p>
    {ctrl_label 'message'}<br/> 
    {ctrl_control 'message'}
    </p>
    <p>      
    {ctrl_label 'captcha'} <br/>
    {ctrl_control 'captcha'}
    </p>
    {formsubmit 'validate'} {formreset 'cancel'}
    </fieldset>
    {/form}
    </div>
</div>