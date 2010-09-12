{if $nbMembers > 0 }
{hook 'hfbBeforeOnlineToday'}
<div class="box">
    <h2>{@havefnubb~main.member.online.today@}</h2>
    <div class="block">
    {hook 'hfbOnlineToday'}
    <ul class="user-online-today">
{foreach $members as $member}
        <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}" title="{$member->nickname|eschtml}">{$member->nickname|eschtml}</a>,</li>
{/foreach}
    </ul>
    </div>
</div>
{hook 'hfbAfterOnlineToday'}
{/if}
