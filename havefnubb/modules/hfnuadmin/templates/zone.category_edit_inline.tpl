{form $form, 'hfnuadmin~category:saveedit'}    
<tr>
    <td>{ctrl_control 'cat_order'}</td>
    <td>{ctrl_control 'cat_name'}</td>
    <td>{formsubmit 'validate'} {ifacl2 'hfnu.admin.category.delete'} - <a href="{jurl 'hfnuadmin~category:delete',array('id_cat'=>$id_cat)}" title="{@hfnuadmin~category.delete.this.category@}" onclick="return confirm('{jlocale 'hfnuadmin~category.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a>{/ifacl2}</td>
</tr>
{/form}