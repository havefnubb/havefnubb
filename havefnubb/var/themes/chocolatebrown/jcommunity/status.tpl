<div class="loginbox-connected">
{ifuserconnected}
	<h2 id="login-status">{@havefnubb~member.status.welcome@} {$login}</h2>
	{hook 'hfbJcommunityStatusConnected',array('login'=>$login)}
	<a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}">{@havefnubb~member.status.your.account@}</a> -
	<a href="{jurl 'jcommunity~login:out'}">{@havefnubb~main.logout@}</a>
{else}
	<h2 id="login-status">{@havefnubb~member.status.welcome@}</h2>
	{@havefnubb~member.status.welcome.and.thanks.to@} <a href="{jurl 'jcommunity~login:index'}">{@havefnubb~member.status.connect@}</a>
	{@havefnubb~member.status.or.to@} <a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.status.register@}</a>
	{@havefnubb~member.status.or.maybe@} <a href="{jurl 'jcommunity~password:index'}">{@havefnubb~member.status.forgotten.password@} ?</a>
{/ifuserconnected}
</div>
