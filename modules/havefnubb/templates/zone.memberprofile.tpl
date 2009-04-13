<div class="post-author">
    <ul class="member-ident">
        <li class="user-name"><a href="{jurl 'jcommunity~account:show',array('user'=>$user->login)}" title="{$user->login|eschtml}">{$user->login|eschtml}</a></li>        
        <li class="user-avatar">{avatar 'images/avatars/'.$user->id}</li>
        <li class="user-town">{@havefnubb~member.town@} : {$user->member_town|eschtml}</li>
        {if $user->member_country != ''}
        <li class="user-country">{image 'images/flags/'.$user->member_country.'.gif'}  {$user->member_country|eschtml}</li>
        {/if}
        <li class="user-rank"><span>{@havefnubb~rank.rank_name@} : {zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}</span></li>        
    </ul>
    <ul class="member-info">
        <li class="user-posts">{@havefnubb~member.nb.messages@}: {$user->nb_msg}</li>
        {if $user->member_show_email == 'Y'}<li class="membercontacts"><span class="user-email"><a href="mailto:{$user->email}">{@havefnubb~member.email@}</a></span> - {/if}<span class="user-website"><a href="{$user->member_website}" title="{@member.website@}">{@member.website@}</a></span></li>
    </ul>
</div>