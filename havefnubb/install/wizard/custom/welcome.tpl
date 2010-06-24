
<h3>{@introduction@}</h3>
    <p>{@process.description@}</p>

<h3>{@rights@}</h3>
    <p>{@rights.description@}</p>

    <h4>{@rights.dirs@}</h4>
    <pre>{literal}find . -type d -exec chmod 755 {} \;{/literal}</pre>
    <h4>{@rights.files@}</h4>
    <pre>{literal}find . -type f -exec chmod 644 {} \;{/literal}</pre>



<!--<h3>{@lets.go@}</h3>-->