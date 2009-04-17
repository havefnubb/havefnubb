{ifuserconnected}
<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~member.identity@}</h3>    
</div>
<div id="loginbox">
    <p>{jlocale 'havefnubb~member.login.welcome', array($login)}</p>
    <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~login:out'}">{@havefnubb~member.login.logout@}</a>,
        <a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">{@havefnubb~member.login.your.account@}</a>)
    </div>
</div>
{else}
<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.login.connection@}</h3>
</div>
<div id="loginbox">
    <div id="loginbox-form">
    {form $form, 'jcommunity~login:in'}
<p>            {ctrl_label 'auth_login'} {ctrl_control 'auth_login'} - {ctrl_label 'auth_password'} {ctrl_control 'auth_password'} - 

        {if $persistance_ok}
            {ctrl_label 'auth_remember_me'} {ctrl_control 'auth_remember_me'} - 
        {/if}
        {formsubmit}
        {if $url_return}
            <input type="hidden" name="auth_url_return" value="{$url_return|eschtml}" />
        {/if}        
</p>
    {/form}
    </div>
    <div id="loginbox-links">
        (<a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.login.register@}</a>, 
        <a href="{jurl 'jcommunity~password:index'}">{@havefnubb~member.login.forgotten.password@}</a>)
    </div>
</div>
{/ifuserconnected}