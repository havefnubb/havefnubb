{meta_html csstheme 'css/hfnucal.css'}
<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'hfnucal~default:index',array('year'=>date('Y'),'month'=>date('m'))}" title="{@hfnucal~main.Calendar@}">{@hfnucal~main.Calendar@}</a></h3>
</div>
{foreach ($datas as $cal)}
    <div class="box">
        <h2><span>{$cal->subject} {$cal->date_created|jdatetime:'timestamp':'lang_datetime'}</span></h2>
        <div class="block">
            {$cal->message|wiki:'hfb_rule'}
        </div>
    </div>
{/foreach}
