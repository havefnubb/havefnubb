<div id="hfbody">
<!-- #top -->
<div id="top">
	<div id="title">
		<h1>{$TITLE}</h1>
		<h1><span>{$DESC}</span></h1>
	</div>
</div>
<!-- #top -->
<!-- #maincontent -->
<div id="maincontent">	
    <div id="breadcrumbtop" class="headbox up-and-down">   
        <h3>
            <span {if $step == 'home'} class="actif"{/if}>1- Accueil ></span>
            <span {if $step == 'check'} class="actif"{/if}>2- Vérification ></span>
            <span {if $step == 'config'} class="actif"{/if}>3- Configuration ></span>
            <span {if $step == 'dbconfig'} class="actif"{/if}>4- Base de données ></span>
            <span {if $step == 'installdb'} class="actif"{/if}>5- Création des tables ></span>
            <span {if $step == 'end'} class="actif"{/if}>6- Fin</span>    
        </h3>
    </div>
    {$MAIN}
    <div id="breadcrumbbottom" class="headbox up-and-down">
    </div>
</div>
<!-- #maincontent -->
<div id="footer" class="up-and-down">
    <p><span>{@havefnubb~main.poweredby@} <a href="http://forge.jelix.org/projects/havefnubb" title="HaveFnu!">HaveFnu!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a></span></p>
</div><!-- #footer -->
</div>