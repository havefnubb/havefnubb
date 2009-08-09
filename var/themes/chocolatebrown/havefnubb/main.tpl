{meta_html csstheme 'css/havefnuboard.css'}
{meta_html csstheme 'css/havefnuboard_posts.css'}
{meta_html csstheme 'css/havefnuboard_users.css'}
<div id="hfbody">
<!-- #top -->
<div id="top">
	<div id="title">
		<h1><a href="{jurl 'havefnubb~default:index'}" >{$TITLE}</a></h1><p>{$DESC}</p>
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
    <p><span>{@havefnubb~main.poweredby@} <a href="http://www.havefnubb.org" title="HaveFnuBB!">HaveFnuBB!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a> - Design by <a href="http://www.freecsstemplates.org/">FreeCSSTemplates</a></span></p>
</div>
<!-- #footer -->
</div>