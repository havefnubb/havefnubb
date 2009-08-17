{entete 'openwechange~entete', array('title'=>$title, 'class'=>'color1')}
    <div class="span-8">
        {$nb_nv_msg}
    </div>
    
    <!--     <div class="span-9 last tright">
            machins reçus
        </div> -->
{/entete}

<div class="span-18 last tright">
    <a href="{jurl 'jmessenger~message:precreate'}">{@message.action.new@}</a> |
    <a href="#send">{@message.action.goto.send@}</a> |
    <a href="#archive">{@message.action.goto.archive@}</a>
</div>

{wechangemessage}

<p></p>

{$received}

<hr />

<div id="send">
    {$send}
</div>

<hr />


<div id="archive" class="span-18 last archives">
    <a id="getarchive" class="tright" href="{jurl 'jmessenger~message:getArchive'}">{@message.getarchives@}</a>
</div>


<script type="text/javascript">
{literal}
JQ(document).ready(function(){
    JQ('div.new').click(function(){
        var id = JQ(this).attr("id");
        JQ(this).removeClass("new");
        JQ.post( "/message/read/"+id );
    });
    
    JQ('#getarchive').click(function(){
        JQ("#archive").load( "/message/getArchive" );
		return false;
    });
});
{/literal}
</script>