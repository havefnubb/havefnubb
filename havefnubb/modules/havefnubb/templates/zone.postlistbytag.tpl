<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {jlocale 'havefnubb~main.cloud.posts.by.tag',array($tag)}</h3>
</div>
<div class="cloud-list">
    <table class="cloudList" width="100%">
        <tr>
            <th class="listcol">{@havefnubb~forum.forumlist.title@}</th>
            <th class="listcol">{@havefnubb~member.common.author@}</th>
        </tr>
    {for $i = 0 ; $i < $count ; $i++}
        {ifacl2 'hfnu.posts.view','forum'.$posts[$i]['id_forum']}
    </tr>
        <tr>
            <td class="coltitle linkincell"><a href="{jurl 'havefnubb~posts:view',array('id_forum'=>$posts[$i]['id_forum'],'id_post'=>$posts[$i]['id_post'],'parent_id'=>$posts[$i]['parent_id'],'ptitle'=>$posts[$i]['subject'],'ftitle'=>$posts[$i]['forum_name'])}">{$posts[$i]['subject']|eschtml}</a></td>
            <td class="colright linkincell"><a href="{jurl 'jcommunity~account:show',array('user'=>$posts[$i]['login'])}">{$posts[$i]['login']|eschtml}</a>, <a href="{jurl 'jcommunity~account:show',array('user'=>$posts[$i]['login'])}">{$posts[$i]['date_modified']|jdatetime:'timestamp':'lang_datetime'}</a></td>
        </tr>
        {/ifacl2}
    {/for}
    </table>
</div>    
