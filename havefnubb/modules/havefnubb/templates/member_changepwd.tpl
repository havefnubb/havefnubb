<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'jcommunity~account:edit' , array('user'=>$login)}">{@havefnubb~member.edit.account.header@}</a> - <a href="{jurl 'havefnubb~members:mail'}" >{@havefnubb~member.internal.messenger@}</a></h3>
</div>
<div id="profile">
{form $form,'havefnubb~members:savenewpwd', array('user'=>$login)}
<fieldset>
    <legend>{@havefnubb~member.pwd.change.your.password@}</legend>
    {formcontrols}
    <p>{ctrl_label} : {ctrl_control}</p>
    {/formcontrols}
</fieldset>
<p>{formsubmit}</p>
{/form}
</div>