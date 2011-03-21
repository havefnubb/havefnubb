
<div class="headings">
    <h3><span>{@activeusers~activeusers.member.online.today@}</span></h3>
</div>
<div id="online-today">
{hook 'ActiveUsersOnlineToday'}
<ul class="user-online-today">
{foreach $members as $member}
    <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}"
        title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
{/foreach}
{if $nbAnonymous == 1}<li>{jlocale 'activeusers~activeusers.one.anonymous'}</li>
{elseif $nbAnonymous > 1}<li>{jlocale 'activeusers~activeusers.anonymous.number', $nbAnonymous}</li>{/if}
</ul>
</div>

