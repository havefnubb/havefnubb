{literal}
<script type="text/javascript">
//<![CDATA[
var imagePath = '{/literal}{$j_basepath}{literal}hfnu/images/';
$(document).ready(function(){
  // hide help
  $(".hfnuadmin-help").hide();
  // show help
  $("#hfnuadmin-help").click(function () {
     $(this).toggleClass("active").next().slideToggle("slow");
  });
  //toggle Image
  $("#hfnuadmin-help").toggle(
    function () {
      $(this).find("img").attr({src:imagePath+"delete.png"});
    },
    function () {
      $(this).find("img").attr({src:imagePath+"add.png"});
    }
  );
});
//]]>
</script>
{/literal}
{include 'hlp_notification'}
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
    {if $notify->rowCount() > 0}
    <tbody>
        {assign $line = true}
        {foreach $notify as $notif}
        <tr class="{cycle array('odd','even')}">
            <td>{$notif->login|eschtml}</td>
            <td>{$notif->date_created|jdatetime:'timestamp':'lang_datetime'}</td>
            <td><a href="{jurl 'havefnubb~posts:lists',
            array(  'ftitle'=>$notif->forum_name,
                    'id_forum'=>$notif->id_forum
                    )
                    }">{$notif->forum_name|eschtml}</a></td>
            <td>{@hfnuadmin~notify.subject@}: <a href="{jurl 'havefnubb~posts:view',
            array(  'id_post'=>$notif->id_first_msg,
                    'ftitle'=>$notif->forum_name,
                    'id_forum'=>$notif->id_forum,
                    'thread_id'=>$notif->thread_id,
                    'ptitle'=>$notif->subject,
                    )}#p{$notif->id_post}" >{$notif->subject|eschtml}</a></td>
        </tr>
        <tr>
            <td>{ifacl2 'hfnu.admin.notify.delete'}<a href="{jurl 'hfnuadmin~notify:delete',array('id_notify'=>$notif->id_notify)}" title="{@hfnuadmin~notify.delete.this.notify@}" onclick="return confirm('{jlocale 'hfnuadmin~notify.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a>{/ifacl2}</td>
            <td colspan="3" class="td-notify-message">{$notif->message|wiki|stripslashes}</td>
        </tr>
        {assign $line = !$line}
        {/foreach}
    </tbody>
    {/if}
</table>
