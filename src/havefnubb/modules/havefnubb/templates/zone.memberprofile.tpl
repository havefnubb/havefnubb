{if !$user}
<div class="post-author">
    <ul class="member-ident">
    {hook 'hfbMemberProfile',array('user'=>0)}
        <li class="user-name user-image">{@havefnubb~member.guest@}</li>
        <li class="user-rank user-image"><span>{@havefnubb~rank.rank_name@} : </span></li>
    </ul>
    <ul class="member-info">
        <li class="user-posts user-image">{@havefnubb~member.common.nb.messages@}: 0</li>
    </ul>
</div>
{else}
{hook 'hfbBeforeMemberProfile',array('user'=>$user->id)}
<div class="post-author">
    <ul class="member-ident">
        {hook 'hfbMemberProfile',array('user'=>$user->id)}
        <li class="user-name user-image">{zone 'activeusers~onlinestatus',array('login'=>$user->login)}<a href="{jurl 'jcommunity~account:show',array('user'=>$user->login)}" title="{jlocale 'havefnubb~member.common.view.the.profile.of',array($user->nickname)}">{$user->nickname|eschtml}</a></li>
        {if $user->member_gravatar == 1}
            <li>{gravatar $user->email,array('username'=>$user->login)}</li>
        {else}
            {if file_exists('hfnu/images/avatars/'. $user->id.'.png') }
                <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.png" alt="{$user->login}" />
            {elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpg')}
                <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.jpg" alt="{$user->login}" />
            {elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpeg')}
                <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.jpeg" alt="{$user->login}" />
            {elseif file_exists('hfnu/images/avatars/'. $user->id.'.gif')}
                <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.gif" alt="{$user->login}" />
            {/if}
        {/if}
        {if $user->member_town != ''}
        <li class="user-town user-image">{@havefnubb~member.common.town@} : {$user->member_town|eschtml}</li>
        {/if}
        {if $user->member_country != ''}
        <li class="user-country user-image">
            <img src="{$j_basepath}hfnu/images/flags/{$user->member_country|lower}" alt="{$user->member_country|eschtml}" />
            {country $user->member_country}</li>
        {/if}
        <li class="user-rank user-image"><span>{@havefnubb~rank.rank_name@} : {zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}</span></li>
    </ul>
    <ul class="member-info">
        <li class="user-posts user-image">{@havefnubb~member.common.nb.messages@}: {$user->nb_msg}</li>
        <li class="user-email user-image">
            <span>
            {if $user->member_show_email == 'Y'}{mailto array('address'=>$user->email,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}
            {else}
            <a href="{jurl 'jmessenger~jmessenger:create'}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->nickname)}">{@havefnubb~member.common.contact.the.member.by.email@}</a>
            {/if}</span>
        </li>
        {ifacl2 'hfnu.admin.member'}
        <li class="user-ip user-image">
            {ifacl2 'hfnu.admin.member'}
            {mailto array('address'=>$user->email,'encode'=>'hex','text'=>$user->email)}<br/>
            {/ifacl2}
        </li>
        {/ifacl2}
        {if $user->member_website != ''}<li class="user-website user-image"><span><a href="{$user->member_website}" title="{jlocale 'havefnubb~member.common.website.of',array($user->nickname)}">{@havefnubb~member.common.website@}</a></span></li>{/if}
    </ul>
</div>
{hook 'hfbAfterMemberProfile',array('user'=>$user->id)}
{/if}
