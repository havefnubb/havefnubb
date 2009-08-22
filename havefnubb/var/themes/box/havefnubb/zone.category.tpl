<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a>{if $action == 'view'} > {$category->cat_name|eschtml}{/if}</h3>
</div>
<div id="post-message">{jmessage}</div>	
{if $action == 'index'}
{assign $count = 0}
{assign $total = 0}
{foreach $categories as $category}
{assign $total = $total +1}
{if $count == 0}
<div class="two-cols">    
{/if}
    <div class="col">
        <h3 class="category"><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name|eschtml}">{$category->cat_name|eschtml}</a></h3>    
    {ifacl2 'hfnu.forum.list','forum'.$category->id_forum}    
    {zone 'havefnubb~forum',array('action'=>'index','id_cat'=>$category->id_cat)}
    {/ifacl2}
    </div>
{assign $count = $count +1}
{if $count == 2 or $total == $nbCat}
{assign $count = 0}
</div>
<div class="clearboth" > </div>
{/if}
{/foreach}
{zone 'havefnubb~online'}
{zone 'havefnubb~online_today'}
{zone "jtags~tagscloud",array('destination'=>'havefnubb~default:cloud')}
{elseif $action == 'view'}
{zone 'havefnubb~forum',array('action'=>'view','id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}
{/if}
