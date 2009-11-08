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
<div id="breadcrumbtop" class="headbox box_title">
    <h3>{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)} 
{if $himself}
> <a id="user" class="user-image" href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@havefnubb~member.account.show.edit.your.profile@}</a>
{ifacl2 'auth.users.change.password'}
> <a class="user-edit-password user-image" href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a>
{/ifacl2}        
{else}
> <a href="{jurl 'hfnucontact~default:index',array('to'=>$user->login)}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->login)}">{@havefnubb~member.common.contact.the.member.by.email@}</a>
{/if}
	</h3>	
</div>
<div id="post-message">{jmessage}</div>
<div id="profile">
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
        <ul>
            <li><a href="#user-profile-general"><span class="user-general user-image">{@havefnubb~member.general@}</span></a></li>
            <li><a href="#user-profile-pref"><span class="user-pref user-image">{@havefnubb~member.pref@}</span></a></li>
            <li><a href="#user-profile-messenger"><span class="user-messenger user-image">{@havefnubb~member.instant.messenger@}</span></a></li>	
            <li><a href="#user-profile-hardware"><span class="user-hw user-image">{@havefnubb~member.hardware@}</span></a></li>
        </ul>
        <div id="user-profile-general">
            <fieldset>
                <div class="legend"><span class="user-general user-image">{@havefnubb~member.general@}</span></div>
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
                <div class="legend"><span class="user-location user-image">{@havefnubb~member.common.location@}</span></div>
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
                        {image 'hfnu/images/flags/'.$user->member_country.'.gif', array('alt'=>$user->member_country)} {$user->member_country|eschtml}
						{/if}
                    </div>
                    <div class="form_property">
                        <label class="user-website user-image"><strong>{@havefnubb~member.common.website@}</strong></label>
                    </div>
                    <div class="fom_value">
                        <a href="{$user->member_website|eschtml}" title="{@havefnubb~member.common.website@}">{$user->member_website|eschtml}</a>                        
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>

            </fieldset>
            <fieldset>
                <div class="legend"><span class="user-stats user-image">{@havefnubb~member.common.stats@}</span></div>
                <div class="form_row">                    
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
                <div class="legend"><span class="user-messenger user-image">{@havefnubb~member.instant.messenger@}</span></div>
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
                <div class="legend"><span class="user-pref user-image">{@havefnubb~member.pref@}</span></div>
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
                        {$user->member_comment|wiki:'wr3_to_xhtml'|stripslashes}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>                
            </fieldset>
        </div>
		
        <div id="user-profile-hardware">
            <fieldset>
                <div class="legend"><span class="user-hw user-image">{@havefnubb~member.hardware@}</span></div>
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
    </div>
</div>
