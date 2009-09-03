{ifuserconnected}
<script type="text/javascript">
{literal}
$(document).ready(function(){
    $('div.new').click(function(){
        var id = $(this).attr("id");
        $(this).removeClass("new");
        $.post( "/message/read/"+id );
    });
});
{/literal}
</script>
<div class="box">
    <h2><a href="{jurl 'jcommunity~account:edit',array('user'=>$login)}">{@havefnubb~member.edit.account.header@}</a></h2>
    <div class="block">
        <ul class="nav main">
            <li><a href="{jurl 'jcommunity~account:edit',array('user'=>$login)}#user-profile-general">{@havefnubb~member.general@}</a></li>
            <li><a href="{jurl 'jcommunity~account:edit',array('user'=>$login)}#user-profile-pref">{@havefnubb~member.pref@}</a></li>
            <li><a href="{jurl 'jcommunity~account:edit',array('user'=>$login)}#user-profile-messenger">{@havefnubb~member.instant.messenger@}</a></li>	
            <li><a href="{jurl 'jcommunity~account:edit',array('user'=>$login)}#user-profile-hardware">{@havefnubb~member.hardware@}</a></li>
        </ul>
    </div>
</div>

<div class="box menu">
    <h2>{@havefnubb~member.private.messaging@}</h2>
    <div class="block" id="section-menu">         
        <ul class="section menu">
            <li>{zone 'jmessenger~nbNewMessage'}</li>
            <li>
                <ul>
                    <li><a href="{jurl 'jmessenger~jmessenger:inbox'}"><span class="user-email-inbox user-image">{@jmessenger~message.msg.inbox@}</span></a></li>
                    <li><a href="{jurl 'jmessenger~jmessenger:outbox'}"><span class="user-email-outbox user-image">{@jmessenger~message.msg.outbox@}</span></a></li>
                    <li><a href="{jurl 'jmessenger~jmessenger:precreate'}"><span class="user-email-add user-image">{@jmessenger~message.title.new@}</span></a></li>    
                </ul>
            </li>
        </ul>
    </div>        
</div>
{/ifuserconnected}
