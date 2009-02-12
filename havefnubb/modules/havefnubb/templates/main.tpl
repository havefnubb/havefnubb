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
	
	<div id="navbar" class="up-and-down">
		<ul>
			<li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a></li>
			<li><a href="{jurl 'havefnubb~members:index'}" title="{@havefnubb~main.member.list@}">{@havefnubb~main.member.list@}</a></li>
			<li><a href="{jurl 'hfnusearch~default:index'}" title="{@havefnubb~main.search@}">{@havefnubb~main.search@}</a></li>
			{ifacl2 'hfnu.admin.index'}
			<li><a href="{jurl 'hfnuadmin~default:index'}" title="{@havefnubb~main.admin.panel@}">{@havefnubb~main.admin.panel@}</a></li>
			{/ifacl2}
		</ul>
	</div>

</div>
<!-- #top -->
<!-- #maincontent -->
<div id="maincontent">	
	{$MAIN}
	<!-- .breadcrumb #bottom -->
	<div class="breadcrumb" id="breadcrumbbottom">
	{breadcrumb 5, ' > '}
	</div>
	<!-- .breadcrumb #breadcrumbbottom -->
</div>
<!-- #maincontent -->
<div id="footer" class="up-and-down">
    <p><span>{@havefnubb~main.poweredby@} <a href="http://forge.jelix.org/projects/havefnubb" title="HaveFnu!">HaveFnu!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a></span></p>
</div><!-- #footer -->
</div>