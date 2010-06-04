<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>{if $action == 'view'} > {$category->cat_name|eschtml}{/if}</h3>
</div>
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
        {/if}
        {assign $id_cat = $category->id_cat}
    <div class="category box_title">
        <h3><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h3>
    </div>
        <table class="data_table">
    {/if}
    {ifacl2 'hfnu.forum.list','forum'.$category->id_forum}
    {hook 'hfbCategoryList',array('id_cat'=>$category->id_cat)}
        {if $category->forum_type == 0}
        <tr>
            <td><span class="colleft {post_status 'forum',$category->id_forum}">&nbsp;</span></td>
            <td class="colmain linkincell">
                <h4>
                    <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a>
                    <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{image $j_basepath.'hfnu/images/rss.png',array('alt'=>$category->forum_name)}</a>
                </h4>
                {$category->forum_desc|eschtml}
                {zone 'havefnubb~forumchild',array('id_forum'=>$category->id_forum,'lvl'=>1,'calledFrom'=>'home')}
            </td>
            <td class="colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$category->id_forum)}</td>
            <td class="colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
            {zone 'havefnubb~postlc',array('id_forum'=>$category->id_forum)}</span></td>
        </tr>
        {elseif $category->forum_type == 1}
        <tr>
            <td class="colleft_index colredirect">&nbsp; </td>
            <td class="colmain_index linkincell"><h4 class="forumtitle"><a href="{$category->forum_url}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
            <td class="colstats_index linkincell" colspan="2"> </td>
        </tr>
        {elseif $category->forum_type == 2}
        <tr>
            <td class="colleft_index colrss"> &nbsp;</td>
            <td class="colmain_index linkincell"><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$forum->forum_desc|eschtml}</span></td>
            <td class="colstats_index linkincell" colspan="2">&nbsp;</td>
        </tr>
    {/if}

    {/ifacl2}
        {if $id_forum == 0 or $id_forum != $category->id_forum}
        {assign $id_forum = $category->id_forum}
        {/if}
    {if $nbCat == $i}
        </table>
    {/if}
{/foreach}
{elseif $action == 'view'}
        <table class="data_table">
    {hook 'hfbBeforeForumIndex'}
    {foreach $categories as $category}
    {ifacl2 'hfnu.forum.view','forum'.$category->id_forum}
    {hook 'hfbBeforeForumIndex'}
    {if $category->forum_type == 0}
        <tr>
            <td><span class="colleft {post_status 'forum',$category->id_forum}">&nbsp;</span></td>
            <td class="colmain linkincell">
                <h4>
                <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a>
                <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{image $j_basepath.'hfnu/images/rss.png',array('alt'=>$category->forum_name)}</a>
                </h4>
                {$forum->forum_desc|eschtml}
                {zone 'havefnubb~forumchild',array('id_forum'=>$category->id_forum,'lvl'=>1,'calledFrom'=>'home')}
            </td>
            <td class="colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$category->id_forum)}</td>
            <td class="colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
            {zone 'havefnubb~postlc',array('id_forum'=>$category->id_forum)}</span></td>
        </tr>
    {elseif $category->forum_type == 1}
        <tr>
            <td class="colleft colredirect">&nbsp;</td>
            <td class="colmain linkincell"><h4 class="forumtitle"><a href="{$category->forum_url}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
            <td class="colstats linkincell" colspan="2">&nsbp;</td>
        </tr>
    {elseif $category->forum_type == 2}
        <tr>
            <td class="colleft colrss"> &nbsp;</td>
            <td class="colmain linkincell"><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
            <td class="colstats linkincell" colspan="2">&nbsp;</td>
        </tr>
    {/if}
    {/ifacl2}
    {/foreach}
        </table>
    {hook 'hfbAfterForumIndex'}
{/if}
{hook 'hfbAfterCategoryList'}
