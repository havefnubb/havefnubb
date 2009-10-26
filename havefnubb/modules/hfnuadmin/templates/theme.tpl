<h1>{@hfnuadmin~admin.themes@}</h1>
<h2>{@hfnuadmin~admin.themes.list@}</h2>

{foreach $themes as $ind=>$theme}
<p>
    {image $j_basepath.'themes/'.$themes[$ind]['name'].'theme.png' ,array('alt'=>$themes[$ind]['name'])}
    {$themes[$ind]['label']}<br/>    
    {$themes[$ind]['desc']}<br/>
    {$themes[$ind]['version']}<br/>
    {$themes[$ind]['dateCreate']}
</p>
{/foreach}