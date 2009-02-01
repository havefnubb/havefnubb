<div class="linkpages">
{pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>    

<div class="postlist">
{foreach $posts as $post}
{assign $parent_id = $post->id_post}
{assign $id_forum = $post->id_forum}
<div class="post">
    <div class="posthead">
        <h4 class="posthead-title">{$post->subject|eschtml}</h4>
        <div class="posthead-date">{$post->date_created|jdatetime:'db_datetime':'lang_datetime'} {@havefnubb~main.by@} {$post->login|eschtml}</div>
    </div>
    <div class="postbody">
        <div class="post-author">
            <ul class="member-ident">
                <li class="membername"><a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}" title="{$post->login|eschtml}">{$post->login|eschtml}</a></li>        
                <li class="memberavatar">{avatar 'images/avatars/'.$post->id_user}</li>
                <li class="membertown">{@havefnubb~member.town@} : {$post->member_town|eschtml}</li>
                <li class="membertitle"><span>Ici le rang</span></li>        
                <li class="memberstatus"><span>Ici Online/Offline</span></li>
            </ul>
            <ul class="member-info">
                <li class="membersnbposts">{@havefnubb~member.nb.messages@}: {zone 'havefnubb~membernbmsg', array('id_user'=>$post->id_user)}</li>
                <li class="membercontacts"><span class="memberemail"><a href="mailto:{$post->email}">{@havefnubb~member.email@}</a></span> - <span class="memberwebsite"><a href="{$post->member_website}" title="{@member.website@}">{@member.website@}</a></span></li>
            </ul>
        </div>
        <div class="post-entry">
            <div class="message-content">
                {$post->message|wiki:$wr_engine|stripslashes}
                <div class="signature-content">
                    {if $post->member_comment != ''}<hr/>
                    {$post->member_comment|eschtml|stripslashes}
                    {/if}
                </div>            
            </div>
        </div>        
    </div>
    <div class="postfoot">
        <div class="post-actions">
            {ifacl2 'hfnu.posts.notify'}
            <span class="postnotify"><a href="{jurl 'posts:notify', array('id_post'=>$post->id_post)}" title="{@havefnubb~main.notify@}">{@havefnubb~main.notify@}</a> </span>
             {/ifacl2}
            {ifacl2 'hfnu.posts.delete'}             
            <span class="postdelete"><a href="{jurl 'posts:delete', array('id_post'=>$post->id_post,'id_forum'=>$post->id_forum)}" title="{@havefnubb~main.delete@}" onclick="return confirm({@havefnubb~post.listinpost.confirm.deletion@})">{@havefnubb~main.delete@}</a> </span>
            {/ifacl2}
            {ifacl2 'hfnu.posts.edit'}           
            <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@havefnubb~main.edit@}">{@havefnubb~main.edit@}</a> </span>
            {/ifacl2}
            {ifacl2 'hfnu.posts.quote'}            
            <span class="postquote"><a href="{jurl 'posts:quote' ,array('parent_id'=>$post->parent_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a></span>
            {/ifacl2}
            
        </div>
    </div>
</div>    
{/foreach}
</div>

{ifuserconnected}
{zone 'havefnubb~quickreply',array('id_post'=>$id_post,'id_forum'=>$id_forum)}
{zone 'havefnubb~jumpto',array('id_forum'=>$id_forum)}
{/ifuserconnected}
<div class="linkpages">
{pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
