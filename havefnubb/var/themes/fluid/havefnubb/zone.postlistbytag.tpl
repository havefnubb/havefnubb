<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@havefnubb~main.cloud@}</h3>
</div>
<div class="box">
    <div class="block">
    <table>
        <caption>{jlocale 'havefnubb~main.cloud.posts.by.tag',array($tag)}</caption>
        <thead>        
        <tr>
            <th>{@havefnubb~forum.forumlist.title@}</th>
            <th>{@havefnubb~member.common.author@}</th>
        </tr>
        </thead>
        <tbody>
    {for $i = 0 ; $i < $count ; $i++}
        {ifacl2 'hfnu.posts.view','forum'.$posts[$i]['id_forum']}
        <tr>
            <td><a href="{jurl 'havefnubb~posts:view',array('id_forum'=>$posts[$i]['id_forum'],'id_post'=>$posts[$i]['id_post'],'parent_id'=>$posts[$i]['parent_id'],'ptitle'=>$posts[$i]['subject'],'ftitle'=>$posts[$i]['forum_name'])}">{$posts[$i]['subject']|eschtml}</a></td>
            <td><a href="{jurl 'jcommunity~account:show',array('user'=>$posts[$i]['login'])}">{$posts[$i]['login']|eschtml}</a>, <a href="{jurl 'jcommunity~account:show',array('user'=>$posts[$i]['login'])}">{$posts[$i]['date_modified']|jdatetime:'timestamp':'lang_datetime'}</a></td>
        </tr>
        {/ifacl2}
    {/for}
        </tbody>    
    </table>
    </div>    
</div>    
