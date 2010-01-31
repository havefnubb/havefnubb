<table class="records-list" width="98%">
	<thead>
		<tr><th colspan="2">{$moduleInfo['name']}</th></tr>
	</thead>
	<tbody>
		<tr class="odd">
			<td>{@hfnuadmin~hfnuabout.about.version@} :</td><td><strong> {$moduleInfo['version']} {@hfnuadmin~hfnuabout.about.date.version@} {$moduleInfo['dateVersion']|jdatetime:'db_date':'lang_date'}</strong> (<em>{@hfnuadmin~hfnuabout.about.date.create@} {$moduleInfo['dateCreate']|jdatetime:'db_date':'lang_date'}</em>)</td>
		</tr>
		<tr class="even">
			<td>{@hfnuadmin~hfnuabout.about.label@} :</td><td> {$moduleInfo['label']|escxml}</td>
		</tr>
		<tr  class="odd">
			<td>{@hfnuadmin~hfnuabout.about.desc@} :</td><td> {$moduleInfo['desc']}</td>
		</tr>
		<tr class="even">
			<td>{@hfnuadmin~hfnuabout.about.notes@} :</td><td> {$moduleInfo['notes']}</td>
		</tr>
		<tr  class="odd">
			<td>{@hfnuadmin~hfnuabout.about.license@} :</td><td> {if $moduleInfo['licenseURL'] != ''}<a href="{$moduleInfo['licenseURL']}">{$moduleInfo['license']}</a>{else}{$moduleInfo['license']}{/if}</td>
		</tr>
		<tr class="even">
			<td>{@hfnuadmin~hfnuabout.about.copyright@} :</td><td> {$moduleInfo['copyright']}</td>
		</tr>
		{foreach $moduleInfo['creators'] as $author}
		<tr class="{cycle array('odd','even')}">
			<td>{@hfnuadmin~hfnuabout.about.authors@} :</td><td> {if $author['email'] != ''}<a href="mailto:{$author['email']}">{$author['name']|escxml}{else}{$author['name']|escxml}{/if}</a></td>
		</tr>
		{/foreach}
		<tr>
			<td>{@hfnuadmin~hfnuabout.about.links@}</td><td><a href="{$moduleInfo['homepageURL']}">{@hfnuadmin~hfnuabout.about.homepageURL@}</a>{if $moduleInfo['updateURL'] != ''} - <a href="{$moduleInfo['updateURL']}">{@hfnuadmin~hfnuabout.about.updateURL@}</a>{/if}</td>
		</tr>
	</tbody>
</table>
