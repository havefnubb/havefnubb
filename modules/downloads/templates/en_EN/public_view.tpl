{meta_html css $j_themepath.'css/downloads_main.css'}
<div id="downloads-content">
	<div id="breadcrumbtop" class="headbox">
		<h3>> <a href="{jurl 'downloads~default:index',array('dir'=>$path)}">{$path}</a> > {$download->dl_name|escxml}</h3>
	</div>	
	<div id="downloads-title">
		<h3><span>To Download</span></h3>
	</div>
	<div id="downloads-downloadit">
		<h3>{$download->dl_name|escxml}</h3>
		{$download->dl_desc}<br/>
		{if $allowGuest == 1}	
		<a href="{jurl 'downloads~default:dl' , array('id'=>$download->id)}" title="download this file"><img src="{$j_themepath}img/download.png" alt="to download"/> to download</a><br/>
		{else}
		{ifuserconnected}	
		<a href="{jurl 'downloads~default:dl' , array('id'=>$download->id)}" title="download this file"><img src="{$j_themepath}img/download.png" alt="to download"/> to download</a><br/>
		{else}
		<strong>You are not a member of the website or you are not connected to download this file</strong><br/><br/>
		{/ifuserconnected}
		{/if}
		.: Last update {$download->dl_date|jdatetime:'db_date':'lang_datetime'} - {$filesize} - <em>Downloaded <strong>{$download->dl_count}</strong> fois</em> :.
	</div>
</div><!-- #downloads-content -->