<h1>{$moduleInfo['name']}</h1>
<dl>
<dt>{@hfnuadmin~hfnuabout.about.version@} :</dt><dd> {$moduleInfo['version']} ({@hfnuadmin~hfnuabout.about.date.create@} {$moduleInfo['dateCreate']})</dd>
<dt>{@hfnuadmin~hfnuabout.about.label@} :</dt><dd> {$moduleInfo['label']|escxml}</dd>
<dt>{@hfnuadmin~hfnuabout.about.desc@} :</dt><dd> {$moduleInfo['desc']}</dd>
<dt>{@hfnuadmin~hfnuabout.about.notes@} :</dt><dd> {$moduleInfo['notes']}</dd>
<dt>{@hfnuadmin~hfnuabout.about.licence@} :</dt><dd> {if $moduleInfo['licenceURL'] != ''}<a href="{$moduleInfo['licenceURL']}">{$moduleInfo['licence']}</a>{else}{$moduleInfo['licence']}{/if}</dd>
<dt>{@hfnuadmin~hfnuabout.about.copyright@} :</dt><dd> {$moduleInfo['copyright']}</dd>
{foreach $moduleInfo['creators'] as $author}
<dt>{@hfnuadmin~hfnuabout.about.authors@} :</dt><dd> {if $author['email'] != ''}<a href="mailto:{$author['email']}">{$author['name']|escxml}{else}{$author['name']|escxml}{/if}</a></dd>
{/foreach}
<dt>{@hfnuadmin~hfnuabout.about.links@}</dt><dd><a href="{$moduleInfo['homepageURL']}">{@hfnuadmin~hfnuabout.about.homepageURL@}</a> - <a href="{$moduleInfo['updateURL']}">{@hfnuadmin~hfnuabout.about.updateURL@}</a></dd>
</dl>
