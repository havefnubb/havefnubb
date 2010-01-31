{if $childs > 0}
{if $calledFrom == 'home'}
<ul class="subforum-home">
	<li>{@havefnubb~forum.forumchild.subforum@} :</li>
{foreach $forumChilds as $forum}
	{if $forum->forum_type != 1}
	<li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> ({zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}),</li>
	{else}
	<li><a href="{$forum->forum_url}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a>,</li>
	{/if}
{/foreach}
</ul>
{else}
<div class="subforum box_title">
	<h3>{@havefnubb~forum.forumchild.subforum@}</h3>
</div>
<table class="data_table" width="100%">
{foreach $forumChilds as $forum}
{if $forum->forum_type == 0}
	<tr>
		<td class="line colleft {zone 'havefnubb~newestposts',array('id_forum'=>$forum->id_forum)}"></td>
		<td class="line colmain linkincell">
			<h4 class="forumtitle">
				<a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a>
<a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{image $j_basepath.'hfnu/images/rss.png',array('alt'=>$forum->forum_name)}</a>
			</h4>
			<span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
		<td class="line colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$forum->id_forum)}</td>
		<td class="line colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
		{zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}</span></td>
	</tr>
{elseif $forum->forum_type == 1}
	<tr>
		<td class="colleft colredirect"> </td>
		<td class="colmain linkincell"><h4 class="forumtitle"><a href="{$forum->forum_url}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
		<td class="colstats linkincell" colspan="2"> </td>
	</tr>
{elseif $forum->forum_type == 2}
	<tr>
		<td class="colleft colrss"> &nbsp;</td>
		<td class="colmain linkincell"><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
		<td class="colstats linkincell" colspan="2">&nbsp;</td>
	</tr>
{/if}
{/foreach}
</table>
{/if}
{/if}
