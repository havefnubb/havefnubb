{if $nbMembers > 0 }
<div class="headings">
    <h3><span>{@activeusers~activeusers.member.currently.online@}</span></h3>
</div>
<div id="currently-online">
{hook 'ActiveUsersOnlineUsers'}
<ul class="user-currently-online">
{assign $nbanonymous = 0}
{foreach $members as $member}
    {if $member->login}
        <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}"
        title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
    {else}{assign $nbanonymous = $nbanonymous + 1}{/if}
{/foreach}
{if $nbanonymous}<li>{jlocale 'activeusers~activeusers.anonymous.number', $nbanonymous}</li>{/if}

</ul>
</div>
{/if}
