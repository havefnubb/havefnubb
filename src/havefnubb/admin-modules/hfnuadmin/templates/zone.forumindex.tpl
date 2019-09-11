{assign $line = true}
{foreach $forums as $forum}
    <tr class="{cycle array('odd','even')}">
        <th>{$forum->forum_name|eschtml}</th>
        {ifacl2 'hfnu.admin.forum.delete'}
        <td><a href="{jurl 'hfnuadmin~forum:delete',array('id_forum'=>$forum->id_forum)}" title="{@hfnuadmin~forum.forum.delete@}: {$forum->forum_name|eschtml}" onclick="return confirm('{jlocale 'hfnuadmin~forum.confirm.deletion',array($forum->forum_name)}')">{@hfnuadmin~forum.forum.delete@}</a></td>
        {/ifacl2}
        {ifacl2 'hfnu.admin.forum.edit'}
        <td><a href="{jurl 'hfnuadmin~forum:edit',array('id_forum'=>$forum->id_forum)}" title="{@hfnuadmin~forum.forum.edit@}: {$forum->forum_name|eschtml}">{@hfnuadmin~forum.forum.edit@}</a></td>
        {/ifacl2}
        <td>{$forum->forum_order}</td>
        <td>{$forum->post_expire}</td>
    </tr>
    {zone 'hfnuadmin~forumchild',array('id_forum'=>$forum->id_forum,'lvl'=>$forum->child_level+1)}
{assign $line = !$line}
{/foreach}
