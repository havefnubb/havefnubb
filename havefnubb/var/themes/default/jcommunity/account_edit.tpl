{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.core.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.tabs.min.js'}
{literal}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $("#container > ul").tabs();
});
//]]>
</script>
{/literal}
{hook 'hfbAccountEditBefore',array('user'=>$username)}
<div class="box">
	<h2>{@havefnubb~member.edit.account.header@}</h2>
	<div class="block">
		{form $form, 'jcommunity~account:save', array('user'=>$username)}
		<div id="container">
			<ul class="nav main">
				<li><a href="#user-profile-general">{@havefnubb~member.general@}</a></li>
				<li><a href="#user-profile-pref">{@havefnubb~member.pref@}</a></li>
				<li><a href="#user-profile-messenger">{@havefnubb~member.instant.messenger@}</a></li>
				<li><a href="#user-profile-hardware">{@havefnubb~member.hardware@}</a></li>                
				{hook 'hfbAccountEditTab',array('user'=>$username)}
			</ul>
			<div id="user-profile-general">
			<fieldset>
				<legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label class="user-name user-image"><strong>{ctrl_label 'nickname'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'nickname'}</div>
					<div class="form_property">
						<label class="user-email user-image"><strong>{ctrl_label 'email'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'email'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
				   <div class="form_property">
						<label class="user-birthday user-image"><strong>{ctrl_label 'member_birth'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_birth'}</div>
					{ifacl2 'auth.users.change.password'}
					<div class="form_value"><a class="user-edit-password user-image"  href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a></div>
					{/ifacl2}
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			<fieldset>
				<legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label class="user-town user-image"><strong>{ctrl_label 'member_town'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_town'}</div>
					<div class="form_property">
						<label class="user-country user-image"><strong>{ctrl_label 'member_country'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_country'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-website user-image"><strong>{ctrl_label 'member_website'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_website'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			</div>
			<div id="user-profile-messenger">
			<fieldset>
				<legend><span class="user-messenger user-image">{@havefnubb~member.instant.messenger@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label class="user-email user-image">&nbsp;</label>
					</div>
					<div class="form_value"><a href="{jurl 'havefnubb~members:mail'}">{@havefnubb~member.internal.messenger@}</a></div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-xfire user-image"><strong>{ctrl_label 'member_xfire'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_xfire'}</div>
					<div class="form_property">
						<label class="user-icq user-image"><strong>{ctrl_label 'member_icq'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_icq'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-yim user-image"><strong>{ctrl_label 'member_yim'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_yim'}</div>
					<div class="form_property">
						<label class="user-msn user-image"><strong>{ctrl_label 'member_hotmail'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_hotmail'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-aim user-image"><strong>{ctrl_label 'member_aol'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_aol'}</div>
					<div class="form_property">
						<label class="user-gtalk user-image"><strong>{ctrl_label 'member_gtalk'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_gtalk'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-jabber user-image"><strong>{ctrl_label 'member_jabber'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_jabber'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			</div>
			<div id="user-profile-pref">
			<fieldset>
				<legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label class="user-language user-image"><strong>{ctrl_label 'member_language'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_language'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-show-email user-image"><strong>{ctrl_label 'member_show_email'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_show_email'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_value">{@havefnubb~member.account.edit.show.your.email.description@}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-avatar user-image"><strong>{ctrl_label 'member_avatar'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_avatar'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-gravatar user-image"><strong>{ctrl_label 'member_gravatar'}</strong></label>
						<br/>
					</div>
					<div class="form_value">{ctrl_control 'member_gravatar'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-signature user-image"><strong>{ctrl_label 'member_comment'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_comment'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			</div>
			<div id="user-profile-hardware">
			<fieldset>
				<legend><span class="user-hw user-image">{@havefnubb~member.hardware@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label class="user-connect user-image"><strong>{ctrl_label 'member_connection'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_connection'}</div>
					<div class="form_property">
						<label class="user-os user-image"><strong>{ctrl_label 'member_os'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_os'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-processor user-image"><strong>{ctrl_label 'member_proc'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_proc'}</div>
					<div class="form_property">
						<label class="user-motherboard user-image"><strong>{ctrl_label 'member_mb'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_mb'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-card user-image"><strong>{ctrl_label 'member_card'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_card'}</div>
					<div class="form_property">
						<label class="user-ram user-image"><strong>{ctrl_label 'member_ram'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_ram'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-display user-image"><strong>{ctrl_label 'member_display'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_display'}</div>
					<div class="form_property">
						<label class="user-screen user-image"><strong>{ctrl_label 'member_screen'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_screen'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-mouse user-image"><strong>{ctrl_label 'member_mouse'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_mouse'}</div>
					<div class="form_property">
						<label class="user-keyboard user-image"><strong>{ctrl_label 'member_keyb'}</strong></label>
					</div>
					<div class="form_value">{ctrl_control 'member_keyb'}</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			</div>
			{hook 'hfbAccountEditDiv',array('user'=>$username)}
		</div> <!-- #container -->
		<div class="form_row form_row_submit">
			<div class="form_value">{formsubmit}</div>
			<div class="clearer">&nbsp;</div>
		</div>
	{/form}
	</div> <!-- #block -->
</div> <!-- #box -->
{hook 'hfbAccountEditAfter',array('user'=>$username)}
