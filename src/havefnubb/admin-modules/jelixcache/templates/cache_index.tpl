<h1>{@jelixcache~jelixcache.cache.clear.header@}</h1>
{@jelixcache~jelixcache.cache.clear.description@}


<form action="{formurl 'jelixcache~default:clear'}" method="post">
    <div>{formurlparam}
    
    <p><input type="checkbox" name="confirm" value="Y" id="confirm"/>
        <label for="confirm">{@jelixcache~jelixcache.cache.confirm@}</label><br/>   
    </p>
    
    <p><input type="submit" value="{@jelixcache~jelixcache.cache.ok@}" /></p>
    
    </div>

</form>