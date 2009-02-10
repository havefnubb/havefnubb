<div class="post-author">
    <ul class="member-ident">
        <li class="membername"><a href="{jurl 'jcommunity~account:show',array('user'=>$user->login)}" title="{$user->login|eschtml}">{$user->login|eschtml}</a></li>        
        <li class="memberavatar">{avatar 'images/avatars/'.$user->id}</li>
        <li class="membertown">{@havefnubb~member.town@} : {$user->member_town|eschtml}</li>
        <li class="membertitle"><span>{@havefnubb~rank.rank_name@} : {zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}</span></li>        
        <li class="memberstatus"><span>{zone 'havefnubb~online_offline',array('userId'=>$user->id)}</span></li>
    </ul>
    <ul class="member-info">
        <li class="membersnbposts">{@havefnubb~member.nb.messages@}: {$user->nb_msg}</li>
        {if $user->member_show_email == 'Y'}<li class="membercontacts"><span class="memberemail"><a href="mailto:{$user->email}">{@havefnubb~member.email@}</a></span> - {/if}<span class="memberwebsite"><a href="{$user->member_website}" title="{@member.website@}">{@member.website@}</a></span></li>
    </ul>
</div>