<div id="loginbox">
{ifuserconnected}
    <h3 class="up-and-down headbox">{@havefnubb~member.identity@}</h3>
    {jlocale 'havefnubb~member.login.welcome', array($login)}

    <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~login:out'}">{@havefnubb~member.login.logout@}</a>,
        <a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">{@havefnubb~member.login.your.account@}</a>)
    </div>
{else}
    <h3 class="up-and-down headbox">{@havefnubb~main.login.connection@}</h3>
    {form $form, 'jcommunity~login:in'}
    <ul>
        <li>
            {ctrl_label 'auth_login'} {ctrl_control 'auth_login'}
        </li>
        <li>
            {ctrl_label 'auth_password'} {ctrl_control 'auth_password'}
        </li>
        {if $persistance_ok}
        <li>
            {ctrl_label 'auth_remember_me'} {ctrl_control 'auth_remember_me'}
        </li>
        {/if}
        <li>
            {formsubmit}
        </li>
        {if $url_return}
            <li><input type="hidden" name="auth_url_return" value="{$url_return|eschtml}" /></li>
        {/if}        
    </ul>    
    {/form}
    <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.login.register@}</a>, 
        <a href="{jurl 'jcommunity~password:index'}">{@havefnubb~member.login.forgotten.password@}</a>)
    </div>
{/ifuserconnected}
</div>