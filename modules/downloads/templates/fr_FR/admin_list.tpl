{if $message != ''}
<div id="messagebox">
    {for $i=0;$i < $nb_msg ; $i++}
        {$message[$i]}
    {/for}
</div>
{/if}

<h1><img src="{$j_themepath}img/downloads.png" alt="téléchargements"/> Liste des téléchargements existants</h1>
<table class="downloads" cellpadding="0" cellspacing="0">
    {assign $i = 0}
    {assign $dir = ''}
    {foreach $downloads as $download}    
    {assign $class = ($i%2 == 0) ? 'even' : 'odd'}
    {if $dir != $download->dl_path}
    {assign $dir = $download->dl_path}
    <tr>
        <td colspan="7" class="header"><a href="{jurl 'downloads~default:index',array('dir'=>$download->dl_path)}" title="voir le contenu de ce répertoire sur le site"><img src="{$j_themepath}img/zoom.png" alt="voir le contenu du répertoire sur le site"/>Contenu du Répertoire</a> :{$download->dl_path}</td>
    </tr>                
    <tr>
        <th class="header2"></th>
        <th class="header2">Nom</th>
        <th class="header2">Date</th>
        <th class="header2">Nb Téléchargements</th>            
        <th class="header2">Etat</th>
        <th class="header2">Dans le Bloc ?</th>    
        <th class="header2">Action</th>
    </tr>
    {/if}    
    <tr>
        <td class="{$class} name">{if $download->dl_filename == ''}<img src="{$j_themepath}img/warning.png" alt="téléchargement sans fichier, penser à rajouter un fichier"/>{else}<img src="{$j_themepath}img/ico_file.png" alt="téléchargement avec fichier"/>{/if}</td>
        <td class="{$class} name"><a href="{jurl 'downloads~mgr:manage',array('id'=>$download->id)}" title="Editer ce téléchargement">{$download->dl_name}</a></td>
        <td class="{$class}">{$download->dl_date|jdatetime:'db_date':'lang_datetime'}</td>              
        <td class="{$class}">{$download->dl_count}</td>
        <td class="{$class}">{if $download->dl_enable == 1}<a href="{jurl 'downloads~mgr:dl_disable',array('id'=>$download->id)}" title="désactiver"><img src="{$j_themepath}img/on.png" alt="actif"/></a>{else}<a href="{jurl 'downloads~mgr:dl_enable',array('id'=>$download->id)}" title="activer"><img src="{$j_themepath}img/off.png" alt="inactif"/></a>{/if}</td>
        <td class="{$class}">{if $download->dl_on_block}<a href="{jurl 'downloads~mgr:dl_not_on_block',array('id'=>$download->id)}" title="oter du bloc"><img src="{$j_themepath}img/ok.png" alt="Oui"/></a>{else}<a href="{jurl 'downloads~mgr:dl_on_block',array('id'=>$download->id)}" title="ajouter au bloc"><img src="{$j_themepath}img/ko.png" alt="Non"/></a>{/if}</td>
        <td class="{$class}">
        <a href="{jurl 'downloads~mgr:delete',array('id'=>$download->id)}" title="Supprimer ce téléchargement ?"><img src="{$j_themepath}img/delete.png" alt="supprimer" onclick="return confirm('Etes vous sûr de vouloir supprimer ce téléchargement ?')"/></a>
        {if $download->dl_filename != ''}<a href="{jurl 'downloads~mgr:trash',array('id'=>$download->id)}" title="Supprimer le fichier de ce téléchargement ?"><img src="{$j_themepath}img/trash.png" alt="supprimer" onclick="return confirm('Etes vous sûr de vouloir supprimer le fichier de ce téléchargement ?')"/></a>{else}<img src="{$j_themepath}img/warning.png" alt="téléchargement sans fichier"/>{/if}
        </td>
    </tr>
    {assign $i++}
    {/foreach}   
</table>
    