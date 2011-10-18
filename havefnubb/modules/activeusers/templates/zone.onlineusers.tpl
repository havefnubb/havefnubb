<div class="box">
    <h3>{@activeusers~activeusers.member.currently.online@}</h3>
    <div class="box-content">
    {hook 'ActiveUsersOnlineUsers'}
    <ul class="user-currently-online">
{foreach $members as $member}
    <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}"
        title="{$member->name|eschtml}">{$member->name|eschtml}</a>,</li>
{/foreach}
{if $nbAnonymous == 1}<li>{jlocale 'activeusers~activeusers.one.anonymous'}</li>
{elseif $nbAnonymous > 1}<li>{jlocale 'activeusers~activeusers.anonymous.number', $nbAnonymous}</li>{/if}
    </ul>
    {if count($bots)}
    <div class="bots-currently-online">Bots:
        <ul>
            {foreach $bots as $bot}<li>{$bot->name|eschtml},</li>{/foreach}
        </ul>
    </div>
    {/if}
    </div>
</div>
