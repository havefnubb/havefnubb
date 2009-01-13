{if $action == 'index'}
    <div id="breadcrumbtop">
        <h3><a href="{jurl 'havefnubb~default:index'}" title="{@main.home@}">{@main.home@}</a></h3>
    </div>
{foreach $categories as $category}
    <div class="forumcat">
        <h3><a href="{jurl 'category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h3>
    </div>
    {zone 'forum',array('id_cat'=>$category->id_cat)}   
{/foreach}
{elseif $action == 'view'}
    <div id="breadcrumbtop">
        <h3><a href="{jurl 'havefnubb~default:index'}" title="{@main.home@}">{@main.home@}</a> > {$category->cat_name|eschtml}</h3>
    </div>
    {zone 'forum',array('id_cat'=>$category->id_cat)}   
{/if}