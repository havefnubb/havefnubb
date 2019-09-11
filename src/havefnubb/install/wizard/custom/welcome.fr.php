<?php
include (__DIR__.'/../../version.php');

$versionMsg =   '<ul class="jelix-msg">'.
                '<li class="jelix-msg-item-ok">';

if ($appInstalled) {
	$versionMsg .= 'Version installée : '.$currentVersion['havefnubb']['version'] .
                '</li>'.
                '</ul>';

	if ($alreadyInstalled === false) 
	    $versionMsg .=   '<ul class="jelix-msg">'.
                    '<li class="jelix-msg-item-notice">'.
                    'Nouvelle Version : '.
                    $newVersion.
                    '</li>'.
                    '</ul>';
}
else 
	$versionMsg .= 'Installation de la Version ' . $newVersion . 
                '</li>'.
                '</ul>';

$locales=array(
    'title'=>'Bienvenue',
    'introduction'=>'Introduction',
    'version'=>$versionMsg,
    'process.description'=>'cette procédure d\'installation se décompose en plusieurs parties,
                            allant de la vérification de la compatibilité de votre système jusqu\'à l\'accès
                            à votre base de données en passant par les paramètres du forum.',
    'process.rename.dist.file'=>'Renommer les fichiers de configuration',
    'process.rename.dist.file.desc'=>'Avant de commencer, renommer les fichiers <strong>localconfig.ini.php.dist</strong> en
                                    <strong>localconfig.ini.php</strong> et <strong>profiles.ini.php.dist</strong> en
                                    <strong>profiles.ini.php</strong> se trouvant dans le dossier <strong>var/config</strong>',
    'rights'=>'Droits d\'accès',
    'rights.description'=>'N\'oubliez pas de mettre les bons droits d\'accés sur vos fichiers et répertoire. Ils doivent tous être en
                            lecture seule pour le serveur web, <strong>excepté ces fichiers qui doivent pouvoir être modifiés par le serveur web :</strong>
                            <ol>
                            <li>cache/ et files/</li>
                            <li>havefnubb/var/config/localconfig.ini.php</li>
                            <li>havefnubb/var/config/profiles.ini.php</li>
                            <li>havefnubb/var/config/havefnubb/config.ini.php</li>
                            </ol>
                            <p>Sur une machine windows, vous n\'avez probablement pas à vous occuper de ça.</p>
                            ',
    'rights.debian.title'=>'Droits sur un serveur debian/ubuntu',
    'rights.debian.description'=>'Sur un serveur debian/ubuntu, la plupart du temps ces fichiers doivent avoir www-data comme groupe et avoir le droit de modification
    pour ce groupe. Executez le script havefnubb/install/changerights.sh en ligne de commande'
);
