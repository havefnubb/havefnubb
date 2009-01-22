<div class="linkpages">
{pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>    

<div class="postlist">
{foreach $posts as $post}
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
                <li class="membercontacts"><span class="memberemail"><a href="mailto:{$post->email}">{@havefnubb~member.email@}</a></span> - <span class="memberwebsite"><a href="{$post->member_website}" title="{@member.website@}">{@member.website@}</a></span>
            </ul>
        </div>
        <div class="post-entry">
            <div class="message-content">
                {$post->message|wiki:$wr_engine}
                <div class="signature-content">
                    {if $post->member_comment != ''}<hr/>
                    {$post->member_comment|eschtml}
                    {/if}
                </div>            
            </div>
        </div>        
    </div>
    <div class="postfoot">
        <div class="post-actions">
            <span class="postdelete"><a href="{jurl 'posts:delete', array('id_post'=>$post->id_post)}" title="{@main.delete@}">{@havefnubb~main.delete@}</a> </span>
            <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@main.edit@}">{@havefnubb~main.edit@}</a> </span>
            <span class="postquote"><a href="{jurl 'posts:quote' ,array('parent_id'=>$post->parent_id,'id_post'=>$post->id_post)}" title="{@main.quote@}">{@havefnubb~main.quote@}</a></span>
        </div>
    </div>
</div>    
{/foreach}
</div>

<div class="linkpages">
{pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
