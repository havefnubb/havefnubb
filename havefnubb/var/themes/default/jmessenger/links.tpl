{ifuserconnected}
<div id="breadcrumbtop" class="headbox">
    <h3 id="user"><a href="{jurl 'jcommunity~account:edit',array('user'=>$login)}">{@havefnubb~member.edit.account.header@}</a> - <span class="user-email">{@havefnubb~member.internal.messenger@}</span>
{ifacl2 'auth.users.change.password'}
> <a class="user-edit-password" href="{jurl 'havefnubb~members:changepwd', array('user'=>$login)}">{@havefnubb~member.pwd.change.of.password@}</a>
{/ifacl2}    
    
    </h3>
</div>

<p>{zone 'jmessenger~nbNewMessage'}</p>

<ul>
    <li class="user-email-inbox"><a href="{jurl 'jmessenger~jmessenger:inbox'}">{@jmessenger~message.msg.inbox@}</a></li>
    <li class="user-email-outbox"><a href="{jurl 'jmessenger~jmessenger:outbox'}">{@jmessenger~message.msg.outbox@}</a></li>
    <li class="user-email-add"><a href="{jurl 'jmessenger~jmessenger:precreate'}">{@jmessenger~message.title.new@}</a></li>
</ul>
{/ifuserconnected}