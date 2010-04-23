<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{$feed->link}"> {$forum->forum_name|eschtml}</a></h3>
</div>
{if ($feed)}
<div id="hfnu-feeds">
    <h3><a href="{$feed->link}">{$forum->forum_name|eschtml}</a></h3>
    {assign $i=0}
    {foreach $feed->items as $item}
    {if $i < 11}
    <dt><a href="{$item->link}">{$item->title}</a></dt>
    <dd><p><strong>{$item->pubdate}</strong>
    <em>{$item->content}</em><br/>
    {$item->description}</p></dd>
    {assign $i++}
    <hr/>
    {/if}
    {/foreach}
</div>
{/if}
