{foreach $categories as $category}
<table class="records-list" width="100%">
<thead>
    <tr>
        <th class="records-list-forum-category" colspan="4">{$category->cat_name|eschtml}</th>
    </tr>
    <tr>
        <th class="records-list-forum-name">{@hfnuadmin~forum.forum_name@}</th>
        <th class="records-list-forum-action" colspan="2">{@hfnuadmin~forum.action@}</th>
        <th class="records-list-forum-order">{@hfnuadmin~forum.forum_order@}</th>
    </tr>
</thead>
<tbody>
{zone 'hfnuadmin~forum',array('id_cat'=>$category->id_cat)}
</tbody>
</table>
{/foreach}
