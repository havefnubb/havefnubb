<div class="postlist">
    <div class="linkpages">
    {pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
    </div>    
{foreach $posts as $post}
<div class="post">
    <div class="posthead">
        <h3>{$post->date_created|jdatetime:'db_datetime':'lang_datetime'} {@havefnubb~main.by@} {zone 'havefnubb~poster', array('id_user'=>$post->id_user)}</h3>
    </div>
    <div class="postbody">
        {zone 'havefnubb~postprofile',array('id_post'=>$post->id_post)}
        <div class="post-entry">
            <h4 class="message-title">{$post->subject|eschtml}</h4>
            <div class="message-content">
            {$post->message|wiki:$wr_engine}
            {zone 'havefnubb~postsignature',array('id_post'=>$post->id_post)}
            </div>
        </div>        
    </div>
    <div class="postfoot">
        <p class="post-actions">
            <span class="postdelete"><a href="{jurl 'posts:delete', array('id_post'=>$post->id_post)}" title="{@main.delete@}">{@havefnubb~main.delete@}</a> | </span>
            <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@main.edit@}">{@havefnubb~main.edit@}</a> | </span>
            <span class="postquote"><a href="{jurl 'posts:quote' ,array('parent_id'=>$post->parent_id,'id_post'=>$post->id_post)}" title="{@main.quote@}">{@havefnubb~main.quote@}</a></span>
        </p>
    </div>
</div>    
{/foreach}
    <div class="linkpages">
    {pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
    </div>
</div>
