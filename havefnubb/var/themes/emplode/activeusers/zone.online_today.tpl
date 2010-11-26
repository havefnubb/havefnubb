{if $nbMembers > 0 }
<div class="headings">
    <h3><span>{@activeusers~activeusers.member.online.today@}</span></h3>
</div>
<div id="online-today">
{hook 'ActiveUsersOnlineToday'}
<ul class="user-online-today">
{foreach $members as $member}
    <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}" title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
{/foreach}
</ul>
</div>
{/if}
