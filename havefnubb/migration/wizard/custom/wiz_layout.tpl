<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
    <title>HaveFnuBB!</title>
    <link type="text/css" href="themes/install/css/install.css" rel="stylesheet" />
    <link type="text/css" href="themes/text.css" rel="stylesheet" />
    <link type="text/css" href="themes/grid.css" rel="stylesheet" />
    <link type="text/css" href="themes/default/css/main_layout.css" rel="stylesheet" />
    <link type="text/css" href="themes/default/css/pages_layout.css" rel="stylesheet" />
    <link type="text/css" href="themes/install/css/nav.css" rel="stylesheet" />
    <link type="text/css" href="themes/install/css/layout.css" rel="stylesheet" />
    <link type="text/css" href="themes/install/css/theme.css" rel="stylesheet" />
    <link type="text/css" href="themes/install/css/pages_theme.css" rel="stylesheet" />
    <link type="text/css" href="themes/ie.css" rel="stylesheet" />
</head>
<body>

<div class="container_16 installwizard">

    <div class="grid_16">
        <h1 id="branding">HaveFnuBB!</h1>
    </div>
    <div class="clear"></div>

    <div class="grid_16">
        <h2 id="page-heading">Where Everything is Fnu</h2>
    </div>
    <div class="clear"></div>

    <div class="grid_16">
        <div class="box">
            <h2>{@home.welcome@}</h2>
        </div>
        <div class="grid_4 alpha">
            <ol id="id">
                <li class="actif">{@install.migrate@} ></li>
            </ol>
        </div>
        <div class="grid_12 omega">
            <form action="migration.php" {if $enctype}enctype="{$enctype}"{/if} method="post">
                <div>
                  <input type="hidden" name="step" value="{$stepname}" />
                  <input type="hidden" name="doprocess" value="1" />
                </div>
                <div class="box">
                {if $messageHeader}<div id="contentheader">{@$messageHeader@}</div>{/if}
                {$MAIN}
                {if $messageFooter}<div id="contentFooter">{@$messageFooter@}</div>{/if}
                    <div id="buttons">
                        {if $previous}
                        <button name="previous"  class="jforms-submit" onclick="location.href='migration.php?step={$previous}';return false;">{@previousLabel@|eschtml}</button>
                        {/if}
                        {if $next}
                        <button type="submit" class="jforms-submit">{@nextLabel@|eschtml}</button>
                        {/if}
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="clear"></div>

    <div class="grid_16" id="site_info">
        <div class="box">
            <p>{@powered.by@} <a href="http://www.havefnubb.org" title="HaveFnuBB!">HaveFnuBB!</a> - &copy; Copyright 2008 - 2012 <a href="http://www.foxmask.info" title="FoxMaSk - Le Grin de Sable">FoxMaSk</a> .</p>
        </div>
    </div>
    <div class="clear"></div>

</div>

</body>
</html>
