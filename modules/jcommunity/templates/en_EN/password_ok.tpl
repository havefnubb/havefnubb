<h2>Activation of a new password</h2>
{if $status == 1}
<p>Your new password is already activated. You can identify yourself on the web site.</p>
{else}
{if $status == 2}
<p>The activation is not possible : the validity of the key has expired. If you want to
retrieve a new password, <a href="{jurl 'jcommunity~password:index'}">ask a new one</a>.</p>
<p>However, you can still authenticate yourself with your old password.</p>
{else}
<p>The new password is now activated, and you are identified on the web site now.</p>
{/if}
{/if}
