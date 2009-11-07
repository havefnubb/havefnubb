{ifuserconnected}
    {hook 'JcommunityStatusConnected',array('login'=>$login)}
    <ul>
        <li>{@havefnubb~member.status.welcome@} <a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}">{$login}.</a></li>
    </ul>
    <ul>
        <li><a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}">{@havefnubb~member.status.your.account@}</a> -</li>
        <li><a href="{jurl 'jcommunity~login:out'}">{@havefnubb~main.logout@}</a></li>
    </ul>
{/ifuserconnected}
