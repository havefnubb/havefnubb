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
    <li><a href="#hfnuadmin-members"><span>{@hfnuadmin~config.members@}</span></a></li>
</ul>
<div id="hfnuadmin-general">
<fieldset>
    <legend>{@hfnuadmin~config.general@}</legend>
<p>{ctrl_label 'title'} </p>
<p>{ctrl_control 'title'} </p>

<p>{ctrl_label 'description'} </p>
<p>{ctrl_control 'description'} </p>

<p>{ctrl_label 'theme'} </p>
<p>{ctrl_control 'theme'} </p>

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
    <legend>{@hfnuadmin~config.messages@}</legend>
<p>{ctrl_label 'posts_per_page'} </p>
<p>{ctrl_control 'posts_per_page'} </p>
<p>{@hfnuadmin~config.posts_per_page.description@}</p>

<p>{ctrl_label 'replies_per_page'} </p>
<p>{ctrl_control 'replies_per_page'} </p>
<p>{@hfnuadmin~config.replies_per_page.description@}</p>

<p>{ctrl_label 'stats_nb_of_lastpost'} </p>
<p>{ctrl_control 'stats_nb_of_lastpost'} </p>
<p>{@hfnuadmin~config.stats_nb_of_lastpost.description@}</p>

<p>{ctrl_label 'elapsed_time_between_two_post_by_same_ip'} </p>
<p>{ctrl_control 'elapsed_time_between_two_post_by_same_ip'} </p>
<p>{@hfnuadmin~config.elapsed_time_between_two_post_by_same_ip.description@}</p>

<p>{ctrl_label 'elapsed_time_after_posting_before_editing'} </p>
<p>{ctrl_control 'elapsed_time_after_posting_before_editing'} </p>
<p>{@hfnuadmin~config.elapsed_time_after_posting_before_editing.description@}</p>

<p>{ctrl_label 'post_max_size'} </p>
<p>{ctrl_control 'post_max_size'} </p>
<p>{@hfnuadmin~config.post_max_size.description@}</p>
</fieldset>
</div>

<div id="hfnuadmin-members">
<fieldset>
    <legend>{@hfnuadmin~config.members@}</legend>
<p>{ctrl_label 'members_per_page'} </p>
<p>{ctrl_control 'members_per_page'} </p>
<p>{@hfnuadmin~config.members_per_page.description@}</p>
</fieldset>
</div>
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>
</div>
{/form}