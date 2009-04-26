{zone ('downloads~adminmenu')}
<h2><img src="{$j_themepath}img/downloads.png" alt="downloads"/> Presentation</h2>
<dl>
<dt>Version :</dt><dd> {$moduleInfo['version']}</dd>
<dt>Label :</dt><dd> {$moduleInfo['label']|escxml}</dd>
<dt>Description :</dt><dd> {$moduleInfo['desc']}</dd>
<dt>Notes :</dt><dd> {$moduleInfo['notes']}</dd>
{foreach $moduleInfo['creators'] as $author}
<dt>Authors :</dt><dd> {$author['name']|escxml}</dd>
{/foreach}
</dl>