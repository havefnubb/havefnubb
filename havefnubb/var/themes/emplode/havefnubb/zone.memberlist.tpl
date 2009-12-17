{hook 'hfbBeforeMembersList'}
<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >
        <span class="user-image" id="user-group">{@havefnubb~member.memberlist.members.list@}</span></h3>
</div>

<div id="group">
    <form action="{formurl 'havefnubb~members:index'}" method="post">
    {formurlparam 'havefnubb~members:index'}        
    <fieldset>        
        <div class="member-filter-description form_row">
            <p>{@havefnubb~member.memberlist.filter.description@}</p>
        </div>    
        <div class="legend"><h3>{@havefnubb~member.memberlist.filter@}</h3></div>
        <div class="form_row">
            <div class="form_property">{@havefnubb~member.memberlist.thegroups@} : </div>
            <div class="form_value">
                <select name="grpid">
                {foreach $groups as $group}
                    {if  $group->id_aclgrp != 0}<option value="{$group->id_aclgrp}">{$group->name}</option>{/if}
                {/foreach}
                 </select>
            </div>
            <div class="clearer">&nbsp;</div>
        </div>
        <div class="form_row">
            <div class="form_property">{@havefnubb~member.memberlist.initial.nickname@} : </div>
            <div class="form_value">
                <select name="letter">
                {foreach $letters as $letter}
                    <option value="{$letter}">{$letter}</option>
                {/foreach}
                 </select>
            </div>
            <div class="clearer">&nbsp;</div>
        </div>
        <div class="form_row">
            <div class="form_property">{@havefnubb~member.memberlist.search.nickname@} : </div>
            <div class="form_value">
                <input type="text"  name="member_search" value="" size="40"/>
            </div>
            <div class="clearer">&nbsp;</div>
        </div>    
        <div class="form_row form_row_submit">    
            <div class="form_value">        
                <input class="button" type="submit" value="{@havefnubb~member.memberlist.filter@}" />
            </div>
            <div class="clearer">&nbsp;</div>
        </div>

    </fieldset>
    
    </form>    
</div>

<div class="linkpages">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~members:index', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>

<table class="data_table" width="100%">
    <tr>
        <th class="listcol themember">{@havefnubb~member.memberlist.username@}</th>
        <th class="listcol user-rank">{@havefnubb~member.common.rank@}</th>
        <th class="listcol user-since">{@havefnubb~member.memberlist.member.since@}</th>
        <th class="listcol user-posts">{@havefnubb~member.memberlist.nb.posted.msg@}</th>
    </tr>
    {foreach $members as $member}
    {hook 'hfbMembersList',array('user'=>$member->login)}
    <tr>
        <td class="listline linkincell">
            <a href="{jurl 'jcommunity~account:show', array('user'=>$member->login)}"
               title="{jlocale 'havefnubb~member.memberlist.profile.of', array($member->login)}">
            {$member->login|eschtml}
            </a>
        </td>
        <td class="listline colrank">{zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$member->nb_msg)}</td>
        <td class="listline coldate">{$member->member_created|jdatetime:'db_datetime':'lang_datetime'}</td>
        <td class="listline colnum">{$member->nb_msg}</td>
    </tr>
    {/foreach}
</table>
{hook 'hfbAfterMembersList'}
<div class="linkpages">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~members:index', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>