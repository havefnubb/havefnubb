{ifacl2 'hfnu.posts.view'}
<div class="linkpages">
{pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>    
{/ifacl2}

<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$id_forum}
    {if $parentStatus != 2}
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

<div class="postlist">
{foreach $posts as $post}
{assign $parent_id = $post->parent_id}
{assign $id_forum = $post->id_forum}
{ifacl2 'hfnu.posts.view','forum'.$id_forum}
<div class="post">
    <div class="posthead">       
        <h4 class="posthead-title"><span class="post-status-icon-{$post->status}"> </span><span class="post-status-{$post->status}">[{jlocale 'havefnubb~post.status.'.$post->status}]</span> <a href="{jurl 'havefnubb~posts:view',array('id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'id_post'=>$post->id_post,'parent_id'=>$post->parent_id,'ptitle'=>$post->subject)}" >{$post->subject|eschtml}</a></h4>
        <div class="posthead-date">{$post->date_created|jdatetime:'db_datetime':'lang_datetime'} {@havefnubb~main.by@} {$post->login|eschtml}</div>
        {if $tags !== false}
        <div class="posthead-tags"><ul>{foreach $tags as $t}<li>{$t}</li>{/foreach}</ul></div>
        {/if}        
    </div>
    <div class="postbody">
        {zone 'havefnubb~memberprofile',array('id'=>$post->id_user)}        
        <div class="post-entry">
            <div class="message-content">
                {$post->message|wiki:'wr3_to_xhtml'|stripslashes}
                <div class="signature-content">
                    {if $post->member_comment != ''}<hr/>
                    {$post->member_comment|wiki:'wr3_to_xhtml'|stripslashes}
                    {/if}
                </div>            
            </div>
        </div>        
    </div>
    <div class="postfoot">
        <div class="post-actions">
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
</div>
{/ifacl2}
{/foreach}
</div>
<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$id_forum}
    {if $parentStatus != 2}
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

{ifacl2 'hfnu.posts.view'}
<div class="linkpages">
{pagelinks 'posts:view', array('id_post'=>$id_post),  $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}
{ifacl2 'hfnu.posts.create','forum'.$id_forum}
{zone 'havefnubb~quickreply',array('id_post'=>$id_post,'id_forum'=>$id_forum)}
{/ifacl2}
{ifacl2 'hfnu.forum.goto'}
{zone 'havefnubb~jumpto',array('id_forum'=>$id_forum)}
{/ifacl2}
{ifacl2 'hfnu.admin.post'}
<div class="headings">    
    <h3><span>{@havefnubb~post.status.change.the.status.of.the.post@}</span></h3>
</div>
<fieldset><legend>{@havefnubb~post.status.change.the.status.of.the.post@}</legend>
{formfull $formStatus, 'havefnubb~posts:status',array('parent_id'=>$parent_id)}
</fieldset>
{/ifacl2}