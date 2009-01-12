<div id="login-status">
{ifuserconnected}
    {$login}, you are connected.
    (<a href="{jurl 'jcommunity~login:out'}">Logout</a>,
     <a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">Your account</a>)
{else}
    Not connected.
    <a href="{jurl 'jcommunity~login:index'}">Login</a>,
    <a href="{jurl 'jcommunity~registration:index'}">Register</a>,
    <a href="{jurl 'jcommunity~password:index'}">Forgotten password</a>
{/ifuserconnected}
</div>
