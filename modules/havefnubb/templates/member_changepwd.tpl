<div id="breadcrumbtop" class="headbox">
    <h3 id="user" class="user-image"><a href="{jurl 'jcommunity~account:edit' , array('user'=>$login)}">{@havefnubb~member.edit.account.header@}</a> - <a class="user-private-message user-image" href="{jurl 'havefnubb~members:mail'}" >{@havefnubb~member.internal.messenger@}</a> > <span class="user-edit-password user-image">{@havefnubb~member.pwd.change.of.password@}</span></h3>
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