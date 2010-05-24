{meta_html csstheme 'css/hfnucal.css'}
<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@hfnucal~main.Calendar@}</h3>
</div>

<div class="box">
    <div class="block">
    {hfnucalendar array('year'=>$year,'month'=>$month,'day'=>$day,
                        'yearBe'=>$yearBe,'monthBe'=>$monthBe,
                        'yearAf'=>$yearAf,'monthAf'=>$monthAf)}
    </div>
</div>
