{meta_html css  $j_jelixwww.'design/records_list.css'}
<h1>{@havefnubb~rank.the.ranks@}</h1>
{ifacl2 'hfnu.admin.rank.create'}
<h2>{@hfnuadmin~rank.create.a.rank@}</h2>
{formfull $form, 'hfnuadmin~ranks:savecreate'}
<br/>
{@hfnuadmin~rank.create.a.rank.description@}
{/ifacl2}
<h2>{@hfnuadmin~rank.list.of.ranks@}</h2>
<form action="{formurl 'hfnuadmin~ranks:saveedit'}" method="post">
<table width="100%" class="records-list">
<thead>  
    <tr>
        <th  class="records-list-rank-name">{@havefnubb~rank.rank_name@}</th>
        <th  class="records-list-rank-limit">{@havefnubb~rank.rank_limit@}</th>
{ifacl2 'hfnu.admin.rank.edit'}               
        <th   class="records-list-rank-action">{@hfnuadmin~rank.actions@}</th>
{/ifacl2}         
    </tr>
</thead>
<tbody>
    {assign $line = true}    
    {foreach $ranks as $rank}
    {ifacl2 'hfnu.admin.rank.edit'}    
    <tr class="{if $line}odd{else}even{/if}">
        <td><input type="text" size="40" name="rank_name[{$rank->id_rank}]" value="{$rank->rank_name}" /></td>
        <td><input type="text" size="9" name="rank_limit[{$rank->id_rank}]" value="{$rank->rank_limit}" /></td>
        <td><input type="hidden" name="id_rank[{$rank->id_rank}]" value="{$rank->id_rank}" />{ifacl2 'hfnu.admin.rank.delete'}<a href="{jurl 'hfnuadmin~ranks:delete',array('id_rank'=>$rank->id_rank)}" title="{@hfnuadmin~rank.delete.this.rank@}" onclick="return confirm('{jlocale 'hfnuadmin~rank.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a>{/ifacl2}</td>
    </tr>
    {else}
    <tr>
        <td>{$rank->rank_name|eschtml}</td>
        <td>{$rank->rank_limit}</td>
    </tr>
    {/ifacl2}
    {assign $line = !$line}    
    {/foreach}
</tbody>    
</table>
<div class="jforms-submit-buttons">
{formurlparam 'hfnuadmin~ranks:saveedit'}    
    <input type="submit" name="saveBt" id="jforms_hfnuadmin_rank_validate_saveBt" class="jforms-submit" value="{@hfnuadmin~rank.saveBt@}"/>
</div>
</form>