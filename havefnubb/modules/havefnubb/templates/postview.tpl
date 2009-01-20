<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>

<div class="postlist">
{foreach $posts as $post}
<div class="post">
    <div class="posthead">
        <h3>{$post->date_created|jdatetime:'db_datetime':'lang_datetime'} {@havefnubb~main.by@} {zone 'poster', array('id_user'=>$post->id_user)}</h3>
    </div>
    <div class="postbody">
        {zone 'postprofile',array('id_post'=>$post->id_post)}
        <div class="post-entry">
            <h4 class="message-title">{$post->subject|eschtml}</h4>
            <div class="message-content">
            {$post->message|wiki:"wr3_to_xhtml"}
            {zone 'postsignature',array('id_post'=>$post->id_post)}
            </div>
        </div>        
    </div>
    <div class="postfoot">
        <p class="post-actions">
            <span class="postdelete"><a href="{jurl 'posts:delete', array('id_post'=>$post->id_post)}" title="{@main.delete@}">{@havefnubb~main.delete@}</a> | </span>
            <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@main.edit@}">{@havefnubb~main.edit@}</a> | </span>
            <span class="postquote"><a href="{jurl 'posts:quote' ,array('id_post'=>$post->id_post)}" title="{@main.quote@}">{@havefnubb~main.quote@}</a></span>
        </p>
    </div>
</div>    
{/foreach}
</div>
