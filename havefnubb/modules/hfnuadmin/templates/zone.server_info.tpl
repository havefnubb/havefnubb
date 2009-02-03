<div class="inbox">
    <dl>
        <dt>HavefnuBB! version</dt>
        <dd>
            HavefnuBB v 1.0 beta1<br/>
            <a href="{jurl 'hfnuadmin~default:check_upgrade'}">{@hfnuadmin~admin.check.official.version@}</a>
        </dd>
        <dt>{@hfnuadmin~admin.server.infos.title@}</dt>
        <dd>
            {$LOADS_AVG} ( xx {@hfnuadmin~admin.server.infos.online.users@})
        </dd>
{ifacl2 'hfnu.admin.server.info'}
        <dt>{@hfnuadmin~admin.server.infos.env@}</dt>
        <dd>
            {@hfnuadmin~admin.server.infos.os@} : {$PHP_OS}<br />
            PHP : {$PHP_VERSION} - <a href="{jurl 'hfnuadmin~default:phpinfo'}">Afficher infos</a><br />
            {@hfnuadmin~admin.server.infos.php.accelerator@} : {$CACHE_ENGINE}
        </dd>
        <dt>{@hfnuadmin~admin.server.infos.database@}</dt>
        <dd>
            {$DB_VERSION}<br />Lignes&nbsp;: {$DB_RECORDS}<br />Taille&nbsp;: {$DB_SIZE}
        </dd>
{/ifacl2}    
    </dl>
</div>