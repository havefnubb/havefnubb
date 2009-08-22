{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/include/jquery.include.js'}
{meta_html css $j_themepath.'css/toggleElements.css'}
{meta_html js $j_jelixwww.'jquery/jquery.toggleElements.pack.js'}
{literal}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){ 
     $('div.toggler-c').toggleElements( 
          { fxAnimation:'slide', fxSpeed:'slow', className:'toggler' } );
     $('div.toggler-quickreply').toggleElements( { fxAnimation:'show', fxSpeed:'slow', className:'toggler' } );    
}); 
//]]>
</script>
{/literal}
<div class="newmessage">
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
<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}

<div class="postlist">
{foreach $posts as $post}
{assign $parent_id = $post->parent_id}
{assign $id_forum = $post->id_forum}
{ifacl2 'hfnu.posts.view','forum'.$id_forum}
<div class="post toggler-c opened" title="[{jlocale 'havefnubb~post.status.'.$post->status}] {$post->subject|eschtml} - {@havefnubb~main.by@} {$post->login|eschtml}">
    <div class="posthead">                
        <h4 class="posthead-title"><span class="post-status-icon-{$post->status}"> </span><span class="post-status-{$post->status}">[{jlocale 'havefnubb~post.status.'.$post->status}]</span> <a href="{jurl 'havefnubb~posts:view',array('id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'id_post'=>$post->id_post,'parent_id'=>$post->parent_id,'ptitle'=>$post->subject)}" >{$post->subject|eschtml}</a></h4>
        <div class="posthead-date">{$post->date_created|jdatetime:'timestamp':'lang_datetime'} {@havefnubb~main.by@} {$post->login|eschtml}</div>
        {if count($tags) > 0}
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
        {* rate ON the FIRST post of the thread *}
        {if $post->parent_id == $post->id_post}
        {zone 'hfnurates~rates' , array('id_source'=>$post->id_post,
                                        'source'=>'post',
                                        'return_url'=>'posts:view',
                                        'return_url_params'=>array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle)
                                        )}
        {/if}        
        <div class="post-actions">
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
</div>
{/ifacl2}
{/foreach}
</div>
<div class="newmessage">
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
<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'parent_id'=>$parent_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
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
<div id="post-status">
{form $formStatus, 'havefnubb~posts:status',array('parent_id'=>$parent_id)}
<p>{ctrl_label 'status'} : {ctrl_control 'status'} {formsubmit 'validate'}</p>
{/form}
</div>
<div class="headings">    
    <h3><span>{@havefnubb~forum.move.this.thread@}</span></h3>
</div>
<div id="post-move">
{form $formMove, 'havefnubb~posts:moveToForum',array('id_post'=>$id_post)}
<p>{ctrl_label 'id_forum'} : {ctrl_control 'id_forum'} {formsubmit 'validate'}</p>
{/form}
</div>
{/ifacl2}
