{meta_html csstheme 'css/hfnucal.css'}
<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@hfnucal~main.Calendar@}</li>
</ul>
<h4>{@hfnucal~main.this.page.display.the.posts.make.day.by.day@}</h4>
<div class="row">
    <div class="span16">
    {hfnucalendar array('year'=>$year,'month'=>$month,'day'=>$day,
                        'yearBe'=>$yearBe,'monthBe'=>$monthBe,
                        'yearAf'=>$yearAf,'monthAf'=>$monthAf)}
    </div>
</div>
