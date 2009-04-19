<div class="headings">
    <h3><span>{@havefnubb~main.member.currently.online@}</span></h3>
</div>
<div id="currently-online">
<p>
<ul class="user-currently-online">   
{foreach $members as $member}
    <li><a href="{jurl 'jcommunity~account:show',array('user'=>$member->login)}" title="{$member->login|eschtml}">{$member->login|eschtml}</a>,</li>
{/foreach}
</ul>
</p>
</div>