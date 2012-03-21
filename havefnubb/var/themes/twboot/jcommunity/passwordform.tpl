<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@havefnubb~member.passwordform.header@}</li>
</ul>
<div>
{form $form,'jcommunity~password:send', array()}
<fieldset>
    <legend>{@havefnubb~member.passwordform.header@}</legend>
    <p>{@havefnubb~member.passwordform.description@}</p>
    <div class="clearfix">
        {ctrl_label 'pass_login'} <div class="input">{ctrl_control 'pass_login'}</div>
    </div>
    <div class="clearfix">
        {ctrl_label 'pass_email'} :<div class="input">{ctrl_control 'pass_email'}</div>
    </div>
</fieldset>
<div class="actions">
    <p>{@havefnubb~member.passwordform.mail.description@}</p>
    {formsubmit}
</div>
{/form}
</div>
