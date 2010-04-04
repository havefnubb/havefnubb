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
{hook 'hfbAccountShowBefore',array($user->login)}
<div id="post-message">{jmessage}</div>
<div class="box">
	<h2>{if $himself}<a id="user" href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)}</a>{else}{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)}{/if}</h2>
	<div class="block">
		<div id="user-profile-avatar">
		{if $user->member_gravatar == 1}
			{gravatar $user->email,array('username'=>$user->login)}
		{else}
			{if file_exists('hfnu/images/avatars/'. $user->id.'.png') }
			{image 'hfnu/images/avatars/'. $user->id.'.png', array('alt'=>$user->login)}
			{elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpg')}
			{image 'hfnu/images/avatars/'. $user->id.'.jpg', array('alt'=>$user->login)}
			{elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpeg')}
			{image 'hfnu/images/avatars/'. $user->id.'.jpeg', array('alt'=>$user->login)}
			{elseif file_exists('hfnu/images/avatars/'. $user->id.'.gif')}
			{image 'hfnu/images/avatars/'. $user->id.'.gif', array('alt'=>$user->login)}
			{/if}
		{/if}
		</div>
		<div id="container">
		<ul class="nav main">
			<li><a href="#user-profile-general">{@havefnubb~member.general@}</a></li>
			<li><a href="#user-profile-pref">{@havefnubb~member.pref@}</a></li>
			<li><a href="#user-profile-messenger">{@havefnubb~member.instant.messenger@}</a></li>
			<li><a href="#user-profile-hardware">{@havefnubb~member.hardware@}</a></li>
			{hook 'hfbAccountShowTab',array($user->login)}
		</ul>
			<div id="user-profile-general">
			<fieldset>
				<legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
	{if $user->member_show_email == 'Y'}
	{assign $class="three-cols"}
	{else}
	{assign $class="two-cols"}
	{/if}
				<div class="{$class} form_row">
					<div class="form_property">
						<label class="user-name user-image"><strong>{@havefnubb~member.nickname@}</strong></label>
					</div>
					<div class="form_value">
						{$user->nickname|eschtml}
					</div>

	{if $user->member_show_email == 'Y'}
					<div class="form_property">
						<label class="user-email user-image"><strong>{@havefnubb~member.email@}</strong></label>
					</div>
					<div class="form_value">
						{mailto array('address'=>$user->email,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}
					</div>
	{/if}
					<div class="form_property">
						<label class="user-birthday user-image"><strong>{@havefnubb~member.common.age@}</strong></label>
					</div>
					<div class="form_value">
						{age $user->member_birth}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			<fieldset>
				<legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label class="user-town user-image"><strong>{@havefnubb~member.common.town@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_town|eschtml}
					</div>
					<div class="form_property">
						<label><strong>{@havefnubb~member.common.country@}</strong></label>
					</div>
					<div class="form_value">
						{if $user->member_country != ''}
						{image 'hfnu/images/flags/'.strtolower($user->member_country).'.gif', array('alt'=>$user->member_country)} {country $user->member_country}
						{/if}
					</div>
					<div class="form_property">
						<label class="user-website user-image"><strong>{@havefnubb~member.common.website@}</strong>&nbsp;</label>
					</div>
					<div class="fom_value">
						{if $user->member_website != ''}
						<a href="{$user->member_website|eschtml}" title="{@havefnubb~member.common.website@}">{$user->member_website|eschtml}</a>
						{/if}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			<fieldset>
				<legend><span class="user-stats user-image">{@havefnubb~member.common.stats@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label><strong>{@havefnubb~member.common.rank@}</strong></label>
					</div>
					<div class="form_value">
						{zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}
					</div>
					<div class="form_property">
						<label><strong>{@havefnubb~member.memberlist.nb.posted.msg@}</strong></label>
					</div>
					<div class="form_value">
						{$user->nb_msg}
					</div>
					<div class="form_property">
						<label><strong>{@havefnubb~member.common.registered.since@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_created|jdatetime}
					</div>
					<div class="form_property">
						<label><strong>{@havefnubb~member.common.last.connection@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_last_connect|jdatetime:'timestamp'}
					</div>
					<div class="clearer">
						&nbsp;
					</div>
				</div>
			</fieldset>
			</div>
			<div id="user-profile-messenger">
			<fieldset>
				<legend><span class="user-messenger user-image">{@havefnubb~member.instant.messenger@}</span></legend>
	{if $himself}
	{else}
				<div class="form_row">
					<div class="form_property">
						<label class="user-messenger user-image"> &nbsp;</label>
					</div>
					<div class="form_value"><a href="{jurl 'hfnucontact~default:index',array('to'=>$user->login)}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->login)}">{@havefnubb~member.common.contact.the.member.by.email@}</a></div>
					<div class="clearer">&nbsp;</div>
				</div>
	{/if}
				<div class="form_row">
					<div class="form_property">
						<label class="user-xfire user-image"><strong>{@havefnubb~member.xfire@}</strong></label>
					</div>
					<div class="fom_value">
						{$user->member_xfire|eschtml}
					</div>
					<div class="form_property">
						<label class="user-icq user-image"><strong>{@havefnubb~member.icq@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_icq|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				 <div class="form_row">
					<div class="form_property">
						<label class="user-yim user-image"><strong>{@havefnubb~member.yim@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_yim|eschtml}
					</div>
					<div class="form_property">
						<label class="user-msn user-image"><strong>{@havefnubb~member.hotmail@}</strong></label>
					</div>
					<div class="form_value">
						{if $user->member_hotmail != ''}
						{mailto array('address'=>$user->member_hotmail,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}
						{/if}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				 <div class="form_row">
					<div class="form_property">
						<label class="user-aim user-image"><strong>{@havefnubb~member.aol@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_aol|eschtml}
					</div>
					<div class="form_property">
						<label class="user-gtalk user-image"><strong>{@havefnubb~member.gtalk@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_gtalk|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				 <div class="form_row">
					<div class="form_property">
						<label class="user-jabber user-image"><strong>{@havefnubb~member.jabber@}</strong></label>
					</div>
					<div class="forum_value">
						{$user->member_jabber|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			</div>
			<div id="user-profile-pref">
			<fieldset>
				<legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label><strong>{@havefnubb~member.common.language@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_language}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label><strong>{@havefnubb~member.common.account.signature@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_comment|wiki:'hfb_rule'|stripslashes}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			</div>
			<div id="user-profile-hardware">
			<fieldset>
				<legend><span class="user-hw user-image">{@havefnubb~member.hardware@}</span></legend>
				<div class="form_row">
					<div class="form_property">
						<label class="user-connect user-image"><strong>{@havefnubb~member.connection@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_connection|eschtml}
					</div>
					<div class="form_property">
						<label class="user-os user-image"><strong>{@havefnubb~member.os@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_os|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-processor  user-image"><strong>{@havefnubb~member.proc@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_proc|eschtml}
					</div>
					<div class="form_property">
						<label class="user-motherboard user-image"><strong>{@havefnubb~member.mb@}</strong></label>
					</div>
					<div class="fom_value">
						{$user->member_mb|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-card user-image"><strong>{@havefnubb~member.card@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_card|eschtml}
					</div>
					<div class="form_property">
						<label class="user-ram user-image"><strong>{@havefnubb~member.ram@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_ram|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-display user-image"><strong>{@havefnubb~member.display@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_display|eschtml}
					</div>
					<div class="form_property">
						<label class="user-screen user-image"><strong>{@havefnubb~member.screen@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_screen|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
				<div class="form_row">
					<div class="form_property">
						<label class="user-mouse user-image"><strong>{@havefnubb~member.mouse@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_mouse|eschtml}
					</div>
					<div class="form_property">
						<label class="user-keyboard user-image"><strong>{@havefnubb~member.keyb@}</strong></label>
					</div>
					<div class="form_value">
						{$user->member_keyb|eschtml}
					</div>
					<div class="clearer">&nbsp;</div>
				</div>
			</fieldset>
			</div>
			{hook 'hfbAccountShowDiv',array($user->login)}
		</div> <!-- #container -->
	</div>
	<div class="fake-button-left">
	{if $himself}
	<a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@havefnubb~member.account.show.edit.your.profile@}</a>
	{/if}
	</div>
	</div>
	{hook 'hfbAccountShowBefore',array($user->login)}
