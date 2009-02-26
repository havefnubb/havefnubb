<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {jlocale 'havefnubb~main.cloud.posts.by.tag',array($tag)}</h3>
</div>
<div class="cloud-list">
    <table class="cloudList" width="100%">
        <tr>
            <th class="forumlistcol">{@havefnubb~forum.forumlist.title@}</th>
            <th class="forumlistcol">{@havefnubb~member.common.author@}</th>
        </tr>
    {for $i = 0 ; $i < $count ; $i++}
        {ifacl2 'hfnu.posts.view','forum'.$posts[$i]['id_forum']}
    </tr>
        <tr>
            <td class="coltitle linkincell"><a href="{jurl 'havefnubb~posts:view',array('id_post'=>$posts[$i]['id_post'],'parent_id'=>$posts[$i]['parent_id'],'ptitle'=>$posts[$i]['subject'])}">{$posts[$i]['subject']|eschtml}</a></td>
            <td class="colright linkincell"><a href="{jurl 'havefnubb~member:view',array('id_post'=>$posts[$i]['id_user'])}">{$posts[$i]['login']|eschtml}</a>, <a href="{jurl 'havefnubb~member:view',array('id_post'=>$posts[$i]['id_user'])}">{$posts[$i]['date_modified']|jdatetime:'db_datetime':'lang_datetime'}</a></td>
        </tr>
        {/ifacl2}
    {/for}
    </table>
</div>    
