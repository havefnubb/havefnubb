<h1>{@hfnuadmin~category.the.categories@}</h1>
<ul>
<li><a href="{jurl 'hfnuadmin~category:create'}" title="{@hfnuadmin~category.create.a.category@}">{@hfnuadmin~category.create.a.category@}</a></li>
</ul>
<table width="100%">
    <tr>
        <th class="forumlistcol">{@hfnuadmin~category.cat_order@}</th>
        <th class="forumlistcol">{@hfnuadmin~category.category_name@}</th>        
        <th class="forumlistcol">{@hfnuadmin~category.actions@}</th>
    </tr>
    {foreach $categories as $category}
    <tr>
        <td>{$category->cat_order|eschtml}</td>
        <td><a href="{jurl 'hfnuadmin~category:edit',array('id_cat'=>$category->id_cat)}" title="{@hfnuadmin~category.edit.this.category@}">{$category->cat_name|eschtml}</a></td>        
        <td><a href="{jurl 'hfnuadmin~category:delete',array('id_cat'=>$category->id_cat)}" title="{@hfnuadmin~category.delete.this.category@}" onclick="return confirm('{jLocale 'hfnuadmin~category.confirm.deletion',array($category->cat_name)}')">{@hfnuadmin~category.delete.this.category@}</a> - 
        <a href="{jurl 'hfnuadmin~category:edit',array('id_cat'=>$category->id_cat)}" title="{@hfnuadmin~category.edit.this.category@}">{@hfnuadmin~category.edit.this.category@}</a></td>
    </tr>
    {/foreach}
</table>