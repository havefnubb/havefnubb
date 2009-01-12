<div class="postlist">
{foreach $posts as $post}
<div class="post">
    <div class="posthead">
        <h3>{$post->date_created|jdatetime:'db_datetime':'lang_datetime'} {@main.by@} {zone 'poster', array('id_user'=>$post->id_user)}</h3>
    </div>
    <div class="postbody">
        {zone 'postprofile',array('id_post'=>$post->id_post)}
        <div class="post-entry">
            <h4 class="message-title">{$post->subject|eschtml}</h4>
            <div class="message-content">
            {$post->message|eschtml}
            {zone 'postsignature',array('id_post'=>$post->id_post)}
            </div>
        </div>        
    </div>
    <div class="postfoot">
        <p class="post-actions">
            <span class="postdelete"><a href="{jurl 'post:delete', array('id_post'=>$post->id_post)}" title="{@main.delete@}">{@main.delete@}</a> | </span>
            <span class="postedit"><a href="{jurl 'post:edit' ,array('id_post'=>$post->id_post)}" title="{@main.edit@}">{@main.edit@}</a> | </span>
            <span class="postquote"><a href="{jurl 'post:quote' ,array('id_post'=>$post->id_post)}" title="{@main.quote@}">{@main.quote@}</a></span>
        </p>
    </div>
</div>    
{/foreach}
</div>
