{meta_html css $j_basepath.'themes/default/css/main_layout.css'}
{meta_html css $j_basepath.'themes/default/css/pages_layout.css'}
{meta_html csstheme 'css/layout.css'}
{meta_html css $j_basepath.'themes/default/css/nav.css'}
{meta_html csstheme 'css/theme.css'}
{meta_html csstheme 'css/pages_theme.css'}
{meta_html js $j_jelixwww.'jquery/jquery.js'}
<div id="page">
    <div id="header">
        <div id="branding">
            <h1><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.homepage@}">{$TITLE}</a></h1>
            <p>{$DESC}</p>
        </div>
        {zone 'jcommunity~status'}
        {hook 'hfbMainInHeader'}
    </div>
    <div id="menubar">
        {ifacl2 'hfnu.admin.index'}
        {zone 'havefnubb~menu',array('selectedMenuItem'=>$selectedMenuItem,'admin'=>true)}
        {else}
        {zone 'havefnubb~menu',array('selectedMenuItem'=>$selectedMenuItem,'admin'=>false)}
        {/ifacl2}
    </div>
    <div id="content">
    {$MAIN}
    </div>
    <div id="breadcrumb" class="box">
        {breadcrumb 8, ' > '}
    </div>
    <div id="footer">
        <p>{@havefnubb~main.poweredby@} <a href="http://www.havefnubb.org" title="HaveFnuBB!">HaveFnuBB!</a> -
            &copy; Copyright 2008 - 2011 <a href="http://www.foxmask.info" title="FoxMaSk'z h0m3">FoxMaSk</a></p>
        {hook 'hfbMainInFooter'}
    </div>
</div>
