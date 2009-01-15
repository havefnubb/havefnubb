<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@havefnubb~member.memberlist.members.list@}</h3>
</div>
<div class="linkpages">
{pagelinks 'members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
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
            <td class="memberlistline"><a href="{jurl 'jcommunity~account:show', array('user'=>$member->login)}" title="{$member->login|eschtml}">{$member->login|eschtml}</a></td>
            <td class="colrank"></td>
            <td class="coldate"></td>
            <td class="colnum"></td>
        </tr>
        {/foreach}
    </table>
<div class="linkpages">
{pagelinks 'members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>