{if $action == 'index'}
    <div id="breadcrumbtop" class="headbox up-and-down">
        <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a></h3>
    </div>
{foreach $categories as $category}
    <div class="forumcat">
        <h3><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h3>
    </div>
    {ifacl2 'hfnu.forum.list'}
    {zone 'havefnubb~forum',array('id_cat'=>$category->id_cat)}
    {/ifacl2}
{/foreach}
    {zone 'havefnubb~statsinfos'}
{elseif $action == 'view'}
    <div id="breadcrumbtop" class="headbox up-and-down">
        <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {$category->cat_name|eschtml}</h3>
    </div>
    {ifacl2 'hfnu.forum.view'}
    {zone 'havefnubb~forum',array('id_cat'=>$category->id_cat)}
    {/ifacl2}
{/if}
