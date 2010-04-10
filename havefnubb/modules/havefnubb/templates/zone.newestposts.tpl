{if $display == 'icon'}
{$postStatus}
{else}
<span class="newestposts">{$postStatus}</span>
<a class="status-{$statusCss}" href="{jurl 'havefnubb~posts:view',
            array(  'id_post'=>$post->parent_id,
                    'parent_id'=>$post->parent_id,
                    'id_forum'=>$post->id_forum,
                    'ftitle'=>$post->forum_name,
                    'ptitle'=>$post->subject)}"
   title="{@havefnubb~forum.forumlist.view.this.subject@}">{$post->subject|eschtml}</a>
{/if}
