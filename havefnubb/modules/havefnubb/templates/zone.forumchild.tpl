{if $childs > 0}
<ul class="child">
    <li>{@havefnubb~forum.forumchild.subforum@} :</li>
{foreach $forumChilds as $forum}
    <li><a href="{jurl 'havefnubb~posts:lists',array('id'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></li>
{/foreach}
<ul>
{/if}