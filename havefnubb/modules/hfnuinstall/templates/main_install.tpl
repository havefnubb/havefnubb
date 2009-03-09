<!-- #contener -->
<div id="contener">

    <div id="top">
        <div>
            <h1>{$TITLE}<br/>
            <span>{$DESC}</span></h1>
        </div>
    </div>
    <!-- #top -->
    
    <!-- #page -->
    <div id="page">
        <div id="content">
        {$MAIN}
        </div>
    </div>    
    <!-- #page -->
    
    <!-- #sidebar -->
    <div id="sidebar">
        <ul>
            <li {if $step == 'home'} class="actif"{/if}>Accueil</li>
            <li {if $step == 'check'} class="actif"{/if}>Vérification</li>
            <li {if $step == 'config'} class="actif"{/if}>Configuration</li>
            <li {if $step == 'dbconfig'} class="actif"{/if}>Base de données</li>
            <li {if $step == 'installdb'} class="actif"{/if}>Création des tables</li>
            <li {if $step == 'end'} class="actif"{/if}>Fin</li>    
        </ul>      
    </div>

</div>
<!-- #contener -->

<div id="footer">
    <p><span>{@havefnubb~main.poweredby@} <a href="http://forge.jelix.org/projects/havefnubb" title="HaveFnu!">HaveFnu!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a></span></p>
</div><!-- #footer -->
