{meta_html css  $j_jelixwww.'design/jacl2.css'}

<table class="record-list" width="100%">
<thead>
    <tr>
        <th>{@hfnuadmin~forum.forum_name@}</th>
        <th>{@hfnuadmin~forum.action@}</th>
        <th>{@hfnuadmin~forum.forum_order@}</th>
    </tr>
</thead>
<tbody>
{foreach $forums as $forum}
    <tr>
        <td>{$forum->forum_name|eschtml}</td>
        <td><a href="{jurl 'hfnuadmin~forum:delete',array('id_forum'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}" onclick="return confirm('{jlocale 'hfnuadmin~forum.confirm.deletion',array($forum->forum_name)}')">{@hfnuadmin~forum.forum.delete@}</a></td>
        <td><a href="{jurl 'hfnuadmin~forum:edit',array('id_forum'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{@hfnuadmin~forum.forum.edit@}</a></td>
        <td>position :{$forum->forum_order}</td>
    </tr>
    {zone 'hfnuadmin~forumchild',array('id_forum'=>$forum->id_forum,'lvl'=>$forum->child_level+1)}
{/foreach}
</tbody>
</table>