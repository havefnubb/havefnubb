{if $nbMembers > 0 }
{hook 'hfbBeforeOnline'}
<div class="headings">
    <h3><span>{@havefnubb~main.member.currently.online@}</span></h3>
</div>
<div id="currently-online">
{hook 'hfbOnline'}
<ul class="user-currently-online">   
{foreach $members as $member}
    <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}" title="{$member->login|eschtml}">{$member->login|eschtml}</a>,</li>
{/foreach}
</ul>
</div>
{hook 'hfbAfterOline'}
{/if}