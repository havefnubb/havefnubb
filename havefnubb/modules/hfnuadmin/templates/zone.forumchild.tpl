{foreach $forumChildren as $forum}
<tr>
    <td>{$arrow} {$forum->forum_name|eschtml}</td>
    <td><a href="{jurl 'hfnuadmin~forum:delete',array('id_forum'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}" onclick="return confirm('{jlocale 'hfnuadmin~forum.confirm.deletion',array($forum->forum_name)}')">{@hfnuadmin~forum.forum.delete@}</a></td>
    <td><a href="{jurl 'hfnuadmin~forum:edit',array('id_forum'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{@hfnuadmin~forum.forum.edit@}</a></td>
    <td>position :{$forum->forum_order}</td>     
</tr>
{zone 'hfnuadmin~forumchild',array('id_forum'=>$forum->id_forum,'lvl'=>$forum->child_level+1)}
{/foreach}