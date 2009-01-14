<div id="breadcrumbtop">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@havefnubb~member.members.list@}</h3>
</div>
<div class="linkpages">
{pagelinks 'members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>
    <table width="100%">
        <tr class="memberlistheader">
            <th>{@havefnubb~member.username@}</th>
            <th>{@havefnubb~member.member.since@}</th>        
        </tr>
        {foreach $members as $member}
        <tr>
            <td class="memberlistline"><a href="{jurl 'jcommunity~account:show', array('user'=>$member->login)}" title="{$member->login|eschtml}">{$member->login|eschtml}</a></td>
            <td class="coldate"></td>
        </tr>
        {/foreach}
    </table>
<div class="linkpages">
{pagelinks 'members:list', '',  $nbMembers, $page, $nbMembersPerPage, "page", $properties}
</div>