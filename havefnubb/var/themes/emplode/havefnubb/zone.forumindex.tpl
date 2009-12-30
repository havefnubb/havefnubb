<table class="data_table" width="100%">
{hook 'hfbBeforeForumIndex'}
{foreach $forums as $forum}
{if $action =='view'}
{ifacl2 'hfnu.forum.view','forum'.$forum->id_forum}
{hook 'hfbForumIndex',array('id_forum'=>$forum->id_forum)}
{if $forum->forum_type == 0}
	<tr>
		<td class="colleft {zone 'havefnubb~newestposts',array('source'=>'forum','id_forum'=>$forum->id_forum)}"></td>
		<td class="colmain linkincell"><h4><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4>{$forum->forum_desc|eschtml}
		{zone 'havefnubb~forumchild',array('id_forum'=>$forum->id_forum,'lvl'=>1,'calledFrom'=>'home')}</td>
		<td class="colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$forum->id_forum)}</td>
		<td class="colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
		{zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}</span></td>
	</tr>
{elseif $forum->forum_type == 1}
	<tr>
		<td class="colleft colredirect"> </td>
		<td class="colmain linkincell"><h4 class="forumtitle"><a href="{$forum->forum_url}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
		<td class="colstats linkincell"> </td>
	</tr>
{elseif $forum->forum_type == 2}
	<tr>
		<td class="colleft colrss"> &nbsp;</td>
		<td class="colmain linkincell"><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
		<td class="colstats linkincell">&nbsp;</td>
	</tr>
{/if}
{/ifacl2}
{elseif $action =='index'}
{ifacl2 'hfnu.forum.list','forum'.$forum->id_forum}
{if $forum->forum_type == 0}
	<tr>
		<td class="colleft {zone 'havefnubb~newestposts',array('source'=>'forum','id_forum'=>$forum->id_forum)}"></td>
		<td class="colmain linkincell"><h4 class="forumtitle"><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span>
		{zone 'havefnubb~forumchild',array('id_forum'=>$forum->id_forum,'lvl'=>1,'calledFrom'=>'home')}</td>
		<td class="colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$forum->id_forum)}</td>
		<td class="colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
		{zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}</span></td>
	</tr>
{elseif $forum->forum_type == 1}
	<tr>
		<td class="colleft_index colredirect"> </td>
		<td class="colmain_index linkincell"><h4 class="forumtitle"><a href="{$forum->forum_url}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
		<td class="colstats_index linkincell"> </td>
	</tr>
{elseif $forum->forum_type == 2}
	<tr>
		<td class="colleft_index colrss"> &nbsp;</td>
		<td class="colmain_index linkincell"><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
		<td class="colstats_index linkincell">&nbsp;</td>
		<td class="colright_index"></td>
	</tr>
{/if}
{/ifacl2}
{/if}
{/foreach}
{hook 'hfbAfterForumIndex'}
</table>
