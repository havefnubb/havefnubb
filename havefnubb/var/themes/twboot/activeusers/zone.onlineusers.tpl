<div class="span5 box-stats">
    <h3>{@activeusers~activeusers.member.currently.online@}</h3>
    {hook 'ActiveUsersOnlineUsers'}
    <ul class="user-currently-online">
{foreach $members as $member}
    {* display link for members *}
    {if $member->login == $member->name }
    <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}"
        title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
    {* or just display the name of the bot *}
    {else}<li>{$member->login|eschtml},</li>
    {/if}
{/foreach}
{if $nbAnonymous == 1}<li>{jlocale 'activeusers~activeusers.one.anonymous'}</li>
{elseif $nbAnonymous > 1}<li>{jlocale 'activeusers~activeusers.anonymous.number', $nbAnonymous}</li>{/if}
    </ul>
</div>
