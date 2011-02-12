{if $nbMembers > 0 }
<div class="box">
    <h2>{@activeusers~activeusers.member.currently.online@}</h2>
    <div class="block">
    {hook 'ActiveUsersOnlineUsers'}
    <ul class="user-currently-online">
{assign $nbanonymous = 0}
{foreach $members as $member}
    {if $member->login}
        <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}"
        title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
    {else}{assign $nbanonymous = $nbanonymous + 1}{/if}
{/foreach}
{if $nbanonymous == 1}<li>{jlocale 'activeusers~activeusers.one.anonymous'}</li>
{elseif $nbanonymous > 1}<li>{jlocale 'activeusers~activeusers.anonymous.number', $nbanonymous}</li>{/if}
    </ul>
    </div>
</div>
{/if}
