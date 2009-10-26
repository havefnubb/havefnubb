<h3>Droits d'accès</h3>
<p>
    n'oubliez pas de passer vos dossiers en 755 et vos fichiers en 644, <strong>à l'exception des 4 fichiers de configurations situés dans var/config avec le mode suivant 664 : defaultconfig.ini.php, dbprofils.ini.php, havefnu.ini.php, flood.ini.php.</strong> et du dossier havefnubb/modules/hfnuinstall/install/sql en 755<br/>
    <h4>changer les droits sur les répertoires</h4>
    <pre>{literal}find . -type d -exec chmod 755 {} \;{/literal}</pre>
    <h4>changer les droits sur les fichiers</h4>
    <pre>{literal}find . -type f -exec chmod 644 {} \;{/literal}</pre>    
</p>
