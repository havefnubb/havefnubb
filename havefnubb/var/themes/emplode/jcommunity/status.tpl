
{ifuserconnected}
    <ul>
        <li>{@havefnubb~member.status.welcome@} <a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}">{$login}.</a></li>
    </ul>
    <ul>
        <li><a href="{jurl 'jcommunity~login:out'}">{@havefnubb~main.logout@}</a></li>
    </ul>   
{else}
<ul>
    <li>{@havefnubb~member.status.welcome.and.thanks.to@}
    <a href="{jurl 'jcommunity~login:index'}">{@havefnubb~member.status.connect@}</a></li>
    <li>{@havefnubb~member.status.or.to@} <a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.status.register@}</a></li>
</ul>
{/ifuserconnected}
