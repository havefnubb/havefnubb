<div id="breadcrumbtop" class="headbox">
    <h23>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {jlocale 'havefnubb~main.cloud.posts.by.tag',array($tag)}</h2>
</div>
<div class="cloud-list">
    <table class="cloudList data_table" width="100%">
        <tr>
            <th class="listcol">{@havefnubb~forum.forumlist.title@}</th>
            <th class="listcol">{@havefnubb~forum.postlistbytag.forum.name@}</th>
            <th class="listcol">{@havefnubb~member.common.author@}</th>
        </tr>
    {if count($posts) > 0}
        <tbody>
    {foreach $posts as $post}
        {ifacl2 'hfnu.posts.view','forum'.$post->id_forum}
        <tr>
            <td class="coltitle linkincell"><a href="{jurl 'havefnubb~posts:view',array('id_forum'=>$post->id_forum,'id_post'=>$post->id_post,'thread_id'=>$post->thread_id,'ptitle'=>$post->subject,'ftitle'=>$post->forum_name)}">{$post->subject|eschtml}</a></td>
            <td class="coltitle linkincell"><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$post->id_forum,'ftitle'=>$post->forum_name)}">{$post->forum_name|eschtml}</a></td>
            <td class="coltitle linkincell"><a href="{jurl 'jcommunity~account:show',array('user'=>$post->login)}">{$post->login|eschtml}</a> {$post->date_created|jdatetime:'timestamp':'lang_datetime'}</td>
        </tr>
        {/ifacl2}
    {/foreach}
        </tbody>
    {/if}
    </table>
</div>
