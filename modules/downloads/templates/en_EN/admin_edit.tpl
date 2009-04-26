{zone ('downloads~adminmenu')}
<h2><img src="{$j_themepath}img/get_download.png" alt="download"/> Downloads</h2>

{if $preview == 1}
<div id="previewbox">
	<h2>Preview</h2>
	<h3>>> {$name}</h3>
	<h4>URL : {$url}</h4>
	<h5>Filename : {$filename}</h5>
	Description: {$description}<br/>
	.: Last update {$date|jdatetime:'db_date':'lang_datetime'} - {$filesize} - <em>Downloaded <strong>{$counter}</strong> times</em> :.
</div>
{/if}

{form $form, 'downloads~mgr:manage'}
	{ctrl_control 'id'}	
	{ctrl_control 'login'}
	
	<p class="field">{ctrl_label 'dl_name'} {ctrl_control 'dl_name'}</p>
	<p class="field">{ctrl_label 'dl_url'} {ctrl_control 'dl_url'}<br/>
	<span class="desc">Let this field empty if you want your <acronym title="Uniform Resource Locator">URL</acronym> being automatically generated from the name given above</p></span>
	</p>
	   
	<p class="field">{ctrl_label 'dl_path'} {ctrl_control 'dl_path'}</p>
    {if $filename != ''}
    <div id="trash"><a href="#" title="delete ?" >{$filename}</a></div>
    {/if}	
	<p class="field" id="newfilename">{ctrl_label 'dl_filename'} {ctrl_control 'dl_filename'}</p>
	
    <p class="field clearb">{ctrl_label 'dl_desc'}</p>
	<p class="area">{ctrl_control 'dl_desc'}</p>
    
	<div class="two-cols">
        <p class="field col">{ctrl_label 'dl_date'} {ctrl_control 'dl_date'}</p>
        <p class="field col">{ctrl_label 'dl_count'} {ctrl_control 'dl_count'}</p>
	</div>
		
	<div class="two-cols">
		<p class="col"><span class="fakelabel">{ctrl_label 'dl_enable'}</span> {ctrl_control 'dl_enable'}</p>
		<p class="col"><span class="fakelabel">{ctrl_label 'dl_on_block'}</span> {ctrl_control 'dl_on_block'}</p>		
	</div>
        
    <div> {formsubmit 'validate'}</div>
{/form}
