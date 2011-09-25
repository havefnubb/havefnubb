{hook 'hfbBeforeMembersList'}
<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li><span class="user-image" id="user-group">{@havefnubb~member.memberlist.members.list@}</span></li>
</ul>
<div class="span16">
    <div class="row well">
        <h3>{@havefnubb~member.memberlist.members.list@}</h3>    
        <form class="form-stacked" action="{formurl 'havefnubb~members:index'}" method="post">
            <div class="span16">
            <div class="hidden">{formurlparam 'havefnubb~members:index'}</div>
            <fieldset>
                <div class="row">
                <legend>{@havefnubb~member.memberlist.filter@}</legend>
                <div class="member-filter-description">
                    <p>{@havefnubb~member.memberlist.filter.description@}</p>
                </div>
                </div>
                <div class="row">
                <div class="span5">
                    <label>{@havefnubb~member.memberlist.thegroups@} : </label>
                    <select name="grpid">
                        {foreach $groups as $group}
                            {if  $group->id_aclgrp != 0}<option value="{$group->id_aclgrp}">{$group->name}</option>{/if}
                        {/foreach}
                    </select>
                </div>
                <div class="span5">
                    <label>{@havefnubb~member.memberlist.initial.nickname@} :</label>
                    <select name="letter">
                    {foreach $letters as $letter}
                        <option value="{$letter}">{$letter}</option>
                    {/foreach}
                     </select>
                </div>
                <div class="span5">
                    <label>{@havefnubb~member.memberlist.search.nickname@} : </label>
                    <input type="text"  name="member_search" value="" size="40"/>
                </div>
                </div>
            </fieldset>
            <div class="span16">
                <div class="offset6">
                    <input class="jforms-submit" type="submit" value="{@havefnubb~member.memberlist.filter@}" />
                </div>
            </div>
            </div>
        </form>
    </div>
</div>




<div class="pagination">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~members:index', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>
<table class="zebra-striped">
    <caption>{@havefnubb~member.memberlist.members.list@}</caption>
    <thead>
        <tr>
            <th class="themember">{@havefnubb~member.memberlist.username@} </th>
            <th class="user-rank">{@havefnubb~member.common.rank@}</th>
            <th class="user-since">{@havefnubb~member.memberlist.member.since@}</th>
            <th class="user-posts">{@havefnubb~member.memberlist.nb.posted.msg@}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $members as $member}
    {hook 'hfbMembersList',array('user'=>$member->login)}
    <tr class="{cycle array('odd','even')}">
        <td class="listline linkincell">
            <a href="{jurl 'jcommunity~account:show', array('user'=>$member->login)}"
               title="{jlocale 'havefnubb~member.memberlist.profile.of', array($member->nickname)}">
            {$member->nickname|eschtml}
            </a>
        </td>
        <td class="listline colrank">{zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$member->nb_msg)}</td>
        <td class="listline coldate">{$member->member_created|jdatetime:'db_datetime':'lang_datetime'}</td>
        <td class="listline colnum">{$member->nb_msg}</td>
    </tr>
    {/foreach}
    </tbody>
</table>
{hook 'hfbAfterMembersList'}
<div class="pagination">
{@havefnubb~main.common.page@}{pagelinks 'havefnubb~members:index', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>
