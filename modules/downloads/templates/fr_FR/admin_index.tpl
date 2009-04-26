<h1><img src="{$j_themepath}img/downloads.png" alt="téléchargements"/> Présentation</h1>
<dl>
<dt>Version :</dt><dd> {$moduleInfo['version']}</dd>
<dt>Libellé :</dt><dd> {$moduleInfo['label']|escxml}</dd>
<dt>Description :</dt><dd> {$moduleInfo['desc']}</dd>
<dt>Notes :</dt><dd> {$moduleInfo['notes']}</dd>
{foreach $moduleInfo['creators'] as $author}
<dt>Auteurs :</dt><dd> {$author['name']|escxml}</dd>
{/foreach}
</dl>