<div class="inbox">
    <dl>
        <dt id="environment">{@servinfo~servinfo.server.infos.env@}</dt>
        <dd>{@servinfo~servinfo.server.infos.os@} : {$PHP_OS}</dd>
        <dd id="php">PHP : {$PHP_VERSION} - <a href="{jurl 'servinfo~default:phpinfo'}">{@servinfo~servinfo.display.phpinfo@}</a></dd>
        <dd id="accelerator">{@servinfo~servinfo.server.infos.php.accelerator@} : {$CACHE_ENGINE}</dd>
        <dt id="database">{@servinfo~servinfo.server.infos.database@}</dt>
        <dd>{$DB_VERSION}</dd>
        <dd>{@servinfo~servinfo.server.infos.database.rows@}: {$DB_RECORDS}</dd>
        <dd>{@servinfo~servinfo.server.infos.database.size@}: {$DB_SIZE}</dd>
        {foreach $otherInfos as $inf}
        <dt id="{$inf->id}">{$inf->label|eschtml}</dt>
        <dd>{$inf->content}</dd>
        {/foreach}
        
    </dl>
</div>
