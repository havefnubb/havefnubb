{meta_html css $j_themepath .'css/hfnusearch.css'}

<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@hfnusearch~search.results@}</li>
</ul>

<h3>{@hfnusearch~search.results@}</h3>
<div id="result">
{if $count == 0}
    {@hfnusearch~search.no.result@}
{else}
    <div class="pager-posts">
    {pagelinks 'hfnusearch~default:query', array('hfnu_q'=>$string,'perform_search_in'=>$perform_search_in),
                $count, $page, $resultsPerPage, "page", $properties}
    </div>
{for $i = 0 ; $i < count($datas) ; $i++ }
    <div class="row well">
        <h4 class="result-header"><a href="{jurl 'havefnubb~posts:view',array('thread_id'=>$datas[$i]['thread_id'],'id_post'=>$datas[$i]['id_post'],'id_forum'=>$datas[$i]['id_forum'],'ftitle'=>$datas[$i]['forum_name'],'ptitle'=>$datas[$i]['subject'])}">{$datas[$i]['subject']|eschtml}</a></h4>
        <span class="result-author">posted by {$datas[$i]['login']}</span> - <span class="result-date">{$datas[$i]['date_created']|jdatetime:'timestamp':'lang_datetime'}</span> <br/>
        <div class="result-msg">{$datas[$i]['message']|wiki:'hfb_rule'|stripslashes}</div>
    </div>
{/for}
    <div class="pager-posts">
    {pagelinks 'hfnusearch~default:query', array('hfnu_q'=>$string,'perform_search_in'=>$perform_search_in),
                $count, $page, $resultsPerPage, "page", $properties}
    </div>
{/if}
</div>
