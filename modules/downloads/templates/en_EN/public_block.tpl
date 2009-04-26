<h2>Downloads Block</h2>
<div class="block">
    {foreach $downloads as $download}
    <ul>
        <li><a href="{jurl 'downloads~default:view',array('url'=>$download->dl_url)}" title="Details of this download">{$download->dl_name|escxml}</a> ({$download->dl_count})</li>
    </ul>
    {/foreach}
</div>