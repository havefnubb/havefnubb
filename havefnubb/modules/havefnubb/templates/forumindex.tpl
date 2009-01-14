<table class="{$tableclass}" width="100%">
{foreach $forums as $forum}
    <tr>
        <td class="colleft"></td>
        <td class="colmain"><h4><a href="{jurl 'forum:view',array('id'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4>{$forum->forum_desc|eschtml}</td>
        <td class="colstats">{zone 'postandmsg',array('id_forum'=>$forum->id_forum)}</td>
        <td class="colright"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
        {zone 'postlc',array('id_forum'=>$forum->id_forum)}</span></td>
    </tr>
{/foreach}
</table>