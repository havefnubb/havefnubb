<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> > {$subject|eschtml}</h3>
</div>
{ifacl2 'hfnu.posts.create'}
<div id="post-message">{jmessage}</div>
{/ifacl2}
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<div class="replymessage"><a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post)}" 
title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a></div>
{/ifacl2}
{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}
{zone 'havefnubb~posts_replies',array('id_post'=>$id_post,'page'=>$page)}
{/ifacl2}