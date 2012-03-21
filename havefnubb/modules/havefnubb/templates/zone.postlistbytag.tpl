<div class="breadcrumb">
<ol>
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >> {@havefnubb~main.cloud@}</li>
</ol>
</div>

<div class="box">
    <div class="box-content">
    <table class="forum_postsbytag">
        <caption>{jlocale 'havefnubb~main.cloud.posts.by.tag',array($tag)}</caption>
        <thead>
        <tr>
            <th>{@havefnubb~forum.forumlist.title@}</th>
            <th>{@havefnubb~forum.postlistbytag.forum.name@}</th>
            <th>{@havefnubb~member.common.author@}</th>
        </tr>
        </thead>
    {if count($posts) > 0}
        <tbody>
    {foreach $posts as $post}
        {ifacl2 'hfnu.posts.view','forum'.$post->id_forum}
        <tr>
            <td><a href="{jurl 'havefnubb~posts:view',array('id_forum'=>$post->id_forum,'id_post'=>$post->id_post,'thread_id'=>$post->thread_id,'ptitle'=>$post->subject,'ftitle'=>$post->forum_name)}">{$post->subject|eschtml}</a></td>
            <td><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name)}">{$post->forum_name|eschtml}</a></td>
            <td><a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}">{$post->login|eschtml}</a> {$post->date_created|jdatetime:'timestamp':'lang_datetime'}</td>
        </tr>
        {/ifacl2}
    {/foreach}
        </tbody>
    {/if}
    </table>
    </div>
</div>
