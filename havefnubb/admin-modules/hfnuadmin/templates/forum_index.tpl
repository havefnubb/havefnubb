{meta_html css  $j_jelixwww.'design/records_list.css'}
{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.core.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.widget.min.js'}
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
{include 'hlp_forum_index'}
<h1>{@hfnuadmin~forum.forum.management@}</h1>
{ifacl2 'hfnu.admin.forum.create'}
<p>{@hfnuadmin~forum.forum.description@}</p>
<h2>{@hfnuadmin~forum.create.a.forum@}</h2>
{formfull $form, 'hfnuadmin~forum:create'}
{/ifacl2}
{zone 'hfnuadmin~category'}
