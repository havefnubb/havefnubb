{if $nbMembers > 0 }
<div class="box">
    <h2>{@activeusers~activeusers.member.online.today@}</h2>
    <div class="block">
    {hook 'ActiveUsersOnlineToday'}
    <ul class="user-online-today">
{foreach $members as $member}
        <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}"
        title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
{/foreach}
    </ul>
    </div>
</div>
{/if}
