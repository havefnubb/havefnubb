<div class="breadcrumb">
<ol>
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>    {if $action == 'view'} &gt; {$cat_name|eschtml}{/if}</li>
</ol>
</div>


<div id="post-message">{jmessage}</div>
{hook 'hfbBeforeCategoryList'}
{if $action == 'index'}

{assign $current_id_cat = 0}

{foreach $forumsList->forumTree as $id_cat=>$category}

    {if $current_id_cat != $id_cat}
        {if $current_id_cat != 0 }
        </table>
        {/if}
        {assign $current_id_cat = $id_cat}
    <div class="category box_title">
    <h2><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$id_cat,'ctitle'=>$category[0])}" title="{$category[0]|eschtml}">{$category[0]|eschtml}</a></h2>
    </div>
        <table class="data_table">
    {/if}

    {foreach $category[1] as $forum} {assign $f = $forum->record}

    {hook 'hfbCategoryList',array('id_cat'=>$id_cat)}
        {if $f->forum_type == 0}
        <tr>
            <td><span class="colleft {post_status 'forum',$f->id_forum}">&nbsp;</span></td>
            <td class="colmain linkincell">
                <h3>
                    <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$f->id_forum,'ftitle'=>$f->forum_name)}" title="{$f->forum_name|eschtml}">{$f->forum_name|eschtml}</a>
                    <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$f->id_forum,'ftitle'=>$f->forum_name)}" title="{@havefnubb~forum.feeds.rss.of.the.forum@}: {$f->forum_name|eschtml}">{image $j_basepath.'hfnu/images/rss.png',array('alt'=>$f->forum_name)}</a>
                </h3>
                {$f->forum_desc|eschtml}
                {assign $firstchild = true}
                {foreach $forum->getLinearIteratorOnChildren() as $child}
                    {if $firstchild}<ul class="subforum-home"><li>{@havefnubb~forum.forumchild.subforum@} :</li>
                        {assign $firstchild = false} {/if}
                    {if $child->record->forum_type != 1}
                    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$child->record->id_forum,'ftitle'=>$child->record->forum_name)}" title="{$child->record->forum_name|eschtml}">{$child->record->forum_name|eschtml}</a>
                        ({if $child->record->id_post}{@havefnubb~main.last.message@}
                        <a href="{jurl 'havefnubb~posts:viewtogo',
                            array('id_post'=>$child->record->id_post,
                                'thread_id'=>$child->record->thread_id,
                                'id_forum'=>$child->record->id_forum,
                                'ftitle'=>$child->record->forum_name,
                                'ptitle'=>$child->record->thread_subject,
                                'go'=>$child->record->id_post)}#p{$child->record->id_post}"
                           title="{@havefnubb~main.goto_this_message@}">{$child->record->date_created|jdatetime:'timestamp':'lang_datetime'}</a> {@havefnubb~main.by@}
                         {if $child->record->nickname == ''}
                            {@havefnubb~member.guest@}
                         {else}
                            <a href="{jurl 'jcommunity~account:show',array('user'=>$child->record->login)}" title="{$child->record->nickname|eschtml}">{$child->record->nickname|eschtml}</a>
                          {/if}
                        {else}
                        {@havefnubb~forum.postlc.no.msg@}
                        {/if})</li>
                    {else}
                    <li><a href="{$child->record->forum_url}" title="{$child->record->forum_name|eschtml}">{$child->record->forum_name|eschtml}</a>,</li>
                    {/if}
                {/foreach}
                {if !$firstchild}</ul>{/if}

            </td>
            <td class="colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$f->id_forum)}</td>
            <td class="colright linkincell"><span class="smalltext">
                {if $f->id_post}<strong>{@havefnubb~main.last.message@}</strong>
                <a href="{jurl 'havefnubb~posts:viewtogo',
                    array('id_post'=>$f->id_post,
                        'thread_id'=>$f->thread_id,
                        'id_forum'=>$f->id_forum,
                        'ftitle'=>$f->forum_name,
                        'ptitle'=>$f->thread_subject,
                        'go'=>$f->id_post)}#p{$f->id_post}"
                   title="{@havefnubb~main.goto_this_message@}">{$f->date_created|jdatetime:'timestamp':'lang_datetime'}</a> {@havefnubb~main.by@}
                 {if $f->nickname == ''}
                    {@havefnubb~member.guest@}
                 {else}
                    <a href="{jurl 'jcommunity~account:show',array('user'=>$f->login)}" title="{$f->nickname|eschtml}">{$f->nickname|eschtml}</a>
                  {/if}
                {else}
                {@havefnubb~forum.postlc.no.msg@}
                {/if}</span></td>
        </tr>
        {elseif $f->forum_type == 1}
        <tr>
            <td class="colleft_index colredirect">&nbsp; </td>
            <td class="colmain_index linkincell"><h3 class="forumtitle"><a href="{$f->forum_url}" title="{$f->forum_name|eschtml}">{$f->forum_name|eschtml}</a></h3>
                <span class="forumdesc">{$f->forum_desc|eschtml}</span></td>
            <td class="colstats_index linkincell" colspan="2"> </td>
        </tr>
        {elseif $f->forum_type == 2}
        <tr>
            <td class="colleft_index colrss"> &nbsp;</td>
            <td class="colmain_index linkincell"><h3><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$f->id_forum,'ftitle'=>$f->forum_name)}" title="{$f->forum_name|eschtml}">{$f->forum_name|eschtml}</a></h3>
                <span class="forumdesc">{$f->forum_desc|eschtml}</span></td>
            <td class="colstats_index linkincell" colspan="2">&nbsp;</td>
        </tr>
    {/if}
    {/foreach}
{/foreach}
    {if $current_id_cat != 0 }
    </table>
    {/if}

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
                <h3>
                <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a>
                <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{image $j_basepath.'hfnu/images/rss.png',array('alt'=>$category->forum_name)}</a>
                </h3>
                {$category->forum_desc|eschtml}
                {zone 'havefnubb~forumchild',array('id_forum'=>$category->id_forum,'lvl'=>1,'calledFrom'=>'home')}
            </td>
            <td class="colstats">{zone 'havefnubb~postandmsg',array('id_forum'=>$category->id_forum)}</td>
            <td class="colright linkincell"><span class="smalltext"><strong>{@havefnubb~main.last.message@}</strong>
            {zone 'havefnubb~postlc',array('id_forum'=>$category->id_forum)}</span></td>
        </tr>
    {elseif $category->forum_type == 1}
        <tr>
            <td class="colleft colredirect">&nbsp;</td>
            <td class="colmain linkincell"><h3 class="forumtitle"><a href="{$category->forum_url}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h3><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
            <td class="colstats linkincell" colspan="2">&nsbp;</td>
        </tr>
    {elseif $category->forum_type == 2}
        <tr>
            <td class="colleft colrss"> &nbsp;</td>
            <td class="colmain linkincell"><h3><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h3><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
            <td class="colstats linkincell" colspan="2">&nbsp;</td>
        </tr>
    {/if}
    {/ifacl2}
    {/foreach}
        </table>
    {hook 'hfbAfterForumIndex'}
{/if}
{hook 'hfbAfterCategoryList'}
