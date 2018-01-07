<div id="login-status">
{ifuserconnected}
    <h3>{@havefnubb~member.status.welcome@} {$login}</h3>
    <a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}">{@havefnubb~member.status.your.account@}</a> -
    <a href="{jurl 'jcommunity~login:out'}">{@havefnubb~main.logout@}</a>
    {hook 'hfbJcommunityStatusConnected',array('login'=>$login)}
{else}
    <h3>{@havefnubb~member.status.welcome@}</h3>
    {@havefnubb~member.status.welcome.and.thanks.to@} <a href="{jurl 'jcommunity~login:index'}">{@havefnubb~member.status.connect@}</a>
{if $canResetPassword || $canRegister}{@havefnubb~member.status.or.to@}
    {if $canRegister}<a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.status.register@}</a>
        {if $canResetPassword}{@havefnubb~member.status.or.maybe@}{/if}
    {/if}
    {if $canResetPassword}<a href="{jurl 'jcommunity~password:index'}">{@havefnubb~member.status.forgotten.password@} ?</a>{/if}
{/if}
{/ifuserconnected}
</div>
