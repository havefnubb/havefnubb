{if $childs > 0}
{if $calledFrom == 'home'}
<ul class="subforum-home">
    <li>{@havefnubb~forum.forumchild.subforum@} :</li>
{foreach $forumChilds as $forum}
    {if $forum->forum_type != 1}
    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> ({zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}), </li>
    {else}
    <li><a href="{$forum->forum_url}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a>,</li>
    {/if}
{/foreach}
</ul>
{else}
<div class="box">
    <h2><strong>{@havefnubb~forum.forumchild.subforum@}</strong></h2>
    <div class="block">
    <table>
{foreach $forumChilds as $forum}
{if $forum->forum_type == 0}
    <tr>
        <td class="{post_status 'forum',$forum->id_forum}"></td>
        <td>
            <h4 class="forumtitle">
                <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a>
                <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{@havefnubb~forum.feeds.rss.of.the.forum@}: {$forum->forum_name|eschtml}">{image 'hfnu/images/rss.png',array('alt'=>$forum->forum_name)}</a>
            </h4>
            <span class="forumdesc">{$forum->forum_desc|eschtml}</span>
        </td>
        <td>{zone 'havefnubb~postandmsg',array('id_forum'=>$forum->id_forum)}</td>
        <td><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
        {zone 'havefnubb~postlc',array('id_forum'=>$forum->id_forum)}</span></td>
    </tr>
{elseif $forum->forum_type == 1}
    <tr>
        <td class="colredirect"> </td>
        <td><h4 class="forumtitle"><a href="{$forum->forum_url}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span> (</td>
        <td colspan="2">&nbsp;</td>
    </tr>
{elseif $forum->forum_type == 2}
    <tr>
        <td class="colrss"> &nbsp;</td>
        <td><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{@havefnubb~forum.feeds.rss.of.the.forum@}: {$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
        <td colspan="2">&nbsp;</td>
    </tr>
{/if}
{/foreach}
    </table>
    </div>
</div>
{/if}
{/if}
