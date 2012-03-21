<ul id="markasread">
    <li><a href="{jurl 'havefnubb~forum:mark_all_as_read'}">{@havefnubb~forum.mark.all.forum.as.read@}</a></li>
    {if $currentIdForum > 0}
    <li><a href="{jurl 'havefnubb~forum:mark_forum_as_read',array('id_forum'=>$currentIdForum)}">{@havefnubb~forum.mark.this.forum.as.read@}</a></li>
    {/if}
    <li><a href="{jurl 'havefnubb~posts:shownew'}">{@havefnubb~post.show.new.posts@}</a></li>
    {if $currentIdForum > 0}
    {if $subcribedToThisForum}
    <li><a href="{jurl 'havefnubb~forum:unsubscribe',array('id_forum'=>$currentIdForum)}">{@havefnubb~forum.unsubscribe.to.this.forum@}</a></li>
    {else}
    <li><a href="{jurl 'havefnubb~forum:subscribe',array('id_forum'=>$currentIdForum)}">{@havefnubb~forum.subscribe.to.this.forum@}</a></li>
    {/if}
    {/if}
</ul>
