{ifuserconnected}
{meta_html js $j_jelixwww.'jquery/jquery.js'}
<script type="text/javascript">
//<![CDATA[
{literal}
var read = {/literal}{urljsstring 'jmessenger~jmessenger:view'}{literal};
$(document).ready(function(){
    $('div.new').click(function(){
        var id = $(this).attr("id");
        $(this).removeClass("new");
        $.post( read +id );
    });
});
{/literal}
//]]>
</script>
    <ul class="menu">
        <li>{zone 'jmessenger~nbNewMessage'}</li>
        <li>
            <ul>
                <li><a href="{jurl 'jmessenger~jmessenger:inbox'}"><span class="user-email-inbox user-image">{@jmessenger~message.msg.inbox@}</span></a></li>
                <li><a href="{jurl 'jmessenger~jmessenger:outbox'}"><span class="user-email-outbox user-image">{@jmessenger~message.msg.outbox@}</span></a></li>
                <li><a href="{jurl 'jmessenger~jmessenger:precreate'}"><span class="user-email-add user-image">{@jmessenger~message.title.new@}</span></a></li>
            </ul>
        </li>
    </ul>
{/ifuserconnected}
