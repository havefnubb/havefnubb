<div class="box">
    <h3>{@activeusers~activeusers.member.online.today@}</h3>
    <div class="box-content">
    {hook 'ActiveUsersOnlineToday'}
    <ul class="user-online-today">
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
</div>
