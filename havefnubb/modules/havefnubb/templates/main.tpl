{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.core.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.tabs.min.js'}

<!-- #top -->
<div id="top">
	
    <h1>{$TITLE}</h1>
	<h1><span>{$DESC}</span></h1>
</div>
<!-- #top -->

<!-- #login-status -->
{zone 'jcommunity~status'}
<!-- #login-status -->

<div id="navbar">
	<ul><li>{@main.home@}</li></ul>
</div>

<!-- #maincontent -->
<div id="maincontent">
	{$MAIN}
	<!-- #breadcrumbbottom -->
	<div id="breadcrumbbottom">
	{breadcrumb 5, ' > '}
	</div>
	<!-- #breadcrumbbottom -->
</div>
<!-- #maincontent -->

<div id="footer">
    <p><span>{@havefnubb~main.poweredby@} <a href="http://forge.jelix.org/projects/havefnubb" title="HaveFnu!">HaveFnu!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a></span></p>
</div><!-- #footer -->
