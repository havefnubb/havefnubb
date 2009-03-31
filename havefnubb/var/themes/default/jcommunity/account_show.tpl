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
> <a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@havefnubb~member.account.show.edit.your.profile@}</a></li>
{/if}		
	</h3>	
</div>
<div id="post-message">{jmessage}</div>
<div id="profile">
{avatar $j_basepath .'images/avatars/'.$user->id}
<div id="container">
	<ul>
		<li><a href="#member-general"><span>{@havefnubb~member.general@}</span></a></li>		
		<li><a href="#member-pref"><span>{@havefnubb~member.pref@}</span></a></li>
		<li><a href="#member-messenger"><span>{@havefnubb~member.instant.messenger@}</span></a></li>	
		<li><a href="#member-hardware"><span>{@havefnubb~member.hardware@}</span></a></li>		
	</ul>    
    <div class="box">
        <div id="member-general">
            <fieldset>
                <legend>{@havefnubb~member.general@}</legend>
                <div class="two-cols">
                    <p class="col">
                        <label><strong>{@havefnubb~member.nickname@}</strong></label><br />{$user->nickname|eschtml}
                    </p>                   
                    <p class="col">
{if $user->member_show_email == 'Y'}                                             
                        <label><strong>{@havefnubb~member.email@}</strong></label><br />{$user->email|eschtml}
{/if}                        
                    </p>                    
                </div>
                <div>
                    <p class="col">
                        <label><strong>{@havefnubb~member.age@}</strong></label><br />{age $user->member_birth}
                    </p>
                </div>                
            </fieldset>                
            <fieldset>
                <legend>{@havefnubb~member.common.location@}</legend>                  
                <div class="two-cols">
                    <p class="col">                
                        <label><strong>{@havefnubb~member.town@}</strong></label><br />{$user->member_town|eschtml}
                    </p>
                    <p class="col">
                        <label><strong>{@havefnubb~member.country@}</strong></label><br />{$user->member_country|eschtml}
                    </p>
                </div>
                <div>
                    <p class="col">                
                        <label><strong>{@havefnubb~member.website@}</strong></label><br /><a href="{$user->member_website|eschtml}" title="{@havefnubb~member.website@}">{$user->member_website|eschtml}</a>
                    </p>
                </div>
            </fieldset>
            <fieldset>
                <legend>{@havefnubb~member.common.stats@}</legend>
                <div class="two-cols">                    
                    <p class="col">                
                        <label><strong>{@havefnubb~member.common.registered.since@}</strong></label><br />{$user->request_date|jdatetime}</a>
                    </p>
                    <p class="col">                
                        <label><strong>{@havefnubb~member.common.last.connection@}</strong></label><br />{$user->member_last_connect|jdatetime}</a>
                    </p>                    
                </div>                 
            </fieldset>
        </div>
        <div id="member-messenger">
            <fieldset>
                <legend>{@havefnubb~member.instant.messenger@}</legend>
                <div class="two-cols">
                    <p class="col">
                        <label><strong>{@havefnubb~member.xfire@}</strong></label><br />{$user->member_xfire|eschtml}
                    </p>                   
                    <p class="col">
                        <label><strong>{@havefnubb~member.icq@}</strong></label><br />{$user->member_icq|eschtml}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
                        <label><strong>{@havefnubb~member.yim@}</strong></label><br />{$user->member_yim|eschtml}
                    </p>                   
                    <p class="col">
                        <label><strong>{@havefnubb~member.hotmail@}</strong></label><br />{$user->member_hotmail|eschtml}
                    </p>                   
                </div>
                 <div class="two-cols">
                    <p class="col">
                        <label><strong>{@havefnubb~member.aol@}</strong></label><br />{$user->member_aol|eschtml}
                    </p>                   
                    <p class="col">
                        <label><strong>{@havefnubb~member.gtalk@}</strong></label><br />{$user->member_gtalk|eschtml}
                    </p>                   
                </div>                    
                 <div class="two-cols">
                    <p class="col">
                        <label><strong>{@havefnubb~member.jabber@}</strong></label><br />{$user->member_jabber|eschtml}
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
                    <p>
                        <label><strong>{@havefnubb~member.common.language@}</strong></label> {$user->member_language}
                    </p>              
                    <p>
                        <label><strong>{@havefnubb~member.common.account.signature@}</strong></label><br />{$user->member_comment|wiki:'wr3_to_xhtml'|stripslashes}
                    </p>
                </div>                
            </fieldset>
        </div>
		
        <div id="member-hardware">
            <fieldset>
                <legend>{@havefnubb~member.hardware@}</legend>
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.connection@}</strong></label><br />{$user->member_connection|eschtml}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.os@}</strong></label>      <br />{$user->member_os|eschtml}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.proc@}</strong></label>    <br />{$user->member_proc|eschtml}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.mb@}</strong></label>      <br />{$user->member_mb|eschtml}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.card@}</strong></label>    <br />{$user->member_card|eschtml}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.ram@}</strong></label>     <br />{$user->member_ram|eschtml}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.display@}</strong></label> <br />{$user->member_display|eschtml}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.screen@}</strong></label>  <br />{$user->member_screen|eschtml}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.mouse@}</strong></label>   <br />{$user->member_mouse|eschtml}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.keyb@}</strong></label>    <br />{$user->member_keyb|eschtml}
                    </p>
                </div>
            </fieldset>
        </div>
	</div>                
</div>
</div>
