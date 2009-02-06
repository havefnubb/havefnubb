{form $form, 'hfnuadmin~ranks:saveedit'}    
<tr>
    <td>{ctrl_control 'rank_name'}</td>
    <td>{ctrl_control 'rank_limit'}</td>
    <td>{formsubmit 'validate'} {ifacl2 'hfnu.admin.rank.delete'} - <a href="{jurl 'hfnuadmin~ranks:delete',array('id_rank'=>$id_rank)}" title="{@hfnuadmin~rank.delete.this.rank@}" onclick="return confirm('{jlocale 'hfnuadmin~rank.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a>{/ifacl2}</td>
</tr>
{/form}