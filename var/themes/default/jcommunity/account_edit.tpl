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
<div id="breadcrumbtop" class="headbox">
    <h3 id="user" class="user-image">{@havefnubb~member.edit.account.header@} - <a class="user-private-message  user-image" href="{jurl 'havefnubb~members:mail'}" >{@havefnubb~member.internal.messenger@}</a>
{ifacl2 'auth.users.change.password'}
> <a class="user-edit-password user-image" href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a>
{/ifacl2}    
    </h3>
</div>
<div id="profile">
{form $form, 'jcommunity~account:save', array('user'=>$username)}
	<div id="container" class="user-formbg">
		<ul>
			<li><a href="#user-profile-general"><span class="user-general user-image">{@havefnubb~member.general@}</span></a></li>
			<li><a href="#user-profile-pref"><span class="user-pref user-image">{@havefnubb~member.pref@}</span></a></li>
			<li><a href="#user-profile-messenger"><span class="user-messenger user-image">{@havefnubb~member.instant.messenger@}</span></a></li>	
			<li><a href="#user-profile-hardware"><span class="user-hw user-image">{@havefnubb~member.hardware@}</span></a></li>
		</ul>    
        <div id="user-profile-general">
            <fieldset>
                <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
                <div class="two-cols">
                    <p class="col">
                        <label class="user-name user-image"><strong>{ctrl_label 'nickname'}</strong></label><br />{ctrl_control 'nickname'}
                    </p>
                    <p class="col">
                        <label class="user-email  user-image"><strong>{ctrl_label 'email'}</strong></label><br />{ctrl_control 'email'}
                    </p>
                </div>
                <div class="two-cols">
                    <p class="col">
                        <label class="user-birthday user-image"><strong>{ctrl_label 'member_birth'}</strong></label><br />{ctrl_control 'member_birth'}
                    </p>
                    <p class="col">
					</p>					
                </div>
            </fieldset>                
            <fieldset>
                <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>                   
                <div class="three-cols">
                    <p class="col">                
                        <label class="user-town user-image"><strong>{ctrl_label 'member_town'}</strong></label><br />{ctrl_control 'member_town'}
                    </p>
                    <p class="col">
                        <label class="user-country user-image"><strong>{ctrl_label 'member_country'}</strong></label><br />{ctrl_control 'member_country'}<br/>
                    </p>
                    <p class="col">
						<label class="user-website user-image"><strong>{ctrl_label 'member_website'}</strong></label><br />{ctrl_control 'member_website'}
                    </p>
                </div>				
            </fieldset>
        </div>
        <div id="user-profile-messenger">
            <fieldset>
                <legend><span class="user-messenger user-image">{@havefnubb~member.instant.messenger@}</span></legend>
                <div class="two-cols">
                    <p class="col">
                        <label class="user-xfire user-image"><strong>{ctrl_label 'member_xfire'}</strong></label><br />{ctrl_control 'member_xfire'}
                    </p>                   
                    <p class="col">
                        <label class="user-icq user-image"><strong>{ctrl_label 'member_icq'}</strong></label><br />{ctrl_control 'member_icq'}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
						<label class="user-yim user-image"><strong>{ctrl_label 'member_yim'}</strong></label><br />{ctrl_control 'member_yim'}
                    </p>                   
                    <p class="col">
                        <label class="user-msn user-image"><strong>{ctrl_label 'member_hotmail'}</strong></label><br />{ctrl_control 'member_hotmail'}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
						<label class="user-aim user-image"><strong>{ctrl_label 'member_aol'}</strong></label><br />{ctrl_control 'member_aol'}
                    </p>                   
                    <p class="col">
						<label class="user-gtalk user-image"><strong>{ctrl_label 'member_gtalk'}</strong></label><br />{ctrl_control 'member_gtalk'}						
                    </p>                   
                </div>                    
                 <div class="two-cols">
                    <p class="col">
						<label class="user-jabber user-image"><strong>{ctrl_label 'member_jabber'}</strong></label><br />{ctrl_control 'member_jabber'}							
                    </p>                   
                </div>                    
					
            </fieldset>
        </div>		
        <div id="user-profile-pref">
            <fieldset>
                <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
                <div class="two-cols">
                    <p class="col">
                        <label class="user-language user-image"><strong>{ctrl_label 'member_language'}</strong></label> {ctrl_control 'member_language'}<br/>
                        {@havefnubb~member.account.edit.language.description@}
                    </p>
                    <p class="col">
                        <label class="user-show-email user-image"><strong>{ctrl_label 'member_show_email'}</strong></label> {ctrl_control 'member_show_email'}<br/>
                        {@havefnubb~member.account.edit.show.your.email.description@}
                    </p>
                </div>
                <div>
					<p class="col">        
                        <label class="user-signature user-image"><strong>{ctrl_label 'member_comment'}</strong></label><br />{ctrl_control 'member_comment'}
                    </p>				
                    <p class="col">
						<label class="user-avatar user-image">
						<strong>{ctrl_label 'member_avatar'}</strong></label>
						{ctrl_control 'member_avatar'}
                    </p>                   					
                </div>                
            </fieldset>
        </div>
		
        <div id="user-profile-hardware">
            <fieldset>
                <legend><span class="user-hw user-image">{@havefnubb~member.hardware@}</span></legend>
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-connect user-image"><strong>{ctrl_label 'member_connection'}</strong></label><br />{ctrl_control 'member_connection'}
                    </p>
                    <p class="col">                                
                        <label class="user-os user-image"><strong>{ctrl_label 'member_os'}</strong></label>      <br />{ctrl_control 'member_os'}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-processor user-image"><strong>{ctrl_label 'member_proc'}</strong></label>    <br />{ctrl_control 'member_proc'}
                    </p>
                    <p class="col">                                
                        <label class="user-motherboard user-image"><strong>{ctrl_label 'member_mb'}</strong></label>      <br />{ctrl_control 'member_mb'}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-card user-image"><strong>{ctrl_label 'member_card'}</strong></label>    <br />{ctrl_control 'member_card'}
                    </p>
                    <p class="col">                                
                        <label class="user-ram user-image"><strong>{ctrl_label 'member_ram'}</strong></label>     <br />{ctrl_control 'member_ram'}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-display user-image"><strong>{ctrl_label 'member_display'}</strong></label> <br />{ctrl_control 'member_display'}
                    </p>
                    <p class="col">                                
                        <label class="user-screen user-image"><strong>{ctrl_label 'member_screen'}</strong></label>  <br />{ctrl_control 'member_screen'}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-mouse user-image"><strong>{ctrl_label 'member_mouse'}</strong></label>   <br />{ctrl_control 'member_mouse'}
                    </p>
                    <p class="col">                                
                        <label class="user-keyboard user-image"><strong>{ctrl_label 'member_keyb'}</strong></label>    <br />{ctrl_control 'member_keyb'}
                    </p>
                </div>
            </fieldset>
        </div>
	</div>
	<p class="formsubmit">{formsubmit}</p>
{/form}
</div>