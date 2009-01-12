<h2>Profil de {$user->login|eschtml}</h2>
{avatar $j_basepath .'images/avatars/'.$user->id}
{if $himself}
<ul>
    <li><a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">Editer votre profil</a></li>
</ul>
{/if}