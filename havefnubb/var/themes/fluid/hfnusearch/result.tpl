{meta_html css $j_themepath .'css/hfnusearch.css'}
<div class="box">
    <h2>{@hfnusearch~search.results@}</h2>
    <div class="block">
        <div id="result">
    {if $count == 0}
        {@hfnusearch~search.no.result@}
    {else}
    {for $i = 0 ; $i < count($datas) ; $i++ }
        <div class="result-line">            
            <h4 class="result-header"><a href="{jurl 'havefnubb~posts:view',array('parent_id'=>$datas[$i]['parent_id'],'id_post'=>$datas[$i]['id_post'],'id_forum'=>$datas[$i]['id_forum'],'ftitle'=>$datas[$i]['forum_name'],'ptitle'=>$datas[$i]['subject'])}">{$datas[$i]['subject']|eschtml}</a></h4>
            <span class="result-author">posted by {$datas[$i]['login']}</span> - <span class="result-date">{$datas[$i]['date_created']|jdatetime:'timestamp':'lang_datetime'}</span> <br/>
            <div class="result-msg">{$datas[$i]['message']|wiki:'wr3_to_xhtml'|stripslashes}</div>
        </div>
    {/for}
    {/if}
        </div>
    </div>
</div>
