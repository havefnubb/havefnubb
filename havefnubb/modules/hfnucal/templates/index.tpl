{meta_html csstheme 'css/hfnucal.css'}
<h2>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@hfnucal~main.Calendar@}</h2>
<h3>{@hfnucal~main.this.page.display.the.posts.make.day.by.day@}</h3>
<div class="box">
    <div class="box-content">
    {hfnucalendar array('year'=>$year,'month'=>$month,'day'=>$day,
                        'yearBe'=>$yearBe,'monthBe'=>$monthBe,
                        'yearAf'=>$yearAf,'monthAf'=>$monthAf)}
    </div>
</div>
