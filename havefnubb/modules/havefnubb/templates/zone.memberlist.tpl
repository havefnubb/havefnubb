<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@havefnubb~member.memberlist.members.list@}</h3>
</div>
<div class="group">

<form action="{formurl 'havefnubb~members:index'}" method="post">
<fieldset><legend>{@havefnubb~member.memberlist.thegroups@}</legend>
{formurlparam 'havefnubb~members:index'}
    <select name="grpid">
    {foreach $groups as $group}
        {if  $group->id_aclgrp != 0}<option value="{$group->id_aclgrp}">{$group->name}</option>{/if}
    {/foreach}
     </select>
    <input type="submit" value="Rechercher" />
</fieldset>
</form>

</div>
<div class="linkpages">
{pagelinks 'havefnubb~members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>
<table width="100%">
    <tr>
        <th class="memberlistcol">{@havefnubb~member.memberlist.username@}</th>
        <th class="memberlistcol">{@havefnubb~member.common.rank@}</th>
        <th class="memberlistcol">{@havefnubb~member.memberlist.member.since@}</th>
        <th class="memberlistcol">{@havefnubb~member.memberlist.nb.posted.msg@}</th>
    </tr>
    {foreach $members as $member}
    <tr>
        <td class="line linkincell memberlistline">
            <a href="{jurl 'jcommunity~account:show', array('user'=>$member->login)}"
               title="{jlocale 'havefnubb~member.memberlist.profile.of', array($member->login)}">
            {$member->login|eschtml}
            </a>
        </td>
        <td class="line colrank">{zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$member->nb_msg)}</td>
        <td class="line coldate">{$member->request_date|jdatetime:'db_datetime':'lang_datetime'}</td>
        <td class="line colnum">{$member->nb_msg}</td>
    </tr>
    {/foreach}
</table>
<div class="linkpages">
{pagelinks 'havefnubb~members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>