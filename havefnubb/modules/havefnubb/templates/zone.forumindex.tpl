<table class="{$tableclass}" width="100%">
{foreach $forums as $forum}
    <tr>
        <td class="colleft"></td>
        <td class="colmain linkincell"><h4><a href="{jurl 'havefnubb~posts:lists',array('id'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4>{$forum->forum_desc|eschtml}
        {zone 'havefnubb~forumchild',array('id_forum'=>$forum->id_forum,'lvl'=>1,'calledFrom'=>'home')}        </td>
        <td class="colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$forum->id_forum)}</td>
        <td class="colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
        {zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}</span></td>
    </tr>
{/foreach}
</table>