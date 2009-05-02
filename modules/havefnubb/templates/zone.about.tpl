<h1>{$moduleInfo['name']}</h1>
<dl>
<dt>{@havefnubb~main.about.version@} :</dt><dd> {$moduleInfo['version']}</dd>
<dt>{@havefnubb~main.about.label@} :</dt><dd> {$moduleInfo['label']|escxml}</dd>
<dt>{@havefnubb~main.about.desc@} :</dt><dd> {$moduleInfo['desc']}</dd>
<dt>{@havefnubb~main.about.notes@} :</dt><dd> {$moduleInfo['notes']}</dd>
<dt>{@havefnubb~main.about.licence@} :</dt><dd> {$moduleInfo['licence']}</dd>
<dt>{@havefnubb~main.about.copyright@} :</dt><dd> {$moduleInfo['copyright']}</dd>
{foreach $moduleInfo['creators'] as $author}
<dt>{@havefnubb~main.about.authors@} :</dt><dd> {$author['name']|escxml}</dd>
{/foreach}
<dt>{@havefnubb~main.about.links@}</dt><dd><a href="{$moduleInfo['homepageURL']}">{@havefnubb~main.about.homepageURL@}</a> - <a href="{$moduleInfo['updateURL']}">{@havefnubb~main.about.updateURL@}</a></dd>
</dl>


