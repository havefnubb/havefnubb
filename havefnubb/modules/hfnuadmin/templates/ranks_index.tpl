<h1>{@havefnubb~rank.the.ranks@}</h1>
{ifacl2 'hfnu.admin.rank.create'}
<h2>{@hfnuadmin~rank.create.a.rank@}</h2>
{formfull $form, 'hfnuadmin~ranks:savecreate'}
<br/>
{@hfnuadmin~rank.create.a.rank.description@}
{/ifacl2}
<h2>{@hfnuadmin~rank.list.of.ranks@}</h2>
<table width="100%">
    <tr>
        <th>{@havefnubb~rank.rank_name@}</th>
        <th>{@havefnubb~rank.rank_limit@}</th>
{ifacl2 'hfnu.admin.rank.edit'}               
        <th>{@hfnuadmin~rank.actions@}</th>
{/ifacl2}         
    </tr>
    {foreach $ranks as $rank}
    {ifacl2 'hfnu.admin.rank.edit'}
    {zone 'hfnuadmin~ranks_edit_inline',array('id_rank'=>$rank->id_rank)}
    {else}
    <tr>
        <td>{$rank->rank_name|eschtml}</td>
        <td>{$rank->rank_limit}</td>
    </tr>
    {/ifacl2}
    {/foreach}    
</table>