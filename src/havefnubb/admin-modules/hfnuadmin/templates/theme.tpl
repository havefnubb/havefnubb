<h1>{@hfnuadmin~theme.themes@}</h1>
<h2>{@hfnuadmin~theme.list@}</h2>
{foreach $themes as $ind=>$theme}
<div class="themelist">
<h3 {if $current_theme == $themes[$ind]['name']} id="current_theme"{/if}>{$themes[$ind]['label']}</h3>
    <a href="{jurl 'theme:useit',array('theme'=>$themes[$ind]['name'])}" title="{@theme.use.this.theme@}">{image $j_basepath.'themes/'.$themes[$ind]['name'].'/theme.png' ,array('alt'=>@theme.use.this.theme@,'height'=>150,'width'=>350)}</a><br/>
    {$themes[$ind]['desc']}<br/>
    {$themes[$ind]['version']} {@theme.created.on@} {$themes[$ind]['dateCreate']|jdatetime:'db_date':'lang_date'}<br/>
</div>
{/foreach}
