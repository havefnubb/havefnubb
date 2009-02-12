<h1>{@hfnuadmin~notify.the.notifications@}</h1>
<table width="100%" class="records-list">
<thead>  
    <tr>
        <th  class="records-list-notify-date">{@hfnuadmin~notify.date@}</th>
        <th  class="records-list-notify-subjet">{@hfnuadmin~notify.message@}</th>
    </tr>
</thead>
<tbody>
    {assign $line = true}    
    {foreach $notify as $notif}
    
    <tr class="{if $line}odd{else}even{/if}">
        <td rowspan="2">{$notif->date_created|jdatetime:'db_datetime':'lang_datetime'}<br/>
        {ifacl2 'hfnu.admin.notify.delete'}<a href="{jurl 'hfnuadmin~notify:delete',array('id_notify'=>$notif->id_notify)}" title="{@hfnuadmin~notify.delete.this.notify@}" onclick="return confirm('{jlocale 'hfnuadmin~notify.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a>{/ifacl2}
        </td>
        <td>{@hfnuadmin~notify.subject@}: {$notif->subject|eschtml}</td>
    </tr>
    <tr>
        <td>{$notif->message|wiki:'wr3_to_xhtml'|stripslashes}</td>
    </tr>
    {assign $line = !$line}    
    {/foreach}
</tbody>    
</table>