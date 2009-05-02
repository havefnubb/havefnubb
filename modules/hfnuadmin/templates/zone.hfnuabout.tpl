{* ifacl2 'hfnu.admin.server.info' *}
{* zone 'servinfo~server_info' *}
{* /ifacl2 *}    

{for $i = 0 ; $i < count($moduleInfos) ; $i ++}
<h1>Présentation</h1>
<dl>
<dt>Version :</dt><dd> {$moduleInfos[$i]['version']}</dd>
<dt>Libellé :</dt><dd> {$moduleInfos[$i]['label']|escxml}</dd>
<dt>Description :</dt><dd> {$moduleInfos[$i]['desc']}</dd>
<dt>Notes :</dt><dd> {$moduleInfos[$i]['notes']}</dd>
{foreach $moduleInfos[$i]['creators'] as $author}
<dt>Auteurs :</dt><dd> {$author['name']|escxml}</dd>
{/foreach}
</dl>
{/for}