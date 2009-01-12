<h2>Activation of your new password</h2>

<p><strong>An email has been sent to you</strong> which contains a key.
In order to confirm the password change. you should indicate the key in the following form,
and give a new password.</p>

{form $form,'jcommunity~password:confirm', array()}
<fieldset>
    <legend>Activation</legend>
    <ul>
    {formcontrols}
    <li>{ctrl_label} : {ctrl_control}</li>
    {/formcontrols}
    </ul>
</fieldset>
<p>{formsubmit}</p>
{/form}

