<div id="login-status">
{ifuserconnected}
    Welcome {$login}
    <ul>
        <li><a href="{jurl 'jcommunity~login:out'}">Logout</a></li>
        <li><a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">Your account</a></li>
    </ul>
{else}
    You are not logged in.
    <ul>
        <li><a href="{jurl 'jcommunity~login:index'}">Login</a></li>
        <li><a href="{jurl 'jcommunity~registration:index'}">Register</a></li>
        <li><a href="{jurl 'jcommunity~password:index'}">Forgotten password</a></li>
    </ul>
{/ifuserconnected}
</div>
