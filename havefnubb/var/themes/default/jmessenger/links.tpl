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
<div id="breadcrumbtop" class="headbox">
    <h3 id="user" class="user-image"><a href="{jurl 'jcommunity~account:edit',array('user'=>$login)}">{@havefnubb~member.edit.account.header@}</a> - <span class="user-private-message user-image">{@havefnubb~member.internal.messenger@}</span>
{ifacl2 'auth.users.change.password'}
> <a class="user-edit-password user-image" href="{jurl 'havefnubb~members:changepwd', array('user'=>$login)}">{@havefnubb~member.pwd.change.of.password@}</a>
{/ifacl2}    
    </h3>
</div>

<div id="messenger-index">
<p>{zone 'jmessenger~nbNewMessage'}</p>
<ul>
    <li class="user-email-inbox user-image"><a href="{jurl 'jmessenger~jmessenger:inbox'}">{@jmessenger~message.msg.inbox@}</a></li>
    <li class="user-email-outbox user-image"><a href="{jurl 'jmessenger~jmessenger:outbox'}">{@jmessenger~message.msg.outbox@}</a></li>
    <li class="user-email-add user-image"><a href="{jurl 'jmessenger~jmessenger:precreate'}">{@jmessenger~message.title.new@}</a></li>
</ul>
</div>
{/ifuserconnected}
