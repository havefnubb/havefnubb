{if $nbMembers > 0 }
<div class="headings">
    <h3><span>{@activeusers~activeusers.member.online.today@}</span></h3>
</div>
<div id="online-today">
{hook 'ActiveUsersOnlineToday'}
<ul class="user-online-today">
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
{/if}
