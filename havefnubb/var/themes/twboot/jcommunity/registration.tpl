<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@havefnubb~member.registration.account.creation@}</li>
</ul>
<div id="post-message">{jmessage}</div>
<div>
{form $form,'jcommunity~registration:save', array()}
    <fieldset>
    <legend>{@havefnubb~main.informations@}</legend>
    <p>{@havefnubb~member.registration.account.service.description@}</p>
    <div class="clearfix">
        {ctrl_label 'reg_login'} :<div class="input">{ctrl_control 'reg_login'}</div>
    </div>
    <div class="clearfix">
        {ctrl_label 'reg_email'} :<div class="input">{ctrl_control 'reg_email'}</div>
    </div>
    <div class="clearfix">
        {ctrl_label 'reg_captcha'} :<div class="input">{ctrl_control 'reg_captcha'}</div>
    </div>
    </fieldset>
    <div class="actions">
        <p>{@havefnubb~member.registration.account.mail.description@}</p>
        {formsubmit}
    </div>
{/form}
</div>
