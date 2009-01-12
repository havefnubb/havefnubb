{assign $odd=''}
{assign $class=''}
{foreach $posts as $post}
    {if $odd == $post->id_post}
        {assign $class = 'forumlistline1'}
    {else}
        {assign $odd = $post->id_post}
        {assign $class = 'forumlistline2'}
    {/if}
<tr class="{$class}">
    <td></td>
    <td></td>    
    <td class="coltitle"><a href="{jurl 'posts:view',array('id_post'=>$post->id_post)}" title="{@forum.view.this.subject@}">{$post->subject|eschtml}</a></td>
    <td class="colposter">{zone 'poster',array('id_user'=>$post->id_user)}</td>
    <td class="colnum">{zone 'responsettl',array('id_post'=>$post->id_post)}</td>
    <td class="colnum">{zone 'viewedttl',array('id_post'=>$post->id_post)}</td>
    <td class="coldate">{zone 'postlc',array('id_post'=>$post->id_post)}</td>
</tr>
{/foreach}
