{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/jquery.toggleElements.pack.js'}
{meta_html css $j_themepath.'css/toggleElements.css'}
{literal}
<script type="text/javascript"> 
$(document).ready(function(){ 
     $('div.toggler-c').toggleElements( 
          { fxAnimation:'slide', fxSpeed:'slow', className:'toggler' } );
     $('div.toggler-quickreply').toggleElements( { fxAnimation:'show', fxSpeed:'slow', className:'toggler' } );    
}); 
</script>
{/literal}
<div id="breadcrumbtop" class="headbox">
    <h3>{@hfnusearch~search.results@}</h3>    
</div>
<div id="result">
{if $count == 0}
{@hfnusearch~search.no.result@}
{else}
{for $i = 0 ; $i < count($datas) ; $i++ }
    <div class="toggler-c opened" title="{$datas[$i]['subject']|eschtml}">
        <h4 class="result-header"><a href="{jurl 'havefnubb~posts:view',array('parent_id'=>$datas[$i]['parent_id'],'id_post'=>$datas[$i]['id_post'],'id_forum'=>$datas[$i]['id_forum'],'ftitle'=>$datas[$i]['forum_name'],'ptitle'=>$datas[$i]['subject'])}">{$datas[$i]['subject']|eschtml}</a></h4>
        <span class="result-author">posted by {$datas[$i]['login']}</span> - <span class="result-date">{$datas[$i]['date_created']|jdatetime:'timestamp':'lang_datetime'}</span> <br/>
        <div class="result-msg">{$datas[$i]['message']|wiki:'wr3_to_xhtml'|stripslashes}</div>
    </div>
{/for}
{/if}
</div>