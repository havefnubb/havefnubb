<h1><img src="{$j_themepath}img/download.png" alt="configuration"/> Téléchargements : Configuration</h1>
{if $message != ''}
<div id="messagebox">
	{for $i=0;$i < $nb_msg ; $i++}
		{$message[$i]}
	{/for}
</div>
{/if}		
{form $form, 'downloads~mgr:config'}
	<fieldset>
		<legend>Permettre ...</legend>
		
		<p><span class="fakelabel">Aux invités de télécharger ?</span></p>
        <p>{ctrl_label 'guest'}<br/> {ctrl_control 'guest'}</p>
        <span class="desc">Si oui, tout le monde pourra télécharger ; si non, seuls les membres du site le pourront.</span>
		
		<p><span class="fakelabel">Les liens externes (à votre site) ?</span></p>
        <p>{ctrl_label 'external_links'}<br/> {ctrl_control 'external_links'}</p>
        <span class="desc">Permet d'autoriser ou non les liens directs sur les fichiers sans passer par votre site.</span>
	</fieldset>	
	<fieldset>
		<legend>Afficher sur l'accueil du module</legend>

		<p class="field">{ctrl_label 'number_downloads_on_home'} {ctrl_control 'number_downloads_on_home'}</p>
        <span class="desc">Indiquez le nombre de téléchargements à afficher sur le module.</span>

		<p><span class="fakelabel">{ctrl_label 'last_downloads_on_home'}<br/>{ctrl_control 'last_downloads_on_home'}</span>
		<span class="desc">Permet d'afficher sur l'accueil du module les derniers téléchargements ajoutés.</span></p>

		<p><span class="fakelabel">{ctrl_label 'most_popular_downloads_on_home'}<br/> {ctrl_control 'most_popular_downloads_on_home'}</span>
		<span class="desc">Permet d'afficher sur l'accueil du module les téléchargements les plus populaires toutes catégories confondues.</span></p>
	</fieldset>
    <p>{formsubmit '_submit'}</p>
{/form}
