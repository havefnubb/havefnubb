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

<div class="breadcrumb">
<ol>
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$forum->id_cat,'ctitle'=>$forum->cat_name)}" title="{$forum->cat_name}">{$forum->cat_name|eschtml}</a> >> </li>
    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> >> {$subject|eschtml}</li>
</ol>
</div>

<div class="clear"></div>
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<div id="post-message">{jmessage}</div>
{/ifacl2}

{ifacl2 'hfnu.posts.list','forum'.$forum->id_forum}

<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$forum->id_forum}
    {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
<a href="{jurl 'havefnubb~posts:reply',array('thread_id'=>$thread_id,'id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
    {else}
        {ifacl2 'hfnu.admin.post'}
<a href="{jurl 'havefnubb~posts:reply',array('thread_id'=>$thread_id,'id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
        {/ifacl2}
    {/if}
{/ifacl2}
</div>

{ifacl2 'hfnu.posts.view','forum'.$forum->id_forum}
{hook 'hfbBeforePostReplies',array('id_post'=>$id_post)}
<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$forum->id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
 $nbReplies, $page, $nbRepliesPerPage, "page", $properties}
</div>
{/ifacl2}

<div class="postlist">
{assign $i=0}
{foreach $posts as $post}
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
<div class="post">
    <div class="posthead legend">
        <h4 class="posthead-title"><span class="post-status-icon-{$statusAvailable[$post->status -1]}">&nbsp;</span><span class="post-status-{$statusAvailable[$post->status -1]}">[{jlocale 'havefnubb~post.status.'.$statusAvailable[$post->status -1]}]</span> <a href="{jurl 'havefnubb~posts:view',array('id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name,'id_post'=>$post->id_post,'thread_id'=>$post->thread_id,'ptitle'=>$post->subject)}" >{$post->subject|eschtml}</a>
                {ifuserconnected}
                {if $post->p_date_created > $lastMarkThreadAsRead}
                    <span class="status-post-new">**{@havefnubb~main.common.new@}**</span>
                {/if}
                {/ifuserconnected}
        </h4>
        {* rate ON the FIRST post of the thread *}
        {if $i==0}
        {zone 'hfnurates~rates' , array('id_source'=>$post->id_post,
                                        'source'=>'post',
                                        'return_url'=>'havefnubb~posts:view',
                                        'return_url_params'=>array('id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle)
                                        )}
        {/if}
        <div class="posthead-date">
            <a id="p{$post->id_post}" href="{jurl 'havefnubb~posts:view',array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$post->subject)}#p{$post->id_post}">{if $i >0}<span class="post-reply-idx">{jlocale 'havefnubb~post.reply.number',array('#'.$i)}</span>{/if}
            {$post->p_date_created|jdatetime:'timestamp':'lang_datetime'} {@havefnubb~main.by@} {if $post->login == null} {@havefnubb~member.guest@}{else} {$post->nickname|eschtml}{/if}</a>
        </div>
        {if count($tags) > 1}
        <div class="posthead-tags">
        <ul>{foreach $tags as $t}<li>{$t}</li>{/foreach}</ul>
        </div>
        {elseif count($tags) ==1}
        <div class="posthead-tags">
        <ul><li>{$tags}</li></ul>
        </div>
        {/if}
    </div>
    <div class="postbody">
        {zone 'havefnubb~memberprofile',array('user'=>$post)}
        <div class="post-entry">
            <div class="message-content">
                {if $statusAvailable[$post->status -1] == 'censored'}
                    {@havefnubb~main.censored.reason@} {$post->censored_msg|wiki:'hfb_rule'} {censored_by $post->censored_by}
                    {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
                    <div class="censor-warning">****{@havefnubb~main.censor.moderator.warning@}*****</div>
                    {$post->message|wiki:'hfb_rule'}
                    {/ifacl2}
                {else}
                    {$post->message|wiki:'hfb_rule'}
                {/if}
                <div class="signature-content">
                    {if $post->member_comment != ''}<hr/>
                    {$post->member_comment|wiki:'hfb_rule'}
                    {/if}
                </div>
               </div>
          </div>
     </div>
      <div class="postfoot">
          <div class="post-actions">
            &nbsp;
        {hook 'hfbPostRepliesFooter',
                    array('action'=>'havefnubb~posts:view',
                          'parms'=>array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle)
                          )
        }
          {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
          <span class="postsplit"><a href="{jurl 'postsmgr:splitTo', array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.split.this.message@}">{@havefnubb~main.split.this.message@}</a> </span>
          {/ifacl2}
            {ifacl2 'hfnu.admin.post', 'forum'.$id_forum}
            {if $statusAvailable[$post->status -1] == 'censored'}
            <span class="postcensor"><a href="{jurl 'postsmgr:uncensor', array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.uncensor.this.message@}">{@havefnubb~main.uncensor.this.message@}</a> </span>
            {else}
            <span class="postcensor"><a href="{jurl 'postsmgr:censor', array('id_post'=>$post->id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum)}" title="{@havefnubb~main.censor.this.message@}">{@havefnubb~main.censor.this.message@}</a> </span>
            {/if}
            {/ifacl2}
          {ifacl2 'hfnu.posts.notify','forum'.$id_forum}
          <span class="postnotify"><a href="{jurl 'postsmgr:notify', array('id_post'=>$post->id_post)}" title="{@havefnubb~main.notify@}">{@havefnubb~main.notify@}</a> </span>
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
        {ifacl2 'hfnu.posts.create','forum'.$id_forum}
            {if $subscribed}
            <span class="postsub"><a href="{jurl 'posts:unsubscribe' ,array('thread_id'=>$thread_id)}" title="{@havefnubb~post.unsubscribe.to.this.post@}">{@havefnubb~post.unsubscribe.to.this.post@}</a> </span>
            {else}
            <span class="postsub"><a href="{jurl 'posts:subscribe' ,array('thread_id'=>$thread_id)}" title="{@havefnubb~post.subscribe.to.this.post@}">{@havefnubb~post.subscribe.to.this.post@}</a> </span>
            {/if}
        {/ifacl2}
        {ifacl2 'hfnu.posts.quote','forum'.$id_forum}
            {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
        <span class="postquote"><a href="{jurl 'posts:quote' ,array('thread_id'=>$post->thread_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a></span>
            {else}
                {ifacl2 'hfnu.admin.post'}
        <span class="postquote"><a href="{jurl 'posts:quote' ,array('thread_id'=>$post->thread_id,'id_post'=>$post->id_post)}" title="{@havefnubb~main.quote@}">{@havefnubb~main.quote@}</a></span>
                {/ifacl2}
            {/if}
        {/ifacl2}
          </div>
     </div>
</div>
{/ifacl2}
{assign $i++}
{/foreach}
</div>
<div class="newmessage">
{ifacl2 'hfnu.posts.create','forum'.$forum->id_forum}
<a href="{jurl 'havefnubb~posts:add',array('id_forum'=>$forum->id_forum)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.new.message@}</a>
{/ifacl2}
{ifacl2 'hfnu.posts.reply','forum'.$forum->id_forum}
    {if $status != 'closed' and $status != 'pinedclosed' and $status != 'censored'}
<a href="{jurl 'havefnubb~posts:reply',array('thread_id'=>$thread_id,'id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
    {else}
        {ifacl2 'hfnu.admin.post'}
<a href="{jurl 'havefnubb~posts:reply',array('thread_id'=>$thread_id,'id_post'=>$id_post)}" title="{@havefnubb~forum.forumlist.new.message@}">{@havefnubb~forum.forumlist.reply.message@}</a>
        {/ifacl2}
    {/if}
{/ifacl2}
</div>

{ifacl2 'hfnu.posts.view','forum'.$forum->id_forum}
{hook 'hfbAfterPostsReplies',array('id_post'=>$id_post)}
<div class="pager-posts">
{@havefnubb~main.common.page@}{pagelinks 'posts:view', array('id_post'=>$id_post,'thread_id'=>$thread_id,'id_forum'=>$id_forum,'ftitle'=>$forum_name,'ptitle'=>$ptitle),
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
<div class="headings box_title">
    <h3><span>{@havefnubb~post.status.change.the.status.of.the.post@}</span></h3>
</div>
<div id="post-status">
{form $formStatus, 'havefnubb~postsmgr:status',array('thread_id'=>$thread_id)}
    <div class="form_row">
        <div class="form_property">{ctrl_label 'status'} </div>
        <div class="form_value">
            {ctrl_control 'status'}
        </div>
        <div class="form_value">
        {formsubmit 'validate'}
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
{/form}
</div>
<div class="headings box_title">
    <h3><span>{@havefnubb~forum.move.this.thread@}</span></h3>
</div>
<div id="post-move">
{form $formMove, 'havefnubb~postsmgr:moveToForum',array('id_post'=>$id_post,'thread_id'=>$thread_id)}
    <div class="form_row">
        <div class="form_property">{ctrl_label 'id_forum'} </div>
        <div class="form_value">
            {ctrl_control 'id_forum'}
        </div>
        <div class="form_value">
        {formsubmit 'validate'}
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
{/form}
</div>
{/ifacl2}

{/ifacl2}
