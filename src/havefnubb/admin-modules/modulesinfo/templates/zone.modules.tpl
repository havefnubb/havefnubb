<h1>{@modulesinfo~modulesinfo.modules.list.title@}</h1>
<p>{@modulesinfo~modulesinfo.modules.list.description@}</p>
{foreach $modulesList as $module}
<h3>{$module->name|eschtml}</h3>
<table class="records-list" width="98%">
    <tbody>
        <tr class="odd">
            <th>{@modulesinfo~modules.about.label@} :</th>
            <td>{$module->getLabel()|eschtml}</td>
        </tr>
        <tr class="even">
            <th>{@modulesinfo~modules.about.version@} :</th>
            <td><strong> {$module->version}
            {if $module->versionDate}
                {@modulesinfo~modules.about.date.version@}
                {$module->versionDate|jdatetime:'db_date':'lang_date'} {/if}</strong>
            {if $module->createDate}
            (<em>{@modulesinfo~modules.about.date.create@}
                    {$module->createDate|jdatetime:'db_date':'lang_date'}</em>){/if}</td>
        </tr>
        <tr  class="odd">
            <th>{@modulesinfo~modules.about.desc@} :</th>
            <td> {$module->getDescription()|eschtml}</td>
        </tr>
        <tr  class="odd">
            <th>{@modulesinfo~modules.about.license@} :</th>
            <td> {if $module->licenseURL != ''}<a href="{$module->licenseURL}">{$module->license|eschtml}</a>
                {else}{$module->license|eschtml}{/if}</td>
        </tr>
        <tr class="even">
            <th>{@modulesinfo~modules.about.copyright@} :</th>
            <td> {$module->copyright}</td>
        </tr>
        {foreach $module->author as $author}
        <tr class="{cycle array('odd','even')}">
            <th>{@modulesinfo~modules.about.authors@} :</th>
            <td> {if array_key_exists('email',$author)}
                  {if $author->email != ''}<a href="mailto:{$author->email}">{$author->name|escxml}{/if}
                  {else}{$author->name|escxml}{/if}</a></td>
        </tr>
        {/foreach}
        <tr>
            <th>{@modulesinfo~modules.about.links@}</th>
            <td>{if $module->homepageURL}
                <a href="{$module->homepageURL}"
                   title="{@modulesinfo~modules.about.homepageURL@} : {$module->name}">
                    {@modulesinfo~modules.about.homepageURL@}</a>
                {/if}
                {if $module->updateURL} - <a href="{$module->updateURL}" title="{@modulesinfo~modules.about.updateURL@} : {$module->name}">{@modulesinfo~modules.about.updateURL@}</a>{/if}</td>
        </tr>
    </tbody>
</table>
{/foreach}
