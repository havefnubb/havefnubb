<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>{if $action == 'view'} > {$category->cat_name|eschtml}{/if}</h3>
</div>
<div id="post-message">{jmessage}</div>	
{if $action == 'index'}
{foreach $categories as $category}
<div class="box">
    <h2><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h2>
    <div class="block">
{ifacl2 'hfnu.forum.list','forum'.$category->id_forum}
    {zone 'havefnubb~forum',array('action'=>'index','id_cat'=>$category->id_cat)}
{/ifacl2}
    </div>
</div>
{/foreach}
{elseif $action == 'view'}
    {zone 'havefnubb~forum',array('action'=>'view','id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}
{/if}

<div class="grid_5 alpha">
    {zone 'havefnubb~lastposts'}
    {zone 'havefnubb~stats'}		
</div>

<div class="grid_5">
    {zone 'havefnubb~online'}
    {zone 'havefnubb~online_today'}
</div>

<div class="grid_6 omega">
    {zone 'hfnusearch~hfnuquicksearch'}
    {zone "jtags~tagscloud",array('destination'=>'havefnubb~default:cloud')}    					
</div>
<div class="clear"></div>
