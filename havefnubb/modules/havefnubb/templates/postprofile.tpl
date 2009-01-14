<div class="post-author">
    <ul class="member-ident">
        <li class="membername"><a href="{jurl 'profile:view',array('id'=>$user->id)}" title="{@havefnubb~post.view.profile@} {$user->login}">{$user->login}</a></li>
        <li class="memberavatar">{avatar 'images/avatars/'.$user->id}</li>
        <li class="membertown">{@havefnubb~member.town@} : {$user->member_town|eschtml}</li>
        <li class="membertitle"><span>Ici le rang</span></li>        
        <li class="memberstatus"><span>Ici Online/Offline</span></li>
    </ul>
    <ul class="member-info">
        <li class="membersnbposts">{@havefnubb~member.nb.messages@}: {zone 'membernbmsg', array('id_user'=>$user->id)}</li>
        <li class="membercontacts"><span class="memberemail"><a href="mailto:{$user->email}">{@havefnubb~member.email@}</a></span> - <span class="memberwebsite"><a href="{$user->member_website}" title="{@member.website@}">{@member.website@}</a></span>
    </ul>
</div>