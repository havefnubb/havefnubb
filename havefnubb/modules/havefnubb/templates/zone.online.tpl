{if $nbMembers > 0 }
{hook 'BeforeOnline'}
<div class="box">
    <h2>{@havefnubb~main.member.currently.online@}</h2>
    <div class="block">
    {hook 'Online'}
    <ul class="user-currently-online">   
{foreach $members as $member}
        <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}" title="{$member->login|eschtml}">{$member->login|eschtml}</a>,</li>
{/foreach}
    </ul>
    </div>
</div>
{hook 'AfterOline'}
{/if}