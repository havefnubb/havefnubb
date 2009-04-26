{meta_html css $j_themepath.'css/downloads_main.css'}
<div id="downloads-content">
	<div id="breadcrumbtop" class="headbox">
		<h3>> <a href="{jurl 'downloads~default:index',array('dir'=>$path)}">{$path}</a> > {$download->dl_name|escxml}</h3>
	</div>	
	<div id="downloads-title">
		<h3><span>Télécharger</span></h3>
	</div>
	<div id="downloads-downloadit">
		<h3>{$download->dl_name|escxml}</h3>
		{$download->dl_desc}<br/>
		{if $allowGuest == 1}	
		<a href="{jurl 'downloads~default:dl' , array('id'=>$download->id)}" title="télécharger le fichier"><img src="{$j_themepath}img/download.png" alt="télécharger"/> Télécharger</a><br/>
		{else}
		{ifuserconnected}	
		<a href="{jurl 'downloads~default:dl' , array('id'=>$download->id)}" title="télécharger le fichier"><img src="{$j_themepath}img/download.png" alt="télécharger"/> Télécharger</a><br/>
		{else}
		<strong>Vous devez être membre du site ou être connecté, pour télécharger ce fichier</strong><br/><br/>
		{/ifuserconnected}
		{/if}
		.: Dernière mise à jour {$download->dl_date|jdatetime:'db_date':'lang_datetime'} - {$filesize} - <em>Téléchargé <strong>{$download->dl_count}</strong> fois</em> :.
	</div>
</div><!-- #downloads-content -->
