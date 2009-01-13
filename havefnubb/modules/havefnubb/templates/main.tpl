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
	
	<div id="navbar">
		<ul>
			<li><a href="{jurl 'default:index'}" title="{@main.home@}">{@main.home@}</a></li>
			<li><a href="{jurl 'member:list'}" title="{@main.member.list@}">{@main.member.list@}</a></li>
			<li><a href="{jurl 'search:index'}" title="{@main.search@}">{@main.search@}</a></li>
			{ifacl2 'hfnu.admin.index'}
			<li><a href="">{@main.admin.panel@}</a></li>
			{/ifacl2}
		</ul>
	</div>

</div>
<!-- #top -->

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
</div>