{meta_html css  $j_jelixwww.'design/records_list.css'}
<h1>{@hfnuadmin~forum.forum.management@}</h1>
{ifacl2 'hfnu.admin.forum.create'}
<h2>{@hfnuadmin~forum.create.a.forum@}</h2>
{formfull $form, 'hfnuadmin~forum:create'}
{/ifacl2}
{zone 'hfnuadmin~category'}
