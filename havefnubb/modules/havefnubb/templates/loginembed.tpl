{ifuserconnected}
{else}
{assign $class="three-cols"}
<div id="loginembed">
    <h3 class="headbox">{@havefnubb~main.login.connection@}</h3>
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
            <input type="hidden" name="auth_url_return" value="{$url_return|eschtml}" />
        {/if}        
    </ul>    
    {/form}
</div>
{/ifuserconnected}


