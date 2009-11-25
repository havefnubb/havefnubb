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
            <label class="jforms-label jforms-required" for="jforms_hfnuadmin_forum_forum_type">{@hfnuadmin~forum.forum_type@}</label>
        </th>
        <td>
            <select name="forum_type">
                <option value="0" {if $forum->forum_type == 0}selected="selected"{/if}>{@hfnuadmin~forum.forum_type.classic@}</option>
                <option value="1" {if $forum->forum_type == 1}selected="selected"{/if}>{@hfnuadmin~forum.forum_type.redirect@}</option>
                <option value="2" {if $forum->forum_type == 2}selected="selected"{/if}>{@hfnuadmin~forum.forum_type.rss@}</option>
            </select>
        </td>
    </tr>        
    <tr>
        <th scope="row">
            <label class="jforms-label" for="jforms_hfnuadmin_forum_forum_url">{@hfnuadmin~forum.forum_url@}</label>
        </th>
        <td>
            <input type="text" name="forum_url" id="jforms_hfnuadmin_forum_forum_url" size="40" value="{$forum->forum_url}"/>
        </td>
    </tr>        

    <tr>
        <th scope="row">
            <label class="jforms-label jforms-required" for="jforms_hfnuadmin_forum_forum_order">{@hfnuadmin~forum.forum_order@}</label>
        </th>
        <td>
            <input type="text" name="forum_order" id="jforms_hfnuadmin_forum_forum_order" class=" jforms-required" size="4" value="{$forum->forum_order}"/>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label class="jforms-label jforms-required" for="jforms_hfnuadmin_forum_post_expire">{@hfnuadmin~forum.forum_expire@}</label>
        </th>
        <td>
            <input type="text" name="post_expire" id="jforms_hfnuadmin_forum_post_expire" class=" jforms-required" size="5" value="{$forum->post_expire}"/><br/>
            {@hfnuadmin~forum.forum_expire_desc@}
        </td>        
    </tr>
</table>

<h2>{@hfnuadmin~forum.group.title@}</h2>
<fieldset><legend>{@hfnuadmin~forum.groups.rights@}</legend>
<div>{formurlparam 'hfnuadmin~forum:saveedit'}
<input type="hidden" name="id_forum" value="{$forum->id_forum}"/>
<input type="hidden" name="hfnutoken" value="{$hfnutoken}"/></div>

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

<form action="{formurl 'hfnuadmin~forum:defaultrights'}" method="post">
<div>{formurlparam 'hfnuadmin~forum:defaultrights'}
<input type="hidden" name="id_forum" value="{$forum->id_forum}"/>
<input type="hidden" name="hfnutoken" value="{$hfnutoken}"/></div>
<fieldset><legend>{@hfnuadmin~forum.groups.rights@}</legend>
<div class="jforms-submit-buttons">
    {@hfnuadmin~forum.restorerights.description@}<br/>
    <input type="submit" name="validate" id="jforms_hfnuadmin_forum_restore_rightsBt" class="jforms-submit" value="{@hfnuadmin~forum.restorerightsBt@}"/><br/>    
</div>
</fieldset>
</form>