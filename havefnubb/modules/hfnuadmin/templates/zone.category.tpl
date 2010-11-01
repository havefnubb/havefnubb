<h2>{@hfnuadmin~forum.forum.list@}</h2>
{assign $action=''}
{foreach $categories as $category}
{ifacl2 'hfnu.admin.forum.edit'}
{assign $action='ok'}
{/ifacl2}
{ifacl2 'hfnu.admin.forum.delete'}
{assign $action='ok'}
{/ifacl2}
<table class="records-list" width="100%">
    <thead>
        <tr>
            <th class="records-list-forum-category" colspan="5">{$category->cat_name|eschtml}</th>
        </tr>
        <tr>
            <th class="records-list-forum-name">{@hfnuadmin~forum.forum_name@}</th>
            {if $action=='ok'}
            <th class="records-list-forum-action" colspan="2">{@hfnuadmin~forum.action@}</th>
            {/if}
            <th class="records-list-forum-order">{@hfnuadmin~forum.forum_order@}</th>
            <th class="records-list-forum-order">{@hfnuadmin~forum.forum_expire@}</th>
        </tr>
    </thead>
<tbody>
    {zone 'hfnuadmin~forum',array('id_cat'=>$category->id_cat)}
</tbody>
</table>
{/foreach}
