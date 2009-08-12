{if $childs > 0}
{if $calledFrom == 'home'}
<ul class="subforum-home">
    <li>{@havefnubb~forum.forumchild.subforum@} :</li>
{foreach $forumChilds as $forum}
    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a>,</li>
{/foreach}
</ul>
{else}
<div class="box">
    <h2><strong>{@havefnubb~forum.forumchild.subforum@}</strong></h2>
</div>
<div class="box">
    <div class="block">        
    <table>
{foreach $forumChilds as $forum}
    <tr>
        <td class="{zone 'havefnubb~newestposts',array('id_forum'=>$forum->id_forum)}"></td>
        <td><h4 class="forumtitle"><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
        <td>{zone 'havefnubb~postandmsg',array('id_forum'=>$forum->id_forum)}</td>
        <td><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
        {zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}</span></td>
    </tr>
{/foreach}
    </table>
    </div>
</div>    
{/if}
{/if}