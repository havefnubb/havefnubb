{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.core.min.js'}
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
{include 'hlp_ban'}
<h1>{@hfnuadmin~ban.the.bans@}</h1>
{formfull $form, 'hfnuadmin~ban:saveban'}
<table width="100%" class="records-list">
    <thead>
        <tr>
            <th class="records-list-category-order">{@hfnuadmin~ban.username@}</th>
            <th class="records-list-category-name">{@hfnuadmin~ban.expiry@}</th>
            <th class="records-list-category-name">{@hfnuadmin~ban.ip@}</th>
            <th class="records-list-category-name">{@hfnuadmin~ban.email@}</th>
            <th class="records-list-category-name">{@hfnuadmin~ban.action@}</th>
        </tr>
    </thead>
    {if $bans->rowCount() > 0}
    <tbody>
{foreach $bans as $ban}
    <tr>
        <td>{$ban->ban_username}</td>
        <td>{if $ban->ban_expire == ''}{@hfnuadmin~ban.never.expire@}{else}{$ban->ban_expire|jdatetime:'timestamp':'lang_datetime'} {/if}</td>
        <td>{$ban->ban_ip}</td>
        <td>{$ban->ban_email}</td>
        <td><a href="{jurl 'hfnuadmin~ban:delete',array('ban_id'=>$ban->ban_id)}" title="{@hfnuadmin~ban.delete.this.ban@}" onclick="return confirm('{jlocale 'hfnuadmin~ban.confirm.deletion',array('')}')">{@hfnuadmin~common.delete@}</a></td>
    </tr>
    <tr>
        <td colspan="3">{@hfnuadmin~ban.message@}: {$ban->ban_message}</td>
    </tr>
{/foreach}
    </tbody>
    {/if}
</table>
