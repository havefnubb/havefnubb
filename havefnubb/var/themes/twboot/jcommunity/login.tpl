{ifuserconnected}
<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@havefnubb~member.identity@}</li>
</ul>
<div>
    <p>{jlocale 'havefnubb~member.login.welcome', array($login)}
    (<a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">{@havefnubb~member.login.your.account@}</a>,
    <a href="{jurl 'jcommunity~login:out'}">{@havefnubb~member.login.logout@}</a>)
    </p>
    </div>
</div>
{else}
<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@havefnubb~main.login.connection@}</li>
</ul>
<div>
    {form $form, 'jcommunity~login:in'}
    <fieldset>
        <legend>{@havefnubb~main.login.connection@}</legend>
        <div class="clearfix">
            {ctrl_label 'auth_login'}<div class="input">{ctrl_control 'auth_login'}</div>
        </div>
        <div class="clearfix">
            {ctrl_label 'auth_password'}<div class="input">{ctrl_control 'auth_password'}</div>
        </div>
        {if $persistance_ok}
        <div class="clearfix">
            {ctrl_label 'auth_remember_me'}<div class="input">{ctrl_control 'auth_remember_me'}</div>
        </div>
        {/if}
    </fieldset>
    <div class="actions">
        {formsubmit}
        {if $canRegister}- <a class="btn info" href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.login.register@}</a>{/if}
        {if $canResetPassword}- <a class="btn danger" href="{jurl 'jcommunity~password:index'}">{@havefnubb~member.login.forgotten.password@}</a>{/if}
    </div>
    {if $url_return}
        <input type="hidden" name="auth_url_return" value="{$url_return|eschtml}" />
    {/if}
    {/form}
</div>
{/ifuserconnected}
