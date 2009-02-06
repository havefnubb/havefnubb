{meta_html css  $j_jelixwww.'design/jacl2.css'}
{meta_html css  $j_jelixwww.'design/jform.css'}

<h1>{@hfnuadmin~forum.edit.a.forum@}</h1>
<form action="{formurl 'hfnuadmin~forum:saveedit'}" method="post">
<table class="jforms-table" border="0">
    <tr>
        <th scope="row">
            <label class="jforms-label jforms-required" for="jforms_hfnuadmin_forum_forum_name">{@hfnuadmin~forum.forum_name@}</label>
        </th>
        <td>
            <input type="text" name="forum_name" id="jforms_hfnuadmin_forum_forum_name" class=" jforms-required" size="40" value="{$forum->forum_name}"/>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label class="jforms-label jforms-required" for="jforms_hfnuadmin_forum_forum_desc">{@hfnuadmin~forum.forum_desc@}</label>
        </th>
        <td>
            <textarea rows="5" cols="40" name="forum_desc" id="jforms_hfnuadmin_forum_desc" class=" jforms-required">{$forum->forum_desc}</textarea>
        </td>        
    </tr>
    <tr>
        <th scope="row">
            <label class="jforms-label jforms-required" for="jforms_hfnuadmin_forum_forum_order">{@hfnuadmin~forum.forum_order@}</label>
        </th>
        <td>
            <input type="text" name="forum_order" id="jforms_hfnuadmin_forum_forum_order" class=" jforms-required" size="4" value="1"/>
        </td>
    </tr>
</table>

<h2>{@hfnuadmin~forum.group.title@}</h2>
<fieldset><legend>{@hfnuadmin~forum.groups.rights@}</legend>
<div>{formurlparam 'hfnuadmin~forum:saveedit'}
<input type="hidden" name="id_forum" value="{$forum->id_forum}"/></div>

<table class="records-list jacl2-list">
<thead>
    <tr>
        <th></th>
    {foreach $groups as $group}
        <th>{$group->name}</th>
    {/foreach}
    </tr>
</thead>
<tbody>
{assign $line = true}
    {foreach $rights as $subject=>$right}
    <tr class="{if $line}odd{else}even{/if}">
        <th>{jlocale "hfnuadmin~forum.".$subject}</th>
        {foreach $right as $group=>$r}
        <td><input type="checkbox" name="rights[{$group}][{$subject}]" {if $r}checked="checked"{/if} /></td>
        {/foreach}
    </tr>
    {assign $line = !$line}
    {/foreach}
</tbody>
</table>

<div class="jforms-submit-buttons">
    <input type="submit" name="validate" id="jforms_hfnuadmin_forum_validate_saveBt" class="jforms-submit" value="{@hfnuadmin~forum.saveBt@}"/>
</div>

</fieldset>
</form>
