{if $nbMembers > 0 }
<div class="box">
    <h2>{@havefnubb~main.member.online.today@}</h2>
    <div class="block">
    <ul class="user-online-today">
{foreach $members as $member}
        <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}" title="{$member->login|eschtml}">{$member->login|eschtml}</a>,</li>
{/foreach}
    </ul>
    </div>
</div>
{/if}