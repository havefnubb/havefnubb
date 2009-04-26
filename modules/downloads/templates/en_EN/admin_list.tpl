{if $message != ''}
<div id="messagebox">
    {for $i=0;$i < $nb_msg ; $i++}
        {$message[$i]}
    {/for}
</div>
{/if}
{zone ('downloads~adminmenu')}
<h2><img src="{$j_themepath}img/downloads.png" alt="téléchargements"/> List of existing downloads</h2>
<table class="downloads" cellpadding="0" cellspacing="0">
    {assign $i = 0}
    {assign $dir = ''}
    {foreach $downloads as $download}    
    {assign $class = ($i%2 == 0) ? 'even' : 'odd'}
    {if $dir != $download->dl_path}
    <tr>
        <td class="dir" colspan="2"><a href="{jurl 'downloads~default:index',array('dir'=>$download->dl_path)}" title="voir le contenu de ce répertoire sur le site"><img src="{$j_themepath}img/zoom.png" alt="voir le contenu du répertoire sur le site"/> Contenu du Répertoire</a> : </td>
        <td class="dir" colspan="5">{$download->dl_path}</td>
    </tr>
    <tr>
        <th></th>
        <th>Name</th>
        <th>Date</th>
        <th>Nb of Downlaods</th>            
        <th>Status</th>
        <th>In the Block ?</th>    
        <th>Action</th>
    </tr>
        
    {assign $dir = $download->dl_path}
    {/if}    
    
    <tr>
        <td class="{$class} name">{if $download->dl_filename == ''}<img src="{$j_themepath}img/warning.png" title="download without file, Think to add one file"/>{else}<img src="{$j_themepath}img/ico_file.png" alt="download with a file"/>{/if}</td>
        <td class="{$class} name"><a href="{jurl 'downloads~mgr:manage',array('id'=>$download->id)}" title="Edit this download">{$download->dl_name}</a></td>
        <td class="{$class}">{$download->dl_date|jdatetime:'db_date':'lang_datetime'}</td>              
        <td class="{$class}">{$download->dl_count}</td>
        <td class="{$class}">{if $download->dl_enable == 1}<a href="{jurl 'downloads~mgr:dl_disable',array('id'=>$download->id)}" title="disable"><img src="{$j_themepath}img/on.png" alt="enabled"/></a>{else}<a href="{jurl 'downloads~mgr:dl_enable',array('id'=>$download->id)}" title="enable"><img src="{$j_themepath}img/off.png" alt="disabled"/></a>{/if}</td>
        <td class="{$class}">{if $download->dl_on_block}<a href="{jurl 'downloads~mgr:dl_not_on_block',array('id'=>$download->id)}" title="remove of the block"><img src="{$j_themepath}img/ok.png" alt="Yes"/></a>{else}<a href="{jurl 'downloads~mgr:dl_on_block',array('id'=>$download->id)}" title="add to the block"><img src="{$j_themepath}img/ko.png" alt="No"/></a>{/if}</td>
        <td class="{$class}"><a href="{jurl 'downloads~mgr:delete',array('id'=>$download->id)}" title="Delet this download ?"><img src="{$j_themepath}img/delete.png" alt="supprimer" onclick="return confirm('Are you sure you want to delete this download ?')"/></a>
        {if $download->dl_filename != ''}<a href="{jurl 'downloads~mgr:trash',array('id'=>$download->id)}" title="Remove the file of this download ?"><img src="{$j_themepath}img/trash.png" alt="supprimer" onclick="return confirm('Are you sure you want to delete ths file of this download ?')"/>{else}<img src="{$j_themepath}img/warning.png" title="download without file"/>{/if}</a>
        </td>
    </tr>
    {assign $i++}
    {/foreach}
</table>
