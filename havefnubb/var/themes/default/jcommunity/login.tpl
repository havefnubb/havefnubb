<div id="loginbox">
{ifuserconnected}
    <h3 class="headbox">Identité</h3>
    {$login}, vous êtes déjà connecté.
    <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~login:out'}">déconnexion</a>,
        <a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">votre compte</a>)
    </div>
{else}
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
    <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~registration:index'}">S'inscrire</a>, 
        <a href="{jurl 'jcommunity~password:index'}">mot de passe oublié</a>)
    </div>
{/ifuserconnected}
</div>