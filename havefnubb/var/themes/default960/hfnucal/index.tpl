{meta_html csstheme 'css/hfnucal.css'}
<div class="breadcrumb">
    <ol>
        <li>{@havefnubb~main.common.you.are.here@}</li>
        <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@hfnucal~main.Calendar@}</li>
    </ol>
</div>
<h3>{@hfnucal~main.this.page.display.the.posts.make.day.by.day@}</h3>
<div class="box">
    <div class="block">
    {hfnucalendar array('year'=>$year,'month'=>$month,'day'=>$day,
                        'yearBe'=>$yearBe,'monthBe'=>$monthBe,
                        'yearAf'=>$yearAf,'monthAf'=>$monthAf)}
    </div>
</div>
