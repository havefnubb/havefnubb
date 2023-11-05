<div id="login-status">
{ifuserconnected}
    <p id="welcome">{@havefnubb~member.status.welcome@} {$login}</p>
    <a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">{@havefnubb~member.status.your.account@}</a> -
    <a href="{jurl 'jmessenger~jmessenger:index'}">{@havefnubb~member.private.messaging@}</a> -
    <a href="{jurl 'jcommunity~login:out'}">{@havefnubb~main.logout@}</a>
    {hook 'hfbJcommunityStatusConnected',array('login'=>$login)}
{else}
    <p id="welcome">{@havefnubb~member.status.welcome@}</p>
    {@havefnubb~member.status.welcome.and.thanks.to@} <a href="{jurl 'jcommunity~login:index'}">{@havefnubb~member.status.connect@}</a>
    {if $canResetPassword || $canRegister}{@havefnubb~member.status.or.to@}
        {if $canRegister}<a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.status.register@}</a>
            {if $canResetPassword}{@havefnubb~member.status.or.maybe@}{/if}
        {/if}
        {if $canResetPassword}<a href="{jurl 'jcommunity~password_reset:index'}">{@havefnubb~member.status.forgotten.password@} ?</a>{/if}
    {/if}
{/ifuserconnected}
</div>
