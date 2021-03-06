<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type"/>
    <title>HaveFnuBB!</title>
    <link type="text/css" href="themes/install/css/install.css" rel="stylesheet" />
    <link type="text/css" href="themes/install/css/app.css"     rel="stylesheet" />
    <link type="text/css" href="themes/install/css/hfnu.css"    rel="stylesheet" />
    <link type="text/css" href="themes/install/css/nav.css"     rel="stylesheet" />
    <link type="text/css" href="themes/install/css/theme.css"   rel="stylesheet" />
</head>
<body>
<div id="page">
    <div id="header">
        <div id="branding">
            <h1>HaveFnuBB! - {@version@}</h1>

	</div>
    </div>

    <div id="content">
        <h2 id="page-heading">Where Everything is Fnu</h2>
	<div id="sidebar"> 
            <ol id="id">
            <li {if $stepname == '' or $stepname == 'welcome'}class="actif"{/if}>{@install.update@} ></li>
            <li {if $stepname == 'checkjelix'} class="actif"{/if}>{@install.checking@}</li>
            <li {if $stepname == 'installapp'} class="actif"{/if}>{@install.app@}</li>
            <li {if $stepname == 'end'} class="actif"{/if}>{@install.end@}</li>
            </ol>
        </div>

        <div id="main-install">
            <div class="box-content">
                <div class="box">
                    <h2>{@home.welcome@}</h2>
                </div>
		<div>
                    <form action="update.php" {if $enctype}enctype="{$enctype}"{/if} method="post">
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
                                      <button name="previous"  class="jforms-submit" onclick="location.href='update.php?step={$previous}';return false;">{@previousLabel@|eschtml}</button>
                                    {/if}
                                    {if $next}
                                      <button type="submit" class="jforms-submit">{@nextLabel@|eschtml}</button>
                                    {/if}
                                </div>
                        </div>
                    </form>
                </div>
	       </div>
           </div>
    </div>

    <div id="footer">
        <p>{@powered.by@} <a href="https://havefnubb.jelix.org" title="HaveFnuBB!">HaveFnuBB!</a> -
            &copy; Copyright 2008 - 2012 <a href="https://foxmask.net/" title="FoxMaSk - Le Free de la Passion">FoxMaSk</a> .</p>
    </div>

</div>

</body>
</html>
