{meta_html csstheme 'css/app.css'}
{meta_html csstheme 'css/hfnu.css'}
{meta_html csstheme 'css/nav.css'}
{meta_html csstheme 'css/theme.css'}
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
        {zone 'havefnubb~menu',array('selectedMenuItem'=>$selectedMenuItem)}
    </div>
    <div id="content">
    {$MAIN}
    </div>
    <div id="breadcrumb" class="box">
        {breadcrumb 8, ' > '}
    </div>
    <div id="footer">
        <p>{@havefnubb~main.poweredby@} <a href="https://havefnubb.jelix.org" title="HaveFnuBB!">HaveFnuBB!</a> -
            &copy; Copyright 2008-2012 <a href="https://foxmask.net/" title="FoxMaSk - Le Free de la Passion">FoxMaSk</a></p>
        {hook 'hfbMainInFooter'}
    </div>
</div>
