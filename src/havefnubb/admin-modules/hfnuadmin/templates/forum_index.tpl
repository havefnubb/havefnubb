{include 'hlp_forum_index'}
<h1>{@hfnuadmin~forum.forum.management@}</h1>
{ifacl2 'hfnu.admin.forum.create'}
<p>{@hfnuadmin~forum.forum.description@}</p>
<h2>{@hfnuadmin~forum.create.a.forum@}</h2>
{formfull $form, 'hfnuadmin~forum:create'}
{/ifacl2}
{zone 'hfnuadmin~category'}
