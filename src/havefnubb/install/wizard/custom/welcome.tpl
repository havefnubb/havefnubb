
<h3>{@introduction@}</h3>
    {@version@}
    <p>{@process.description@}</p>
    <h4>{@process.rename.dist.file@}</h4>
    <p>{@process.rename.dist.file.desc@}</p>
{if strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN'}
<h3>{@rights@}</h3>
    <p>{@rights.description@}</p>
    <h4>{@rights.debian.title@}</h4>
    <p>{@rights.debian.description@}</p>
{/if}
