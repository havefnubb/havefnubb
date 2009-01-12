<div id="loginbox">
{ifuserconnected}
    <h2>Identité</h2>
    {$login}, vous êtes déjà connecté.
    <div class="loginbox-links">
        (<a href="{jurl 'jcommunity~login:out'}">déconnexion</a>,
        <a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">votre compte</a>)
    </div>
{else}
    <h2>Connexion</h2>
    <div>        
    {form $form, 'jcommunity~login:in'}
        <fieldset>
            <legend>Connectez vous</legend>        
          
            {ctrl_label 'auth_login'} {ctrl_control 'auth_login'}
            {ctrl_label 'auth_password'} {ctrl_control 'auth_password'}
         
            {if $persistance_ok}
            <div> {ctrl_label 'auth_remember_me'} {ctrl_control 'auth_remember_me'} </div>
            {/if}
            {if $url_return}
            <input type="hidden" name="auth_url_return" value="{$url_return|eschtml}" />
            {/if}

        {formsubmit}
        </fieldset>
    {/form}
        <div class="loginbox-links">
            (<a href="{jurl 'jcommunity~registration:index'}">S'inscrire</a>, 
            <a href="{jurl 'jcommunity~password:index'}">mot de passe oublié</a>)
        </div>
    </div>
{/ifuserconnected}
</div>