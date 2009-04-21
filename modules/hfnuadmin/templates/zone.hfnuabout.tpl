<h2>HavefnuBB! - {$version}</h2>
<div>
    <a href="{jurl 'hfnuadmin~default:check_upgrade'}">{@hfnuadmin~admin.check.official.version@}</a>
</div>
{ifacl2 'hfnu.admin.server.info'}		
{zone 'servinfo~server_info'}
{/ifacl2}    
