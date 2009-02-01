{literal}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $("#container > ul").tabs();
});
//]]>
</script>
{/literal}
<div id="profile">
<h2 class="profile">{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)}</h2>
{avatar $j_basepath .'images/avatars/'.$user->id}
<div id="container">
	<ul>
		<li><a href="#member-identity"><span>{@havefnubb~member.identity@}</span></a></li>
		<li><a href="#member-pref"><span>{@havefnubb~member.pref@}</span></a></li>
		<li><a href="#member-hardware"><span>{@havefnubb~member.hardware@}</span></a></li>		
	</ul>    
    <div class="box">
        <div id="member-identity">
            <fieldset>
                <legend>{@havefnubb~member.identity@}</legend>
                <div class="two-cols">
                    <p class="col">
                        <label><strong>{@havefnubb~member.nickname@}</strong></label><br />{$user->nickname|eschtml}
                    </p>
                    <p class="col">
                        <label><strong>{@havefnubb~member.email@}</strong></label><br />{$user->email|eschtml}
                    </p>
                </div>
                <div class="two-cols">
                    <p class="col">
                        <label><strong>{@havefnubb~member.age@}</strong></label><br />{age $user->member_birth}
                    </p>
                </div>
                   
                <div class="two-cols">
                    <p class="col">                
                        <label><strong>{@havefnubb~member.town@}</strong></label><br />{$user->member_town|eschtml}
                    </p>
                    <p class="col">
                        <label><strong>{@havefnubb~member.country@}</strong></label><br />{$user->member_country|eschtml}
                    </p>
                </div>                    
            </fieldset>
        </div>
        <div id="member-pref">
            <fieldset>
                <legend>{@havefnubb~member.pref@}</legend>
                <div>
                    <p>
                        <label><strong>{@havefnubb~member.comment@}</strong></label><br />{$user->member_comment}
                    </p>
                </div>
                <div class="two-cols">
                    <p class="col">

                    </p>
                    <p class="col">

                    </p>
                </div>
            </fieldset>
        </div>
		
        <div id="member-hardware">
            <fieldset>
                <legend>{@havefnubb~member.hardware@}</legend>
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.connection@}</strong></label><br />{$user->member_connection}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.os@}</strong></label>      <br />{$user->member_os}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.proc@}</strong></label>    <br />{$user->member_proc}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.mb@}</strong></label>      <br />{$user->member_mb}
                    </p>
                </div>                
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.card@}</strong></label>    <br />{$user->member_card}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.ram@}</strong></label>     <br />{$user->member_ram}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.display@}</strong></label> <br />{$user->member_display}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.screen@}</strong></label>  <br />{$user->member_screen}
                    </p>
                </div>            
                <div class="two-cols">
                    <p class="col">                 
                        <label><strong>{@havefnubb~member.mouse@}</strong></label>   <br />{$user->member_mouse}
                    </p>
                    <p class="col">                                
                        <label><strong>{@havefnubb~member.keyb@}</strong></label>    <br />{$user->member_keyb}
                    </p>
                </div>
            </fieldset>
        </div>
	</div>                
</div>
</div>
{if $himself}
<ul>
    <li><a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@havefnubb~member.account.show.edit.your.profile@}</a></li>
</ul>
{/if}
</div>