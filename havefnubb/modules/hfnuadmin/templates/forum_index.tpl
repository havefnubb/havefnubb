<h1>{@hfnuadmin~forum.forum.management@}</h1>
{formfull $form, 'hfnuadmin~forum:create'}

{assign $cat = ''}
{foreach $forums as $forum}
{if $cat != $forum->id_cat}
<h2>{$forum->cat_name|eschtml}</h2>
{assign $cat = $forum->id_cat}
{/if}
[ <a href="{jurl 'hfnuadmin~forum:edit',array('id_forum'=>$forum->id_forum)}" title="{@hfnuadmin~forum.delete.this.forum@}" onclick="return confirm('{jLocale 'hfnuadmin~forum.confirm.deletion',array($forum->forum_name)}')">X</a> ] <a href="{jurl 'hfnuadmin~forum:edit',array('id_forum'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a><br/>
{/foreach}
