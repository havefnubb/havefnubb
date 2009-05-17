{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.core.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/ui.tabs.min.js'}
<div id="hfbody">
<!-- #top -->
<div id="top">
	<div id="title">
		<h1>{$TITLE}</h1>
		<h1><span>{$DESC}</span></h1>
	</div>
	<!-- #login-status -->
	{zone 'jcommunity~status'}
	<!-- #login-status -->
	<!-- main menu -->
	{zone 'havefnubb~menu',array('selectedMenuItem'=>$selectedMenuItem)}
	<!-- main menu -->
</div>
<!-- #top -->
<!-- #maincontent -->
<div id="maincontent">	
	{$MAIN}
	<!-- .breadcrumb #breadcrumbbottom -->
	<div class="breadcrumb" id="breadcrumbbottom">
	{breadcrumb 5, ' > '}
	</div>
	<!-- .breadcrumb #breadcrumbbottom -->
</div>
<!-- #maincontent -->
<!-- #footer -->
<div id="footer" class="up-and-down">
    <p><span>{@havefnubb~main.poweredby@} <a href="http://forge.jelix.org/projects/havefnubb" title="HaveFnu BB!">HaveFnu BB!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a></span></p>
</div>
{if $home == 1}
<div id="statsinfos" class="four-cols">
	{zone 'havefnubb~lastposts'}
	{zone 'havefnubb~stats'}
	<div id="home-links" class="col">
		<div class="headings_footer">
			<h3><span>{@havefnubb~main.home.links@}</span></h3>
        </div>
		<ul>
			<li><a href="{jurl 'havefnubb~default:index'}">Accueil</a></li>
			<li><a href="{jurl 'hfnucontact~default:index'}">Contact</a></li>
		</ul>
	</div>
	<p class="col">
	</p>
<p class='clearboth'>&nbsp;</p>	
</div>	
{/if}
<!-- #footer -->

</div>