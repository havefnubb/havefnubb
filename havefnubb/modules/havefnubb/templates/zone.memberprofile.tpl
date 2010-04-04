{hook 'hfbBeforeMemberProfile',array('user'=>$user->id)}
<div class="post-author">
    <ul class="member-ident">
		{hook 'hfbMemberProfile',array('user'=>$user->id)}
        <li class="user-name user-image">{zone 'online_offline',array('userId'=>$user->id)}<a href="{jurl 'jcommunity~account:show',array('user'=>$user->login)}" title="{jlocale 'havefnubb~member.common.view.the.profile.of',array($user->login)}">{$user->login|eschtml}</a></li>
		{if $user->member_gravatar == 1}
			<li>{gravatar $user->email,array('username'=>$user->login)}</li>
		{else}
			{if file_exists('hfnu/images/avatars/'. $user->id.'.png') }
			<li>{image 'hfnu/images/avatars/'. $user->id.'.png', array('alt'=>$user->login)}</li>
			{elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpg')}
			<li>{image 'hfnu/images/avatars/'. $user->id.'.jpg', array('alt'=>$user->login)}</li>
			{elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpeg')}
			<li>{image 'hfnu/images/avatars/'. $user->id.'.jpeg', array('alt'=>$user->login)}</li>
			{elseif file_exists('hfnu/images/avatars/'. $user->id.'.gif')}
			<li>{image 'hfnu/images/avatars/'. $user->id.'.gif', array('alt'=>$user->login)}</li>
			{/if}
		{/if}
        {if $user->member_town != ''}
        <li class="user-town user-image">{@havefnubb~member.common.town@} : {$user->member_town|eschtml}</li>
        {/if}
        {if $user->member_country != ''}
        <li class="user-country user-image">{image 'hfnu/images/flags/'.strtolower($user->member_country).'.gif',array('alt'=>$user->member_country)}  {country $user->member_country}</li>
        {/if}
        <li class="user-rank user-image"><span>{@havefnubb~rank.rank_name@} : {zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}</span></li>
    </ul>
    <ul class="member-info">
        <li class="user-posts user-image">{@havefnubb~member.common.nb.messages@}: {$user->nb_msg}</li>
        <li class="user-email user-image"><span>{if $user->member_show_email == 'Y'}{mailto array('address'=>$user->email,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}{else}<a href="{jurl 'hfnucontact~default:index',array('to'=>$user->login)}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->login)}">{@havefnubb~member.common.contact.the.member.by.email@}</a>{/if}</span></li>
        {if $user->member_website != ''}<li class="user-website user-image"><span><a href="{$user->member_website}" title="{jlocale 'havefnubb~member.common.website.of',array($user->login)}">{@havefnubb~member.common.website@}</a></span></li>{/if}
    </ul>
</div>
{hook 'hfbAfterMemberProfile',array('user'=>$user->id)}
