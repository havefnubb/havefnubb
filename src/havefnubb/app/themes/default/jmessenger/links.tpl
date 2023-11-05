{ifuserconnected}
<script type="text/javascript">
{literal}
$(document).ready(function(){
    $('div.new').click(function(){
        var id = $(this).attr("id");
        var url = {/literal}{urljsstring 'jmessenger~jmessenger:view', array(), array('id'=>'id')}{literal}
        $(this).removeClass("new");
        $.post(url);
    });
});
{/literal}
</script>

<div class="box" id="jmessenger-menu">
    <h3>{@havefnubb~member.private.messaging@}</h3>
    <div class="box-content">
        <ul class="nav">
            <li><a href="{jurl 'jmessenger~jmessenger:inbox'}"><span class="user-email-inbox user-image">{@jmessenger~message.msg.inbox@}</span></a></li>
            <li><a href="{jurl 'jmessenger~jmessenger:outbox'}"><span class="user-email-outbox user-image">{@jmessenger~message.msg.outbox@}</span></a></li>
            <li><a href="{jurl 'jmessenger~jmessenger:precreate'}"><span class="user-email-add user-image">{@jmessenger~message.title.new@}</span></a></li>
        </ul>
        {zone 'jmessenger~nbNewMessage'}
    </div>
</div>
{/ifuserconnected}
