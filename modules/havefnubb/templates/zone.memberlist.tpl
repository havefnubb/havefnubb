<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >
        <span class="user-image" id="user-group">{@havefnubb~member.memberlist.members.list@}</span></h3>
</div>

<div id="group">
    <form action="{formurl 'havefnubb~members:index'}" method="post">
    <div class="member-filter-description">
        <p>{@havefnubb~member.memberlist.filter.description@}</p>
    </div>
    <fieldset>
        <legend>{@havefnubb~member.memberlist.filter@}</legend>
        {@havefnubb~member.memberlist.thegroups@} :
        {formurlparam 'havefnubb~members:index'}
        <select name="grpid">
        {foreach $groups as $group}
            {if  $group->id_aclgrp != 0}<option value="{$group->id_aclgrp}">{$group->name}</option>{/if}
        {/foreach}
         </select>
        -
        {@havefnubb~member.memberlist.initial.nickname@} :
        <select name="letter">
        {foreach $letters as $letter}
            <option value="{$letter}">{$letter}</option>
        {/foreach}
         </select>
        - 
        {@havefnubb~member.memberlist.search.nickname@} : <input type="text"  name="member_search" value="" size="40"/>
        <input type="submit" value="{@havefnubb~member.memberlist.filter@}" />    
    </fieldset>
    
    </form>    
</div>

<div class="linkpages">
{pagelinks 'havefnubb~members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>

<table width="100%">
    <tr>
        <th class="listcol themember">{@havefnubb~member.memberlist.username@}</th>
        <th class="listcol user-rank">{@havefnubb~member.common.rank@}</th>
        <th class="listcol user-since">{@havefnubb~member.memberlist.member.since@}</th>
        <th class="listcol user-posts">{@havefnubb~member.memberlist.nb.posted.msg@}</th>
    </tr>
    {foreach $members as $member}
    <tr>
        <td class="listline linkincell">
            <a href="{jurl 'jcommunity~account:show', array('user'=>$member->login)}"
               title="{jlocale 'havefnubb~member.memberlist.profile.of', array($member->login)}">
            {$member->login|eschtml}
            </a>
        </td>
        <td class="listline colrank">{zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$member->nb_msg)}</td>
        <td class="listline coldate">{$member->request_date|jdatetime:'db_datetime':'lang_datetime'}</td>
        <td class="listline colnum">{$member->nb_msg}</td>
    </tr>
    {/foreach}
</table>

<div class="linkpages">
{pagelinks 'havefnubb~members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>