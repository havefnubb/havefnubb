{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.core.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.tabs.min.js'}
{literal}
<script type="text/javascript">
//<![CDATA[
  $(document).ready(function(){
    $("#hfnuadmin-config > ul").tabs();
  });
//]]>
</script>
{/literal}
<h1>{@hfnuadmin~admin.config@}</h1>
{form $form, 'hfnuadmin~default:config'}
<div id="hfnuadmin-config">
<ul>
	<li><a href="#hfnuadmin-general"><span>{@hfnuadmin~config.general@}</span></a></li>
	<li><a href="#hfnuadmin-messages"><span>{@hfnuadmin~config.messages@}</span></a></li>
	<li><a href="#hfnuadmin-timeout"><span>{@hfnuadmin~config.timeout@}</span></a></li>
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
	<p>{ctrl_label 'elapsed_time_between_two_post_by_same_ip'} </p>
	<p>{ctrl_control 'elapsed_time_between_two_post_by_same_ip'} </p>
	<p>{@hfnuadmin~config.elapsed_time_between_two_post_by_same_ip.description@}</p>

	<p>{ctrl_label 'elapsed_time_after_posting_before_editing'} </p>
	<p>{ctrl_control 'elapsed_time_after_posting_before_editing'} </p>
	<p>{@hfnuadmin~config.elapsed_time_after_posting_before_editing.description@}</p>
</fieldset>
</div>

<div id="hfnuadmin-timeout">
<fieldset>
	<legend>{@hfnuadmin~config.timeout@}</legend>
	<p>{ctrl_label 'timeout_visit'} </p>
	<p>{ctrl_control 'timeout_visit'} </p>
	<p>{@hfnuadmin~config.timeout_visit.desc@}</p>
	<p>{ctrl_label 'timeout_connected'} </p>
	<p>{ctrl_control 'timeout_connected'} </p>
	<p>{@hfnuadmin~config.timeout_connected.desc@}</p>
</fieldset>
</div>

<div id="hfnuadmin-members">
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
