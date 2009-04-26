{meta_html css $j_themepath.'css/downloads_main.css'}
<div id="downloads-content">
	<div id="breadcrumbtop" class="headbox">
		<h3>> {$path}</h3>
	</div>
	<div id="downloads-title">
		<h3><span>Downloads</span></h3>
	</div>
	{if $message != ''}
	<div id="downloads-messagebox">
		{for $i=0;$i < $nb_msg ; $i++}
			{$message[$i]}
		{/for}
	</div>
	{/if}
	
	{if $popular == 0 and $lastest == 0}
	<div class="downloads-headings">
		<h3><span><img src="{$j_themepath}img/download.png" alt="the downloads"/> The Downloads of the directory "{$path}"</span></h3>
	</div>
	<table class="downloads">
		<tr>
			<th>Name</th>
			<th>Nb Downloads</th>
			<th>Date</th>      
		</tr>
		{foreach $downloads as $download}
		<tr>
			<td class="downloads-colleft"><a href="{jurl 'downloads~default:view',array('url'=>$download->dl_url,'dir'=>$download->dl_path)}" title="Details of this download">{$download->dl_name}</a></td>        
			<td class="downloads-colnum">{$download->dl_count}</td>
			<td class="downloads-colright">{$download->dl_date|jdatetime:'db_date':'lang_datetime'}</td>        
		</tr>
		{/foreach}    
	</table>

	{/if}
	{pagelinks 'downloads~default:index', array('dir'=>$path),  $total, $offset, $nbPerPage, 'offset'}
	{if $popular}
	<div class="downloads-headings">
		<h3><span><img src="{$j_themepath}img/pop_download.png" alt="Most popular downloads"/> Most popular downloads</span></h3>
	</div>
	<table class="downloads">
		<tr>
        <th>Name</th>
        <th>Nb Downloads</th>
        <th>Date</th>          
		</tr>
		{foreach $populars as $popular}
		<tr>'
			<td><a href="{jurl 'downloads~default:view',array('url'=>$popular->dl_url,'dir'=>$download->dl_path)}" title="Détails de ce téléchargement">{$popular->dl_name}</a></td>        
			<td>{$popular->dl_count}</td>
			<td>{$popular->dl_date|jdatetime:'db_date':'lang_datetime'}</td>        
		</tr>
		{/foreach}
	</table>

	{/if}
	{if $lastest}
	<div class="downloads-headings">	
		<h3><span><img src="{$j_themepath}img/last_download.png" alt="lastest downloads"/> Latests added downloads</h3>
	</div>		
	<table class="downloads">
		<tr>
			<th>Nom</th>
			<th>Date</th>
		</tr>
		{foreach $lastests as $lastest}
		<tr>'
			<td><a href="{jurl 'downloads~default:view',array('url'=>$lastest->dl_url,'dir'=>$download->dl_path)}" title="Détails de ce téléchargement">{$lastest->dl_name}</a></td>
			<td>{$lastest->dl_date|jdatetime:'db_date':'lang_datetime'}</td>              
		</tr>
		{/foreach}
	</table>
	{/if}
</div><!-- #content -->
