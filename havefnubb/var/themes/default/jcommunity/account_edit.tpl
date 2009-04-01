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
    <h3>{@havefnubb~member.edit.account.header@} - <a href="{jurl 'havefnubb~members:mail'}" >{@havefnubb~member.internal.messenger@}</a>
{ifacl2 'auth.users.change.password'}
> <a href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a></li>
{/ifacl2}    
    </h3>
</div>
<div id="profile">
{form $form, 'jcommunity~account:save', array('user'=>$username)}
	<div id="container" class="formbg">
	<ul>
		<li><a href="#member-general"><span>{@havefnubb~member.general@}</span></a></li>		
		<li><a href="#member-pref"><span>{@havefnubb~member.pref@}</span></a></li>
		<li><a href="#member-messenger"><span>{@havefnubb~member.instant.messenger@}</span></a></li>	
		<li><a href="#member-hardware"><span>{@havefnubb~member.hardware@}</span></a></li>
	</ul>    

        <div id="member-general">
            <fieldset>
                <legend>{@havefnubb~member.general@}</legend>
                <div class="two-cols">
                    <p class="col">
                        <label><strong>{ctrl_label 'nickname'}</strong></label><br />{ctrl_control 'nickname'}
                    </p>
                    <p class="col">
                        <label><strong>{ctrl_label 'email'}</strong></label><br />{ctrl_control 'email'}
                    </p>
                </div>
                <div class="two-cols">
                    <p class="col">
                        <label><strong>{ctrl_label 'member_birth'}</strong></label><br />{ctrl_control 'member_birth'}
                    </p>
                    <p class="col">
					</p>					
                </div>
            </fieldset>                
            <fieldset>
                <legend>{@havefnubb~member.common.location@}</legend>                   
                <div class="two-cols">
                    <p class="col">                
                        <label><strong>{ctrl_label 'member_town'}</strong></label><br />{ctrl_control 'member_town'}
                    </p>
                    <p class="col">
                        <label><strong>{ctrl_label 'member_country'}</strong></label><br />{ctrl_control 'member_country'}
                    </p>
                </div>                    
            </fieldset>
        </div>
        <div id="member-messenger">
            <fieldset>
                <legend>{@havefnubb~member.instant.messenger@}</legend>
                <div class="two-cols">
                    <p class="col">
                        <label><strong>{ctrl_label 'member_xfire'}</strong></label><br />{ctrl_control 'member_xfire'}
                    </p>                   
                    <p class="col">
                        <label><strong>{ctrl_label 'member_icq'}</strong></label><br />{ctrl_control 'member_icq'}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
						<label><strong>{ctrl_label 'member_yim'}</strong></label><br />{ctrl_control 'member_yim'}
                    </p>                   
                    <p class="col">
                        <label><strong>{ctrl_label 'member_hotmail'}</strong></label><br />{ctrl_control 'member_hotmail'}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
						<label><strong>{ctrl_label 'member_aol'}</strong></label><br />{ctrl_control 'member_aol'}
                    </p>                   
                    <p class="col">
						<label><strong>{ctrl_label 'member_gtalk'}</strong></label><br />{ctrl_control 'member_gtalk'}						
                    </p>                   
                </div>                    
                 <div class="two-cols">
                    <p class="col">
						<label><strong>{ctrl_label 'member_jabber'}</strong></label><br />{ctrl_control 'member_jabber'}							
                    </p>                   
                    <p class="col">                        
                    </p>                   
                </div>                    
					
            </fieldset>
        </div>		
        <div id="member-pref">
            <fieldset>
                <legend>{@havefnubb~member.pref@}</legend>
                <div>
                    <p class="col">
                        <label><strong>{ctrl_label 'member_language'}</strong></label> {ctrl_control 'member_language'}<br/>
                        {@havefnubb~member.account.edit.language.description@}
                    </p>
                    <p class="col">
                        <label><strong>{ctrl_label 'member_show_email'}</strong></label> {ctrl_control 'member_show_email'}<br/>
                        {@havefnubb~member.account.edit.show.your.email.description@}
                    </p>
                </div>
                <div>
                    <p>
                        <label><strong>{ctrl_label 'member_comment'}</strong></label><br />{ctrl_control 'member_comment'}
                    </p>
                </div>                
            </fieldset>
        </div>
		
        <div id="member-hardware">
            <fieldset>
                <legend>{@havefnubb~member.hardware@}</legend>
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{ctrl_label 'member_connection'}</strong></label><br />{ctrl_control 'member_connection'}
                    </p>
                    <p class="col">                                
                        <label><strong>{ctrl_label 'member_os'}</strong></label>      <br />{ctrl_control 'member_os'}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{ctrl_label 'member_proc'}</strong></label>    <br />{ctrl_control 'member_proc'}
                    </p>
                    <p class="col">                                
                        <label><strong>{ctrl_label 'member_mb'}</strong></label>      <br />{ctrl_control 'member_mb'}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{ctrl_label 'member_card'}</strong></label>    <br />{ctrl_control 'member_card'}
                    </p>
                    <p class="col">                                
                        <label><strong>{ctrl_label 'member_ram'}</strong></label>     <br />{ctrl_control 'member_ram'}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{ctrl_label 'member_display'}</strong></label> <br />{ctrl_control 'member_display'}
                    </p>
                    <p class="col">                                
                        <label><strong>{ctrl_label 'member_screen'}</strong></label>  <br />{ctrl_control 'member_screen'}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{ctrl_label 'member_mouse'}</strong></label>   <br />{ctrl_control 'member_mouse'}
                    </p>
                    <p class="col">                                
                        <label><strong>{ctrl_label 'member_keyb'}</strong></label>    <br />{ctrl_control 'member_keyb'}
                    </p>
                </div>
            </fieldset>
        </div>
	</div>
	<p class="formsubmit">{formsubmit}</p>
{/form}
</div>