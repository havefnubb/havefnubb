<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> > {$subject|eschtml}</h3>
    {ifacl2 'hfnu.posts.reply'}
    <div class="replymessage"><a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post)}" 
    title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a></div>
    {/ifacl2}
</div>
{jMessage}
{zone 'postlistinpost',array('id_post'=>$id_post,'page'=>$page)}
