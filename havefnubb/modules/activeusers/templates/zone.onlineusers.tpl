{if $nbMembers > 0 }
{hook 'hfbBeforeOnline'}
<div class="box">
    <h2>{@activeusers~activeusers.member.currently.online@}</h2>
    <div class="block">
    {hook 'ActiveUsersOnlineUsers'}
    <ul class="user-currently-online">
{foreach $members as $member}
        <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}"
               title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
{/foreach}
    </ul>
    </div>
</div>
{/if}
