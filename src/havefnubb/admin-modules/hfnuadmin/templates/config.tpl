{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.core.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.widget.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.tabs.min.js'}
{literal}
<script type="text/javascript">
//<![CDATA[
var imagePath = '{/literal}{$j_basepath}{literal}hfnu/images/';
$(document).ready(function(){
  //manage tabs
  $("#hfnuadmin-config").tabs();
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
{include 'hlp_configuration'}
<h1>{@hfnuadmin~admin.config@}</h1>
{form $form, 'hfnuadmin~default:saveconfig'}
<div id="hfnuadmin-config">
<ul>
    <li><a href="#hfnuadmin-general"><span>{@hfnuadmin~config.general@}</span></a></li>
    <li><a href="#hfnuadmin-messages"><span>{@hfnuadmin~config.messages@}</span></a></li>
    <li><a href="#hfnuadmin-important"><span>{@hfnuadmin~config.important_posts@}</span></a></li>
    <li><a href="#hfnuadmin-flood"><span>{@hfnuadmin~config.flood_protection@}</span></a></li>
    <li><a href="#hfnuadmin-members"><span>{@hfnuadmin~config.members@}</span></a></li>
    <li><a href="#hfnuadmin-social-networks"><span>{@hfnuadmin~config.social.networks@}</span></a></li>
</ul>
<div id="hfnuadmin-general">
<fieldset>
    <legend>{@hfnuadmin~config.general@}</legend>
    <p>{ctrl_label 'title'} </p>
    <p>{ctrl_control 'title'} </p>

    <p>{ctrl_label 'description'} </p>
    <p>{ctrl_control 'description'} </p>

    <p>{ctrl_label 'rules'} </p>
    <p>{ctrl_control 'rules'} </p>

    <p>{ctrl_label 'webmaster_email'} </p>
    <p>{ctrl_control 'webmaster_email'} </p>

    <p>{ctrl_label 'admin_email'} </p>
    <p>{ctrl_control 'admin_email'} </p>

    <p>{ctrl_label 'timezone'} </p>
    <p>{ctrl_control 'timezone'} </p>
</fieldset>
</div>

<div id="hfnuadmin-messages">
<fieldset>
    <legend>{@hfnuadmin~config.messages.desc@}</legend>
    <p>{ctrl_label 'posts_per_page'} </p>
    <p>{ctrl_control 'posts_per_page'} </p>
    <p>{@hfnuadmin~config.posts_per_page.description@}</p>

    <p>{ctrl_label 'replies_per_page'} </p>
    <p>{ctrl_control 'replies_per_page'} </p>
    <p>{@hfnuadmin~config.replies_per_page.description@}</p>

    <p>{ctrl_label 'stats_nb_of_lastpost'} </p>
    <p>{ctrl_control 'stats_nb_of_lastpost'} </p>
    <p>{@hfnuadmin~config.stats_nb_of_lastpost.description@}</p>

    <p>{ctrl_label 'post_max_size'} </p>
    <p>{ctrl_control 'post_max_size'} </p>
    <p>{@hfnuadmin~config.post_max_size.description@}</p>
</fieldset>
</div>

<div id="hfnuadmin-important">
<fieldset>
    <legend>{@hfnuadmin~config.important_posts@}</legend>
    <p>{@hfnuadmin~config.important.description@}</p>
    <p>{ctrl_label 'important_nb_replies'} </p>
    <p>{ctrl_control 'important_nb_replies'} </p>

    <p>{ctrl_label 'important_nb_views'} </p>
    <p>{ctrl_control 'important_nb_views'} </p>
</fieldset>
</div>


<div id="hfnuadmin-flood">
<fieldset>
    <legend>{@hfnuadmin~config.flood_protection@}</legend>
    <p>{ctrl_label 'elapsed_time_between_two_post'} </p>
    <p>{ctrl_control 'elapsed_time_between_two_post'} </p>
    <p>{@hfnuadmin~config.flood.elapsed_time_between_two_post.description@}</p>

    <p>{ctrl_control 'only_same_ip'} {ctrl_label 'only_same_ip'} </p>
    <p>{@hfnuadmin~config.flood.only_same_ip.description@}</p>
</fieldset>
</div>

<div id="hfnuadmin-members">
<fieldset>
    <legend>{@hfnuadmin~config.anonymous_post_authorized.desc@}</legend>
    <p>{ctrl_label 'anonymous_post_authorized'} </p>
    <p>{ctrl_control 'anonymous_post_authorized'} </p>
    <p>{jlocale 'hfnuadmin~config.anonymous_post_authorized.description.html',$forumUrl}</p>
</fieldset>
<fieldset>
    <legend>{@hfnuadmin~config.members@}</legend>
    <p>{ctrl_label 'members_per_page'} </p>
    <p>{ctrl_control 'members_per_page'} </p>
    <p>{@hfnuadmin~config.members_per_page.description@}</p>
</fieldset>
<fieldset>
    <legend>{@hfnuadmin~config.avatar@}</legend>
    <p>{@hfnuadmin~config.avatar.desc@}</p>
    <p>{ctrl_label 'avatar_max_width'} </p>
    <p>{ctrl_control 'avatar_max_width'} </p>
    <p>{ctrl_label 'avatar_max_height'} </p>
    <p>{ctrl_control 'avatar_max_height'} </p>
</fieldset>
</div>

<div id="hfnuadmin-social-networks">
<fieldset>
    <legend>{@hfnuadmin~config.from.which.network.you.want.to.link.to@}</legend>
    <div class="social-network">
        <div id="social-network-twitter"><ul><li>{ctrl_label 'social_network_twitter'}</li><li>{ctrl_control 'social_network_twitter'}</li></ul></div>
        <div id="social-network-digg"><ul><li>{ctrl_label 'social_network_digg'}</li><li>{ctrl_control 'social_network_digg'}</li></ul></div>
        <div id="social-network-delicious"><ul><li>{ctrl_label 'social_network_delicious'}</li><li>{ctrl_control 'social_network_delicious'}</li></ul></div>
        <div id="social-network-facebook"><ul><li>{ctrl_label 'social_network_facebook'}</li><li>{ctrl_control 'social_network_facebook'}</li></ul></div>
        <div id="social-network-reddit"><ul><li>{ctrl_label 'social_network_reddit'}</li><li>{ctrl_control 'social_network_reddit'}</li></ul></div>
        <div id="social-network-netvibes"><ul><li>{ctrl_label 'social_network_netvibes'}</li><li>{ctrl_control 'social_network_netvibes'}</li></ul></div>
    </div>
</fieldset>
</div>

<div>{formsubmit 'validate'} {formreset 'cancel'}</div>
</div>
{/form}
