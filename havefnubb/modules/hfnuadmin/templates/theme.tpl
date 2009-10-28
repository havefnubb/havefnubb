<h1>{@hfnuadmin~theme.themes@}</h1>
<h2>{@hfnuadmin~theme.list@}</h2>
{foreach $themes as $ind=>$theme}
<h3>{$themes[$ind]['label']}</h3>
    {if $current_theme == $themes[$ind]['name']}
<h4>{@theme.current.theme@}</h4>
    {/if}
<p>
    <a href="{jurl 'theme:useit',array('theme'=>$themes[$ind]['name'])}" title="{@theme.use.this.theme@}">{image $j_basepath.'themes/'.$themes[$ind]['name'].'/theme.png' ,array('alt'=>@theme.use.this.theme@,'height'=>150,'width'=>400)}</a><br/>    
    {$themes[$ind]['desc']}<br/>
    {$themes[$ind]['version']} {@theme.created.on@} {$themes[$ind]['dateCreate']|jdatetime:'db_date':'lang_date'}
</p>
{/foreach}