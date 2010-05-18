<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>{if $action == 'view'} > {$category->cat_name|eschtml}{/if}</h3>
</div>
{zone 'hfnusearch~hfnuquicksearch'}
{ifuserconnected}
{zone 'havefnubb~mark_forum',array('currentIdForum'=>$currentIdForum)}
{/ifuserconnected}
<div class="clear"></div>
<div id="post-message">{jmessage}</div>
{hook 'hfbBeforeCategoryList'}
{if $action == 'index'}
{assign $id_cat = 0}
{assign $id_forum = 0}
{assign $i = 0}
{assign $nbCat = count($categories)}
{foreach $categories as $category}
{assign $i++}
    {if $id_cat == 0 or $id_cat != $category->id_cat}
        {if $i > 1 }
        </table>
    </div>
</div>
        {/if}
        {assign $id_cat = $category->id_cat}
<div class="box">
    <h2><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h2>
    <div class="block">
        <table>
    {/if}

{ifacl2 'hfnu.forum.list','forum'.$category->id_forum}
    {hook 'hfbCategoryList',array('id_cat'=>$category->id_cat)}
    {if $category->forum_type == 0}
            <tr>
                <td class="{zone 'havefnubb~newestposts',array('source'=>'forum','id_forum'=>$category->id_forum,'display'=>'icon')}"></td>
                <td>
                    <h4><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a>
                        <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{image $j_basepath.'hfnu/images/rss.png',array('alt'=>$category->forum_name)}</a>
                    </h4>{$category->forum_desc|eschtml}
                {zone 'havefnubb~forumchild',array('id_forum'=>$category->id_forum,'lvl'=>1,'calledFrom'=>'home')}</td>
                <td>{zone 'havefnubb~postandmsg',array('id_forum'=>$category->id_forum)}</td>
                <td><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
                {zone 'havefnubb~postlc',array('id_forum'=>$category->id_forum)}</span></td>
            </tr>
    {elseif $category->forum_type == 1}
            <tr>
                <td class="colredirect"> </td>
                <td><h4 class="forumtitle"><a href="{$category->forum_url}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
                <td colspan="2">&nbsp;</td>
            </tr>
    {elseif $category->forum_type == 2}
            <tr>
                <td class="colrss"> &nbsp;</td>
                <td><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
                <td colspan="2">&nbsp;</td>
            </tr>
    {/if}
{/ifacl2}
        {if $id_forum == 0 or $id_forum != $category->id_forum}
        {assign $id_forum = $category->id_forum}
        {/if}
    {if $nbCat == $i}
        </table>
    </div>
</div>
    {/if}
{/foreach}
{elseif $action == 'view'}
<div class="box">
    <div class="block">
        <table>
{hook 'hfbBeforeForumIndex'}
{foreach $categories as $category}
{ifacl2 'hfnu.forum.view','forum'.$category->id_forum}
{hook 'hfbForumIndex',array('id_forum'=>$category->id_forum)}
        {if $category->forum_type == 0}
            <tr>
                <td class="{zone 'havefnubb~newestposts',array('source'=>'forum','id_forum'=>$category->id_forum,'display'=>'icon')}"></td>
                <td>
                    <h4><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a>
                        <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{image $j_basepath.'hfnu/images/rss.png',array('alt'=>$category->forum_name)}</a>
                    </h4>{$category->forum_desc|eschtml}
                {zone 'havefnubb~forumchild',array('id_forum'=>$category->id_forum,'lvl'=>1,'calledFrom'=>'home')}</td>
                <td>{zone 'havefnubb~postandmsg',array('id_forum'=>$category->id_forum)}</td>
                <td><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
                {zone 'havefnubb~postlc',array('id_forum'=>$category->id_forum)}</span></td>
            </tr>
        {elseif $category->forum_type == 1}
            <tr>
                <td class="colredirect"> </td>
                <td><h4 class="forumtitle"><a href="{$category->forum_url}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
                <td colspan="2">&nbsp;</td>
            </tr>
        {elseif $category->forum_type == 2}
            <tr>
                <td class="colrss"> &nbsp;</td>
                <td><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
                <td colspan="2">&nbsp;</td>
            </tr>
        {/if}
{/ifacl2}
{/foreach}
        </table>
    </div>
</div>
{hook 'hfbAfterForumIndex'}
{/if}
{hook 'hfbAfterCategoryList'}
<div class="grid_5 alpha">
    {zone 'havefnubb~lastposts'}
    {zone 'havefnubb~stats'}
</div>

<div class="grid_5">
    {zone 'havefnubb~online'}
    {zone 'havefnubb~online_today'}
</div>

<div class="grid_6 omega">
    {zone "jtags~tagscloud",array('destination'=>'havefnubb~default:cloud')}
</div>
<div class="clear"></div>
