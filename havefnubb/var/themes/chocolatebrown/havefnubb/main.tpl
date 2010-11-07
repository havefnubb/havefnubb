{meta_html csstheme 'css/reset.css'}
{meta_html csstheme 'css/text.css'}
{meta_html csstheme 'css/grid.css'}
{meta_html csstheme 'css/layout.css'}
{meta_html csstheme 'css/nav.css'}
{meta_html cssthemeie 'css/ie.css'}
{meta_html js $j_jelixwww.'jquery/jquery.js'}
<div class="container_16">
    <div class="grid_16 branding">
        <div class="grid_10">
            <h1 id="branding"><a href="{jurl 'havefnubb~default:index'}" >{$TITLE}</a></h1>
        </div>
        <div class="grid_6">
            {zone 'jcommunity~status'}
        </div>
        <div class="clear"></div>
        {hook 'hfbMainInHeader'}
    </div>
    <div class="clear"></div>

    <div class="grid_16">
        {zone 'havefnubb~menu',array('selectedMenuItem'=>$selectedMenuItem)}
    </div>
    <div class="clear"></div>

    <div class="grid_16">
        <h2 id="page-heading">{$DESC}</h2>
    </div>
    <div class="clear"></div>

    <div class="grid_16">
    {$MAIN}
    </div>
    <div class="clear"></div>

    <div class="grid_16">
        {breadcrumb 8, ' > '}
    </div>
    <div class="clear"></div>

    <div class="grid_16" id="site_info">
        <div class="box">
            <p>{@havefnubb~main.poweredby@} <a href="http://www.havefnubb.org" title="HaveFnuBB!">HaveFnuBB!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a>.</p>
        </div>
        {hook 'hfbMainInFooter'}
    </div>
    <div class="clear"></div>
</div>
