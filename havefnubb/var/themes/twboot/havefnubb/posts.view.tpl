{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
{jmessage}
{/ifacl2}

{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}

{* pagination *}
{ifacl2 'hfnu.posts.view','forum'.$forum->id_forum}
{hook 'hfbBeforePostReplies',array('id_post'=>$id_post)}

<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$forum->id_cat,'ctitle'=>$forum->cat_name)}" title="{$forum->cat_name}">{$forum->cat_name|eschtml}</a> >> </li>
    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> >> {$subject|eschtml}</li>
</ul>
<div class="pager-posts">
{pagelinks 'posts:view', array('id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$forum->id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}

{assign $i=0}
<div class="row">
<div class="span16 viewposts">
{foreach $posts as $post}
<div class="row">
    <div class="span16 postheading">
    {hook 'hfbPostReplies',array('id_post'=>$id_post)}
    {assign $thread_id = $post->thread_id}
    {assign $id_forum = $post->id_forum}

    {ifacl2 'hfnu.posts.view','forum'.$id_forum}
    {social_networks
        array(  'jurl'=>'havefnubb~posts:view',
                'jurlparams'=>array('id_post'=>$post->thread_id,
                    'thread_id'=>$post->thread_id,
                    'id_forum'=>$post->id_forum,
                    'ftitle'=>$post->forum_name,
                    'ptitle'=>$post->subject),
                'title'=>$post->subject)}
    {* rate ON the FIRST post of the thread *}

    {if $post->id_last_msg == $post->id_post or $post->thread_id == $post->id_post}
            <div class="span3">
            {*zone 'hfnurates~rates' , array('id_source'=>$post->id_post,
            'source'=>'post',
            'return_url'=>'havefnubb~posts:view',
            'return_url_params'=>array('id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle)
            )*}
            </div>
            <div class="offset2 span9">
    {else}
            <div class="offset2 span9">
    {/if}
                <h5><a id="p{$post->id_post}" href="{jurl 'havefnubb~posts:view',array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$post->subject)}#p{$post->id_post}">{if $i >0}<span class="post-reply-idx">{jlocale 'havefnubb~post.reply.number',array('#'.$i)}</span>{/if}
               {$post->p_date_created|jdatetime:'timestamp':'lang_datetime'}
               {@havefnubb~main.by@} {if $post->login == null} {@havefnubb~member.guest@}{else} {$post->nickname|eschtml}{/if}</a></h5>
            </div>
	{if $i == 1}
    {if count($tags) > 1}
            <div class="span2 postheading-tags">
                <ul>{foreach $tags as $t}<li><a href="{jurl 'jtags~default:cloud',array('tag'=>$t)}" title="{@havefnubb~post.show.all.posts.with.this.tag@}">{$t}</a></li>{/foreach}</ul>
            </div>
    {elseif count($tags) == 1 and !empty($tags)}
            <div class="span2 postheading-tags">
                <ul><li><a href="{jurl 'havefnubb~default:cloud',array('tag'=>$tags)}" title="{@havefnubb~post.show.all.posts.with.this.tag@}">{$tags}</a></li></ul>
            </div>
    	{/if}
    {/if}
    </div>
</div>
<div class="row">
    {zone 'havefnubb~memberprofile',array('id'=>$post->id_user)}
    <div class="offset4 postbody">
        {if $statusAvailable[$post->status -1] == 'censored'}
            {@havefnubb~main.censored.reason@} {$post->censored_msg|wiki:'hfb_rule'} {censored_by $post->censored_by}
            {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
            <div class="censor-warning">****{@havefnubb~main.censor.moderator.warning@}*****</div>
            {$post->message|wiki:'hfb_rule'}
            {/ifacl2}
        {else}
            {$post->message|wiki:'hfb_rule'}
        {/if}

        {if $post->member_comment !=''}
        <hr/>
        {$post->member_comment|wiki:'hfb_rule'}
        {/if}
    </div>
</div>
<div class="actions postfooter">
    {hook 'hfbPostRepliesFooter',
                array('action'=>'havefnubb~posts:view',
                      'parms'=>array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle)
                      )
    }
    {ifacl2 'hfnu.posts.reply','forum'.$forum->id_forum}
        {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
    <a class="btn success" href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post,'thread_id'=>$thread_id)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
        {else}
            {ifacl2 'hfnu.admin.post'}
    <a class="btn success" href="{jurl 'havefnubb~posts:reply',array('id_post'=>$id_post,'thread_id'=>$thread_id)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
            {/ifacl2}
        {/if}
    {/ifacl2}
    {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
    <a class="btn info" href="{jurl 'postsmgr:splitTo', array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.split.this.message@}">{@havefnubb~main.split.this.message@}</a>
    {/ifacl2}
    {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
    {if $statusAvailable[$post->status -1] == 'censored'}
    <a class="btn info" href="{jurl 'postsmgr:uncensor', array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.uncensor.this.message@}">{@havefnubb~main.uncensor.this.message@}</a>
    {else}
    <a class="btn danger" href="{jurl 'postsmgr:censor', array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.censor.this.message@}">{@havefnubb~main.censor.this.message@}</a>
    {/if}
    {/ifacl2}
    {ifacl2 'hfnu.posts.delete','forum'.$id_forum}
    <a class="btn danger" href="{jurl 'posts:delete', array('id_post'=>$post->id_post,'id_forum'=>$post->id_forum)}" title="{@havefnubb~main.delete@}" onclick="return confirm({@havefnubb~post.listinpost.confirm.deletion@})">{@havefnubb~main.delete@}</a>
    {/ifacl2}
    {ifacl2 'hfnu.posts.notify','forum'.$id_forum}
    <a class="btn danger" href="{jurl 'postsmgr:notify', array('id_post'=>$post->id_post)}" title="{@havefnubb~main.notify@}">{@havefnubb~main.notify@}</a>
     {/ifacl2}
    {if $post->login == $current_user}
        {ifacl2 'hfnu.posts.edit.own','forum'.$id_forum}
    <a class="btn" href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@havefnubb~main.edit@}">{@havefnubb~main.edit@}</a>
        {/ifacl2}
    {else}
        {ifacl2 'hfnu.posts.edit','forum'.$id_forum}
    <a class="btn" href="{jurl 'posts:edit' ,array('id_post'=>$post->id_post)}" title="{@havefnubb~main.edit@}">{@havefnubb~main.edit@}</a>
        {/ifacl2}
    {/if}
    {ifacl2 'hfnu.posts.create','forum'.$id_forum}
        {if $subscribed}
        <a class="btn" href="{jurl 'posts:unsubscribe' ,array('id_post'=>$thread_id)}" title="{@havefnubb~post.unsubscribe.to.this.post@}">{@havefnubb~post.unsubscribe.to.this.post@}</a>
        {else}
        <a class="btn" href="{jurl 'posts:subscribe' ,array('id_post'=>$thread_id)}" title="{@havefnubb~post.subscribe.to.this.post@}">{@havefnubb~post.subscribe.to.this.post@}</a>
        {/if}
    {/ifacl2}
    {ifacl2 'hfnu.posts.quote','forum'.$id_forum}
        {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
    <a class="btn" href="{jurl 'posts:quote' ,array('thread_id'=>$post->thread_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a>
        {else}
            {ifacl2 'hfnu.admin.post'}
    <a class="btn" href="{jurl 'posts:quote' ,array('thread_id'=>$post->thread_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a>
            {/ifacl2}
        {/if}
    {/ifacl2}
</div> <!-- postfooter -->
    {/ifacl2}
    {assign $i++}
{/foreach}
</div></div>

{ifacl2 'hfnu.posts.view','forum'.$forum->id_forum}
{hook 'hfbAfterPostsReplies',array('id_post'=>$id_post)}
<div class="pager-posts">
{pagelinks 'posts:view', array('id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}


{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
{* 'closed' *}
    {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
        {ifuserconnected}
        {zone 'havefnubb~quickreply',array('connected'=>true,'id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$forum->id_forum)}
        {else}
        {zone 'havefnubb~quickreply',array('connected'=>false,'id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$forum->id_forum)}
        {/ifuserconnected}
    {else}
        {ifacl2 'hfnu.admin.post'}
            {ifuserconnected}
            {zone 'havefnubb~quickreply',array('connected'=>true,'id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$forum->id_forum)}
            {else}
            {zone 'havefnubb~quickreply',array('connected'=>false,'id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$forum->id_forum)}
            {/ifuserconnected}
        {/ifacl2}
    {/if}
{/ifacl2}

{ifacl2 'hfnu.forum.goto'}
    {zone 'havefnubb~jumpto',array('id_forum'=>$forum->id_forum)}
{/ifacl2}

{ifacl2 'hfnu.admin.post'}

<div class="alert-message block-message info" data-alert="alert">
    <a class="close" href="#">×</a>
    <strong>{@havefnubb~post.status.change.the.status.of.the.post@}</strong>
{form $formStatus, 'havefnubb~postsmgr:status',array('thread_id'=>$thread_id)}
    <div class="clearfix">
        {ctrl_label 'status'}
        <div class="input">{ctrl_control 'status'} {formsubmit 'validate'}</div>
    </div>
{/form}
</div>

<div class="alert-message block-message info" data-alert="alert">
    <a class="close" href="#">×</a>
    <strong>{@havefnubb~forum.move.this.thread@}</strong>
{form $formMove, 'havefnubb~postsmgr:moveToForum',array('id_post'=>$id_post,'thread_id'=>$thread_id)}
    <div class="clearfix">
        {ctrl_label 'id_forum'}
        <div class="input">{ctrl_control 'id_forum'} {formsubmit 'validate'}</div>
    </div>
{/form}
</div>
{/ifacl2}

{/ifacl2}{*hfnu.posts.list*}
