<h1>{@hfnuthemes~theme.themes@}</h1>
<h2>{@hfnuthemes~theme.list@}</h2>
{foreach $themes as $ind=>$theme}
<div class="themelist">    
<h3 {if $current_theme == strtolower($themes[$ind]['name'])} id="current_theme"{/if}>{$themes[$ind]['label'][$lang]}</h3>
    <a href="{jurl 'default:useit',array('theme'=>$themes[$ind]['name'])}" title="{@theme.use.this.theme@}">{image $j_basepath.'themes/'.strtolower($themes[$ind]['name']).'/theme.png' ,array('alt'=>@theme.use.this.theme@,'height'=>150,'width'=>300)}</a><br/>
    {$themes[$ind]['description'][$lang]}<br/>
    {$themes[$ind]['version']} {@theme.created.on@} {$themes[$ind]['createddate']|jdatetime:'db_date':'lang_date'}<br/>
</div>
{/foreach}