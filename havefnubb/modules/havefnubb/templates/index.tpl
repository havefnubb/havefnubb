<div class="breadcrumb">
<ol>
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>    {if $action == 'view'} &gt; {$cat_name|eschtml}{/if}</li>
</ol>
</div>

{zone 'hfnusearch~hfnuquicksearch'}
{ifuserconnected}
{include 'havefnubb~zone.mark_forum'}
{/ifuserconnected}
<div class="clear"></div>
<div id="post-message">{jmessage}</div>
{hook 'hfbBeforeCategoryList'}


{if $action == 'index'}
{assign $adminRights = 0}
{ifacl2 "hfnu.admin.post"}
    {assign $adminRights = 1}
{/ifacl2}
{assign $current_id_cat = 0}

{foreach $forumsList->forumTree as $id_cat=>$category}
    {if $current_id_cat != $id_cat}
        {if $current_id_cat != 0 }
        </table>
    </div>
</div>
        {/if}
        {assign $current_id_cat = $id_cat}
<div class="box">
    <h3><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$id_cat,'ctitle'=>$category[0])}" title="{$category[0]|eschtml}">{$category[0]|eschtml}</a></h3>
    <div class="box-content">
        <table class="forum_home">
    {/if}

    {foreach $category[1] as $forum} {assign $f = $forum->record}

    {hook 'hfbCategoryList',array('id_cat'=>$id_cat)}
    {if $f->forum_type == 0}
            <tr>
                <td class="{post_status 'forum',$f->id_forum}"></td>
                <td>
                    <h4><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$f->id_forum,'ftitle'=>$f->forum_name)}" title="{$f->forum_name|eschtml}">{$f->forum_name|eschtml}</a>
                        <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$f->id_forum,'ftitle'=>$f->forum_name)}" title="{@havefnubb~forum.feeds.rss.of.the.forum@}: {$f->forum_name|eschtml}">{image 'hfnu/images/rss.png',array('alt'=>$f->forum_name)}</a>
                    </h4>
                    <span class="forumdesc">{$f->forum_desc|eschtml}</span>

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
                    {if !$firstchild}</ul>{/if}</td>


                <td>{zone 'havefnubb~postandmsg',array('id_forum'=>$f->id_forum)}</td>
                <td><span class="smalltext">
                {* hidden post ? *}
                {if $f->status == 7}
                    {*  member has the hnfu.admin.post right *}
                    {if  $adminRights == 1}
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
                        {/if}
                    {*  member has not the hnfu.admin.post right *}
                    {else}
                        {@havefnubb~forum.postlc.no.msg@}
                    {/if}
                {* not an hidden post *}
                {else}
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
                    {/if}

                {/if}
                </span></td>
            </tr>
    {elseif $f->forum_type == 1}
            <tr>
                <td class="colredirect"> </td>
                <td><h4 class="forumtitle"><a href="{$f->forum_url}" title="{$f->forum_name|eschtml}">{$f->forum_name|eschtml}</a></h4>
                    <span class="forumdesc">{$f->forum_desc|eschtml}</span></td>
                <td colspan="2">&nbsp;</td>
            </tr>
    {elseif $f->forum_type == 2}
            <tr>
                <td class="colrss"> &nbsp;</td>
                <td><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$f->id_forum,'ftitle'=>$f->forum_name)}" title="{$f->forum_name|eschtml}">{$f->forum_name|eschtml}</a></h4>
                    <span class="forumdesc">{$f->forum_desc|eschtml}</span></td>
                <td colspan="2">&nbsp;</td>
            </tr>
    {/if}
    {/foreach}
{/foreach}
        {if $current_id_cat != 0 }
        </table>
    </div>
</div>
        {/if}

{elseif $action == 'view'}
<div class="box">
    <div class="box-content">
        <table class="forum_category">
{hook 'hfbBeforeForumIndex'}
{foreach $categories as $category}
{ifacl2 'hfnu.forum.view','forum'.$category->id_forum}
{hook 'hfbForumIndex',array('id_forum'=>$category->id_forum)}
        {if $category->forum_type == 0}
            <tr>
                <td class="{post_status 'forum',$category->id_forum}"></td>
                <td>
                    <h4><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a>
                        <a href="{jurl 'havefnubb~posts:rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{@havefnubb~forum.feeds.rss.of.the.forum@}: {$category->forum_name|eschtml}">{image 'hfnu/images/rss.png',array('alt'=>$category->forum_name)}</a>
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
                <td><h4><a href="{jurl 'havefnubb~forum:read_rss',array('id_forum'=>$category->id_forum,'ftitle'=>$category->forum_name)}" title="{@havefnubb~forum.feeds.rss.of.the.forum@}: {$category->forum_name|eschtml}">{$category->forum_name|eschtml}</a></h4><span class="forumdesc">{$category->forum_desc|eschtml}</span></td>
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
    {zone 'activeusers~onlineusers'}
    {zone 'activeusers~online_today'}
</div>

<div class="grid_6 omega">
    {zone "jtags~tagscloud",array('destination'=>'havefnubb~default:cloud', 'maxcount'=>30)}
</div>
<div class="clear"></div>
