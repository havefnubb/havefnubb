{if $action == 'index'}
    <div id="breadcrumbtop" class="headbox">
        <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a></h3>
    </div>
{foreach $categories as $category}
    <div class="forumcat">
        <h3><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h3>
    </div>
    {zone 'havefnubb~forum',array('id_cat'=>$category->id_cat)}    
{/foreach}
    {zone 'havefnubb~lastposts'}
    {zone 'havefnubb~stats'}
{elseif $action == 'view'}
    <div id="breadcrumbtop"  class="headbox">
        <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {$category->cat_name|eschtml}</h3>
    </div>
    {zone 'havefnubb~forum',array('id_cat'=>$category->id_cat)}   
{/if}
