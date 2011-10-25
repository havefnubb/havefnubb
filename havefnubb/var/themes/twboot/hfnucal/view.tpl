{meta_html csstheme 'css/hfnucal.css'}
<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li><a href="{jurl 'hfnucal~default:index',array('year'=>date('Y'),'month'=>date('m'))}" title="{@hfnucal~main.Calendar@}">{@hfnucal~main.Calendar@}</a></li>
</ul>
<div class="hfnucal-list">
{foreach ($datas as $cal)}
    <div class="row well">
        <h3><span>{$cal->subject} {$cal->date_created|jdatetime:'timestamp':'lang_datetime'}</span></h3>
        {$cal->message|wiki:'hfb_rule'}
    </div>
{/foreach}
</div>
