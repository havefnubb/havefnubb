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
    <h3>{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)} 
{if $himself}
> <a id="user" class="user-image" href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@havefnubb~member.account.show.edit.your.profile@}</a>
{ifacl2 'auth.users.change.password'}
> <a class="user-edit-password user-image" href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a>
{/ifacl2}        
{/if}
	</h3>	
</div>
<div id="post-message">{jmessage}</div>
<div id="profile">
	<div id="user-profile-avatar">
		{if file_exists('images/avatars/'. $user->id.'.png') }
		{image 'images/avatars/'. $user->id.'.png', array('alt'=>$user->login)}
		{elseif file_exists('images/avatars/'. $user->id.'.jpg')}
		{image 'images/avatars/'. $user->id.'.jpg', array('alt'=>$user->login)}
		{elseif file_exists('images/avatars/'. $user->id.'.jpeg')}
		{image 'images/avatars/'. $user->id.'.jpeg', array('alt'=>$user->login)}
		{elseif file_exists('images/avatars/'. $user->id.'.gif')}
		{image 'images/avatars/'. $user->id.'.gif', array('alt'=>$user->login)}		
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
                <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
{if $user->member_show_email == 'Y'}
{assign $class="three-cols"}
{else}
{assign $class="two-cols"}
{/if}
                <div class="{$class}">
                    <p class="col">
                        <label class="user-name user-image"><strong>{@havefnubb~member.nickname@}</strong></label><br />{$user->nickname|eschtml}
                    </p>                   

{if $user->member_show_email == 'Y'}
                    <p class="col">
                        <label class="user-email user-image"><strong>{@havefnubb~member.email@}</strong></label><br />{nospam $user->email}
                    </p>                                   
{/if}                                
                    <p class="col">
                        <label class="user-birthday user-image"><strong>{@havefnubb~member.common.age@}</strong></label><br />{age $user->member_birth}
                    </p>
                </div>                
            </fieldset>                
            <fieldset>
                <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>                  
                <div class="three-cols">
                    <p class="col">                
                        <label class="user-town user-image"><strong>{@havefnubb~member.common.town@}</strong></label><br />{$user->member_town|eschtml}
                    </p>
                    <p class="col">
                        <label><strong>{@havefnubb~member.common.country@}</strong></label><br />{image 'images/flags/'.$user->member_country.'.gif', array('alt'=>$user->member_country)} {$user->member_country|eschtml}
                    </p>
                    <p class="col">                
                        <label class="user-website user-image"><strong>{@havefnubb~member.common.website@}</strong></label><br /><a href="{$user->member_website|eschtml}" title="{@havefnubb~member.common.website@}">{$user->member_website|eschtml}</a>
                    </p>
                </div>
            </fieldset>
            <fieldset>
                <legend><span class="user-stats user-image">{@havefnubb~member.common.stats@}</span></legend>
                <div class="two-cols">                    
                    <p class="col">                
                        <label><strong>{@havefnubb~member.common.registered.since@}</strong></label><br />{$user->member_created|jdatetime}
                    </p>
                    <p class="col">                
                        <label><strong>{@havefnubb~member.common.last.connection@}</strong></label><br />{$user->member_last_connect|jdatetime:'timestamp'}
                    </p>                    
                </div>                 
            </fieldset>
        </div>
        <div id="user-profile-messenger">
            <fieldset>
                <legend><span class="user-messenger user-image">{@havefnubb~member.instant.messenger@}</span></legend>
                <div class="two-cols">
                    <p class="col">
                        <label class="user-xfire user-image"><strong>{@havefnubb~member.xfire@}</strong></label><br />{$user->member_xfire|eschtml}
                    </p>                   
                    <p class="col">
                        <label class="user-icq user-image"><strong>{@havefnubb~member.icq@}</strong></label><br />{$user->member_icq|eschtml}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
                        <label class="user-yim user-image"><strong>{@havefnubb~member.yim@}</strong></label><br />{$user->member_yim|eschtml}
                    </p>                   
                    <p class="col">
                        <label class="user-msn user-image"><strong>{@havefnubb~member.hotmail@}</strong></label><br />{nospam $user->member_hotmail}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
                        <label class="user-aim user-image"><strong>{@havefnubb~member.aol@}</strong></label><br />{$user->member_aol|eschtml}
                    </p>                   
                    <p class="col">
                        <label class="user-gtalk user-image"><strong>{@havefnubb~member.gtalk@}</strong></label><br />{$user->member_gtalk|eschtml}
                    </p>                   
                </div>                    
                 <div class="two-cols">
                    <p class="col">
                        <label class="user-jabber user-image"><strong>{@havefnubb~member.jabber@}</strong></label><br />{$user->member_jabber|eschtml}
                    </p>                   
                    <p class="col">                        
                    </p>                   
                </div>                    
					
            </fieldset>
        </div>
		
        <div id="user-profile-pref">
            <fieldset>
                <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
                <div>
                    <p>
                        <label><strong>{@havefnubb~member.common.language@}</strong></label> {$user->member_language}
                    </p>              
                    <p>
                        <label><strong>{@havefnubb~member.common.account.signature@}</strong></label>
                    </p>
					{$user->member_comment|wiki:'wr3_to_xhtml'|stripslashes}
                </div>                
            </fieldset>
        </div>
		
        <div id="user-profile-hardware">
            <fieldset>
                <legend><span class="user-hw user-image">{@havefnubb~member.hardware@}</span></legend>
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-connect user-image"><strong>{@havefnubb~member.connection@}</strong></label><br />{$user->member_connection|eschtml}
                    </p>
                    <p class="col">                                
                        <label class="user-os user-image"><strong>{@havefnubb~member.os@}</strong></label>      <br />{$user->member_os|eschtml}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-processor  user-image"><strong>{@havefnubb~member.proc@}</strong></label>    <br />{$user->member_proc|eschtml}
                    </p>
                    <p class="col">                                
                        <label class="user-motherboard user-image"><strong>{@havefnubb~member.mb@}</strong></label>      <br />{$user->member_mb|eschtml}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-card user-image"><strong>{@havefnubb~member.card@}</strong></label>    <br />{$user->member_card|eschtml}
                    </p>
                    <p class="col">                                
                        <label class="user-ram user-image"><strong>{@havefnubb~member.ram@}</strong></label>     <br />{$user->member_ram|eschtml}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-display user-image"><strong>{@havefnubb~member.display@}</strong></label> <br />{$user->member_display|eschtml}
                    </p>
                    <p class="col">                                
                        <label class="user-screen user-image"><strong>{@havefnubb~member.screen@}</strong></label>  <br />{$user->member_screen|eschtml}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label class="user-mouse user-image"><strong>{@havefnubb~member.mouse@}</strong></label>   <br />{$user->member_mouse|eschtml}
                    </p>
                    <p class="col">                                
                        <label class="user-keyboard user-image"><strong>{@havefnubb~member.keyb@}</strong></label>    <br />{$user->member_keyb|eschtml}
                    </p>
                </div>
            </fieldset>
        </div>
    </div>
</div>
