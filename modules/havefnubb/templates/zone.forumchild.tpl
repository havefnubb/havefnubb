{if $childs > 0}
{if $calledFrom == 'home'}
<ul class="subforum-home">
    <li>{@havefnubb~forum.forumchild.subforum@} :</li>
{foreach $forumChilds as $forum}
    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></li>
{/foreach}
</ul>
{else}
<div class="subforum">
<h3>{@havefnubb~forum.forumchild.subforum@}</h3>
</div>
<table class="forumList" width="100%">
{foreach $forumChilds as $forum}
    <tr>
        <td class="line colleft forumicone"></td>
        <td class="line colmain linkincell"><h4><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4>{$forum->forum_desc|eschtml}</td>
        <td class="line colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$forum->id_forum)}</td>
        <td class="line colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
        {zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}</span></td>
    </tr>
{/foreach}
</table>
{/if}
{/if}