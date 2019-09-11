{meta_html assets 'havefnubb'}
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
        <p>{@havefnubb~main.poweredby@} <a href="https://havefnubb.jelix.org" title="HaveFnuBB!">HaveFnuBB!</a></p>
        {hook 'hfbMainInFooter'}
    </div>
</div>
