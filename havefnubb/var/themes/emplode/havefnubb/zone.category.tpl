    <div id="breadcrumbtop" class="headbox">
        <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>{if $action == 'view'} > {$category->cat_name|eschtml}{/if}</h3>
    </div>
    <div id="post-message">{jmessage}</div>
{hook 'hfbBeforeCategoryList'}    
{if $action == 'index'}
{foreach $categories as $category}
    <div class="category box_title">
        <h3><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h3>
    </div>
    {ifacl2 'hfnu.forum.list','forum'.$category->id_forum}
    {hook 'hfbCategoryList',array('id_cat'=>$category->id_cat)}
    {zone 'havefnubb~forum',array('action'=>'index','id_cat'=>$category->id_cat)}
    {/ifacl2}
{/foreach}
{elseif $action == 'view'}
    {zone 'havefnubb~forum',array('action'=>'view','id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}
{/if}
{hook 'hfbAfterCategoryList'}