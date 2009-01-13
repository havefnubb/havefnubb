<div id="login-status" class="smalltext">
{ifuserconnected}    
    <ul>
        <li>Bienvenue {$login}.<a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">votre compte</a></li>
        <li><a href="{jurl 'jcommunity~login:out'}">déconnexion</a></li>        
    </ul>
{else}

<ul>
    <li>Bienvenue, merci de vous <li><a href="{jurl 'jcommunity~login:index'}">connecter</a></li>
    <li>ou de vous <a href="{jurl 'jcommunity~registration:index'}">inscrire</a></li>
    <li>à moins que vous aillez <a href="{jurl 'jcommunity~password:index'}">oublié votre mot de passe?</a></li>
</ul>
{/ifuserconnected}
</div>

