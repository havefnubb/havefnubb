<div class="post-author">
    <ul class="member-ident">
        <li class="user-name">{zone 'online_offline',array('userId'=>$user->id)}<a href="{jurl 'jcommunity~account:show',array('user'=>$user->login)}" title="{jlocale 'havefnubb~member.common.view.the.profile.of',array($user->login)}">{$user->login|eschtml}</a></li>        
        <li class="user-avatar">{avatar 'images/avatars/'.$user->id}</li>
        {if $user->member_town != ''}
        <li class="user-town">{@havefnubb~member.common.town@} : {$user->member_town|eschtml}</li>
        {/if}        
        {if $user->member_country != ''}
        <li class="user-country">{image 'images/flags/'.$user->member_country.'.gif',array('alt'=>$user->member_country)}  {$user->member_country|eschtml}</li>
        {/if}
        <li class="user-rank"><span>{@havefnubb~rank.rank_name@} : {zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}</span></li>        
    </ul>
    <ul class="member-info">
        <li class="user-posts">{@havefnubb~member.common.nb.messages@}: {$user->nb_msg}</li>
        <li class="user-email"><span>{if $user->member_show_email == 'Y'}<a href="mailto:{$user->email}">{@havefnubb~member.common.email@}</a>{else}<a href="{jurl 'hfnucontact~default:index',array('to'=>$user->login)}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->login)}">{@havefnubb~member.common.contact.the.member.by.email@}</a>{/if}</span></li>
        {if $user->member_website != ''}<li class="user-website"><span><a href="{$user->member_website}" title="{jlocale 'havefnubb~member.common.website.of',array($user->login)}">{@havefnubb~member.common.website@}</a></span></li>{/if}
    </ul>
</div>