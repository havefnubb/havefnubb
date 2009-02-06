<div class="inbox">
    <dl>
        <dt>{@servinfo~servinfo.server.infos.env@}</dt>
        <dd>
            {@servinfo~servinfo.server.infos.os@} : {$PHP_OS}<br />
            PHP : {$PHP_VERSION} - <a href="{jurl 'servinfo~default:phpinfo'}">{@servinfo~servinfo.display.phpinfo@}</a><br />
            {@servinfo~servinfo.server.infos.php.accelerator@} : {$CACHE_ENGINE}
        </dd>
        <dt>{@servinfo~servinfo.server.infos.database@}</dt>
        <dd>
            {$DB_VERSION}<br />Lignes&nbsp;: {$DB_RECORDS}<br />Taille&nbsp;: {$DB_SIZE}
        </dd>
    </dl>
</div>