
{ifuserconnected}
    <p>{$login}, vous êtes identifié.</p>

    <p>Vous pouvez :</p>
    <ul>
        <li><a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">voir votre profil</a></li>
        <li><a href="{jurl 'jcommunity~login:out'}">vous déconnecter</a></a></li>
    </ul>

{else}
    <p>Vous n'êtes pas authentifié.</p>

    <p>Vous pouvez :</p>
    <ul>
        <li><a href="{jurl 'jcommunity~login:index'}">Vous identifier</a></li>
        <li><a href="{jurl 'jcommunity~registration:index'}">Créer un compte</a></li>
        <li><a href="{jurl 'jcommunity~password:index'}">Récupérer un mot de passe</a>  si vous ne vous souvenez plus du votre.</li>
    </ul>
{/ifuserconnected}

