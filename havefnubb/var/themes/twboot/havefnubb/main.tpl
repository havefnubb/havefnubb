{*meta_html css $j_basepath.'themes/default/css/main_layout.css'*}
{*meta_html css $j_basepath.'themes/default/css/pages_layout.css'*}
{*meta_html csstheme 'css/layout.css'*}
{*meta_html css $j_basepath.'themes/nav.css'*}
{*meta_html csstheme 'css/theme.css'*}
{*meta_html csstheme 'css/pages_theme.css'*}
{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_basepath.'hfnu/js/bootstrap-tabs.js'}
{meta_html js $j_basepath.'hfnu/js/bootstrap-dropdown.js'}
{meta_html js $j_basepath.'hfnu/js/bootstrap-alerts.js'}
{meta_html csstheme 'css/bootstrap.css'}
{literal}
<script type="text/javascript">
//<[!CDATA 
    $(".alert-message").alert('close');
//]>    
</script>
{/literal}
<div class="topbar">
    <div class="topbar-inner">
        <div class="container">
            <a class="brand" href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.homepage@}">{$TITLE}</a>
            {zone 'havefnubb~menu',array('selectedMenuItem'=>$selectedMenuItem)}
            {zone 'hfnusearch~hfnuquicksearch'}
            {zone 'jcommunity~status'}                    
            </ul>
        </div>
    </div>
    {hook 'hfbMainInHeader'}
</div>
<div style="padding-top: 40px;" class="container">
    <p>{$DESC}</p>    
    {$MAIN}
    <div class="breadcrumb">
        {breadcrumb 8, ' > '}
    </div>
    <div id="footer">
        <p>{@havefnubb~main.poweredby@} <a href="http://www.havefnubb.org" title="HaveFnuBB!">HaveFnuBB!</a> -
            &copy; Copyright 2008 - 2011 <a href="http://www.foxmask.info" title="FoxMaSk'z h0m3">FoxMaSk</a></p>
        {hook 'hfbMainInFooter'}
    </div>
</div>
