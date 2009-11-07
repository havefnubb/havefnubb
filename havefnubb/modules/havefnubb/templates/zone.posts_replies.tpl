<div class="fake-button-left grid_8 alpha">&nbsp;
{ifacl2 'hfnu.posts.create','forum'.$id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$id_forum}
    {if $parentStatus != "closed" and $parentStatus != 'pinedclosed'}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
    {else}
        {foreach $groups as $group}
            {if $group->id_aclgrp == 1 or $group->id_aclgrp == 3}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
            {/if}
        {/foreach}    
    {/if}
{/ifacl2}
</div>

{ifacl2 'hfnu.posts.view','forum'.$id_forum}
{hook 'BeforePostReplies',array('id_post'=>$id_post)}
<div class="pager-posts grid_8 omega">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}
<div class="clear"></div>
{foreach $posts as $post}
{hook 'PostReplies',array('id_post'=>$id_post)}
{assign $parent_id = $post->parent_id}
{assign $id_forum = $post->id_forum}
{ifacl2 'hfnu.posts.view','forum'.$id_forum}
<div class="box">
    <h2>{$post->subject|eschtml}</h2>
    <div class="block">
        {* rate ON the FIRST post of the thread *}
        <div class="grid_4">
        {if $post->parent_id == $post->id_post}        
        {zone 'hfnurates~rates' , array('id_source'=>$post->id_post,
                                        'source'=>'post',
                                        'return_url'=>'havefnubb~posts:view',
                                        'return_url_params'=>array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle) 
                                        )}
        {/if}&nbsp;
        </div>              
        <div class="grid_8 postheading">
        <h5>{$post->date_created|jdatetime:'timestamp':'lang_datetime'} {@havefnubb~main.by@} {$post->login|eschtml}</h5>
        </div>
        {if count($tags) > 0}
        <div class="grid_2 postheading-tags">
        <ul>{foreach $tags as $t}<li>{$t}</li>{/foreach}</ul>
        </div>
        {/if}
        <div class="clear"></div>
        <div class="grid_4">
        {zone 'havefnubb~memberprofile',array('id'=>$post->id_user)}        
        </div>
        <div class="grid_12 postbody">
        {$post->message|wiki:'wr3_to_xhtml'|stripslashes}
        </div>
        <div class="clear"></div>
        <div class="prefix_4 grid_12">
        {if $post->member_comment != ''}<hr/>
        {$post->member_comment|wiki:'wr3_to_xhtml'|stripslashes}
        {/if}
        </div>
        <div class="clear"></div>
    </div>
    <div class="postfoot fake-button">
       &nbsp;
       {zone 'hfnucontact~send_to_friend', array('action'=>'havefnubb~posts:view','parms'=>array('id_post'=>$post->id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum))}
        {ifacl2 'hfnu.admin.post', 'forum'.$id_froum}            
        <span class="postsplit"><a href="{jurl 'posts:splitTo', array('id_post'=>$post->id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.split.this.message@}">{@havefnubb~main.split.this.message@}</a> </span>
        {/ifacl2}
        {ifacl2 'hfnu.posts.notify','forum'.$id_forum}
        <span class="postnotify"><a href="{jurl 'posts:notify', array('id_post'=>$post->id_post)}" title="{@havefnubb~main.notify@}">{@havefnubb~main.notify@}</a> </span>
         {/ifacl2}
        {ifacl2 'hfnu.posts.delete','forum'.$id_forum}
        <span class="postdelete"><a href="{jurl 'posts:delete', array('id_post'=>$post->id_post,'id_forum'=>$post->id_forum)}" title="{@havefnubb~main.delete@}" onclick="return confirm({@havefnubb~post.listinpost.confirm.deletion@})">{@havefnubb~main.delete@}</a> </span>
        {/ifacl2}
        {if $post->login == $current_user}
            {ifacl2 'hfnu.posts.edit.own','forum'.$id_forum}
        <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@havefnubb~main.edit@}">{@havefnubb~main.edit@}</a> </span>
            {/ifacl2}
        {else}
            {ifacl2 'hfnu.posts.edit','forum'.$id_forum}
        <span class="postedit"><a href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@havefnubb~main.edit@}">{@havefnubb~main.edit@}</a> </span>
            {/ifacl2}
        {/if}
        {ifacl2 'hfnu.posts.quote','forum'.$id_forum}
        <span class="postquote"><a href="{jurl 'posts:quote' ,array('parent_id'=>$post->parent_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a></span>
        {/ifacl2}            
    </div>
</div>
{/ifacl2}
{/foreach}

<div class="fake-button-left grid_8 alpha">&nbsp;
{ifacl2 'hfnu.posts.create','forum'.$id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$id_forum}
    {if $parentStatus != "closed" and $parentStatus != 'pinedclosed'}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
    {else}
        {foreach $groups as $group}
            {if $group->id_aclgrp == 1 or $group->id_aclgrp == 3}
<a href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
            {/if}
        {/foreach}    
    {/if}
{/ifacl2}
</div>

{ifacl2 'hfnu.posts.view','forum'.$id_forum}
{hook 'AfterPostsReplies',array('id_post'=>$id_post)}
<div class="pager-posts grid_8 omega">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}
<div class="clear"></div>
{ifacl2 'hfnu.posts.create','forum'.$id_forum}
{zone 'havefnubb~quickreply',array('id_post'=>$id_post,'id_forum'=>$id_forum)}
{/ifacl2}
{ifacl2 'hfnu.forum.goto'}
{zone 'havefnubb~jumpto',array('id_forum'=>$id_forum)}
{/ifacl2}
{ifacl2 'hfnu.admin.post'}
<div class="box">
    <h2>{@havefnubb~post.status.change.the.status.of.the.post@}</h2>
{form $formStatus, 'havefnubb~posts:status',array('parent_id'=>$parent_id)}
    <div class="block">
    {ctrl_label 'status'} {ctrl_control 'status'} {formsubmit 'validate'} 
    </div>    
{/form}
</div>    
<div class="box">
    <h2>{@havefnubb~forum.move.this.thread@}</h2>
{form $formMove, 'havefnubb~posts:moveToForum',array('id_post'=>$id_post)}    
    <div class="block">
    {ctrl_label 'id_forum'} {ctrl_control 'id_forum'} {formsubmit 'validate'}
    </div>
{/form}    
</div>
{/ifacl2}
