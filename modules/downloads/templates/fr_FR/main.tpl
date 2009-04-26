<div id="contener">
<div id="top">
	<h1>Les Téléchargements {$ADMINTITLE}</h1>
	<div id="prelude">
		<p id="accesslinks">
			<a href="#content">Aller au contenu</a>
            <a href="#sidebar">Aller au menu</a>
		</p>
	</div>
</div>
<div id="page">
    <div id="content">
    {$MAIN}
    </div><!-- #content -->
</div><!-- #page -->
<div id="sidebar">    
    {ifuserconnected}
    <h2>Identité</h2>
    {zone ('jcommunity~status')}
    {else}
    {zone ('jcommunity~login')}
    {/ifuserconnected}
    {zone 'downloads~block',array('dir'=>'files')}
	{zone 'downloads~block',array('dir'=>'jelix')}
</div>
<div class="clearer"><!-- --></div>
<div id="footer">
    <p><span>&copy; Copyright 2008 FoxMaSk</span></p>
</div><!-- #footer -->
</div><!-- #contener -->