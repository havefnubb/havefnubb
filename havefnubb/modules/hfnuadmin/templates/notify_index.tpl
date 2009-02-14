<h1>{@hfnuadmin~notify.the.notifications@}</h1>
<table width="100%" class="records-list">
<thead>  
    <tr>
        <th  class="records-list-notify-author">{@hfnuadmin~notify.author@}</th>
        <th  class="records-list-notify-date">{@hfnuadmin~notify.date@}</th>
        <th  class="records-list-notify-forum">{@hfnuadmin~forum.forum_name@}</th>
        <th  class="records-list-notify-subjet">{@hfnuadmin~notify.message@}</th>
    </tr>
</thead>
<tbody>
    {assign $line = true}    
    {foreach $notify as $notif}
    
    <tr class="{if $line}odd{else}even{/if}">
        <td>{$notif->member_login|eschtml}</td>
        <td>{$notif->date_created|jdatetime:'db_datetime':'lang_datetime'}</td>
        <td><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$notif->id_forum)}" >{$notif->forum_name|eschtml}</a></td>
        <td>{@hfnuadmin~notify.subject@}: <a href="{jurl 'havefnubb~posts:view',array('id_post'=>$notif->id_post)}" >{$notif->subject|eschtml}</a></td>
    </tr>
    <tr>
        <td>{ifacl2 'hfnu.admin.notify.delete'}<a href="{jurl 'hfnuadmin~notify:delete',array('id_notify'=>$notif->id_notify)}" title="{@hfnuadmin~notify.delete.this.notify@}" onclick="return confirm('{jlocale 'hfnuadmin~notify.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a>{/ifacl2}</td>
        <td colspan="3" class="td-notify-message">{$notif->message|wiki:'wr3_to_xhtml'|stripslashes}</td>
    </tr>
    {assign $line = !$line}    
    {/foreach}
</tbody>    
</table>