<div id="login-status">
{ifuserconnected}
    <ul>
        <li>{@havefnubb~member.status.welcome@} {$login}.</li>
        <li><a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}">{@havefnubb~member.status.your.account@}</a></li>
        <li><a href="{jurl 'jcommunity~login:out'}">{@havefnubb~main.logout@}</a></li>
    </ul>    
{else}
<ul>
    <li>{@havefnubb~member.status.welcome.and.thanks.to@} <a href="{jurl 'jcommunity~login:index'}">{@havefnubb~member.status.connect@}</a></li>
    <li>{@havefnubb~member.status.or.to@} <a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.status.register@}</a></li>
    <li>{@havefnubb~member.status.or.maybe@} <a href="{jurl 'jcommunity~password:index'}">{@havefnubb~member.status.forgotten.password@} ?</a></li>
</ul>
{/ifuserconnected}
</div>
