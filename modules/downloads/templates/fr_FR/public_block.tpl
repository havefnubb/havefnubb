<h2>Bloc Téléchargements</h2>
<div class="downloads-block">
    {foreach $downloads as $download}
    <ul>
        <li><a href="{jurl 'downloads~default:view',array('dir'=>$download->dl_path,'url'=>$download->dl_url)}" title="Détails de ce téléchargement">{$download->dl_name|escxml}</a> ({$download->dl_count})</li>
    </ul>
    {/foreach}
</div>