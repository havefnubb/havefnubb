<h1>{@hfnuadmin~category.the.categories@}</h1>
{ifacl2 'hfnu.admin.category.create'}
<h2>{@hfnuadmin~category.create.a.category@}</h2>
{formfull $form, 'hfnuadmin~category:savecreate'}
<br/>
{@hfnuadmin~category.create.a.category.description@}
{/ifacl2}
<h2>{@hfnuadmin~category.list.of.categories@}</h2>
<table width="100%">
    <tr>
        <th>{@hfnuadmin~category.cat_order@}</th>
        <th>{@hfnuadmin~category.category_name@}</th>  
{ifacl2 'hfnu.admin.category.edit'}       
        <th>{@hfnuadmin~category.actions@}</th>
{/ifacl2}        
    </tr>    
    {foreach $categories as $category}
    {ifacl2 'hfnu.admin.category.edit'}
    {zone 'hfnuadmin~category_edit_inline',array('id_cat'=>$category->id_cat)}
    {else}
    <tr>
        <td>{$category->cat_order}</td>
        <td>{$category->cat_name|eschtml}</td>
    </tr>
    {/ifacl2}
    {/foreach}
</table>