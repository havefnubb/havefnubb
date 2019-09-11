
<h1>{@hfnuadmin~forum.edit.a.forum@}</h1>
{formfull $form,'hfnuadmin~forum:saveedit'}
<form action="{formurl 'hfnuadmin~forum:saveedit'}" method="post">
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
        <input type="submit" name="validateright" id="jforms_hfnuadmin_forum_validate_saveBt" class="jforms-submit" value="{@hfnuadmin~forum.saveBt@}"/>
    </div>

    </fieldset>
</form>

<form action="{formurl 'hfnuadmin~forum:defaultrights'}" method="post">
    <div>{formurlparam 'hfnuadmin~forum:defaultrights'}
    <input type="hidden" name="id_forum" value="{$forum->id_forum}"/></div>
    <fieldset>
        <legend>{@hfnuadmin~forum.groups.rights@}</legend>
        <div class="jforms-submit-buttons">
            {@hfnuadmin~forum.restorerights.description@}<br/>
            <input type="submit" name="validate" id="jforms_hfnuadmin_forum_restore_rightsBt" class="jforms-submit" value="{@hfnuadmin~forum.restorerightsBt@}"/><br/>
        </div>
    </fieldset>
</form>
