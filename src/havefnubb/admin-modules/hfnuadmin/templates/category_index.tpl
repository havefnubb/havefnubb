{include 'hlp_category_index'}
<h1>{@hfnuadmin~category.the.categories@}</h1>
{ifacl2 'hfnu.admin.category.create'}
<h2>{@hfnuadmin~category.create.a.category@}</h2>
{formfull $form, 'hfnuadmin~category:savecreate'}
<br/>
{@hfnuadmin~category.create.a.category.description@}
{/ifacl2}
<h2>{@hfnuadmin~category.list.of.categories@}</h2>
{ifacl2 'hfnu.admin.category.edit'}
<form action="{formurl 'hfnuadmin~category:saveedit'}" method="post">
{/ifacl2}
<table width="100%" class="records-list">
    <thead>
        <tr>
            <th class="records-list-category-order">{@hfnuadmin~category.cat_order@}</th>
            <th class="records-list-category-name">{@hfnuadmin~category.category_name@}</th>
    {ifacl2 'hfnu.admin.category.edit'}
            <th class="records-list-category-action">{@hfnuadmin~category.actions@}</th>
    {/ifacl2}
        </tr>
    </thead>
    <tbody>
        {assign $line = true}
        {foreach $categories as $category}
        {ifacl2 'hfnu.admin.category.edit'}
        <tr class="{if $line}odd{else}even{/if}">
            <td><span class="jforms-required-star">*</span> <input type="text" size="4" name="cat_order[{$category->id_cat}]" value="{$category->cat_order}" /></td>
            <td><span class="jforms-required-star">*</span> <input type="text" size="40" name="cat_name[{$category->id_cat}]" value="{$category->cat_name}" /></td>
            <td><input type="hidden" name="id_cat[{$category->id_cat}]" value="{$category->id_cat}" />{ifacl2 'hfnu.admin.category.delete'}<a href="{jurl 'hfnuadmin~category:delete',array('id_cat'=>$category->id_cat)}" title="{@hfnuadmin~category.delete.this.category@}" onclick="return confirm('{jlocale 'hfnuadmin~rank.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a>{/ifacl2}</td>
        </tr>
        {else}
        <tr>
            <td>{$category->cat_order}</td>
            <td>{$category->cat_name|eschtml}</td>
        </tr>
        {/ifacl2}
        {assign $line = !$line}
        {/foreach}
    </tbody>
</table>
{ifacl2 'hfnu.admin.category.edit'}
<div class="jforms-submit-buttons">
{formurlparam 'hfnuadmin~category:saveedit'}
    <input type="submit" name="saveBt" id="jforms_hfnuadmin_category_validate_saveBt2" class="jforms-submit" value="{@hfnuadmin~category.saveBt@}"/>
    <input type="hidden" name="hfnutoken" value="{$hfnutoken}"/>
</div>
</form>
{/ifacl2}
