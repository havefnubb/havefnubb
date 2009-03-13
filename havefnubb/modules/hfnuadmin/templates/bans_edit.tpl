<h1>{@hfnuadmin~ban.the.bans@}</h1>
{formfull $form, 'hfnuadmin~ban:saveban'}


<table width="100%" class="records-list">
<thead>  
    <tr>
        <th  colspan="3" class="records-list-category-order">Nom</th>
    </tr>
    <tr>
        <th  class="records-list-category-name">Expiration</th>
        <th  class="records-list-category-name">IP</th>
        <th  class="records-list-category-name">EMail</th>        
    </tr>
</thead>
<tbody>
{foreach $bans as $ban}
<tr>
    <td colspan="3">{$ban->ban_username}</td>
</tr>
<tr>
    <td>{$ban->ban_expire}</td>
    <td>{$ban->ban_ip}</td>
    <td>{$ban->ban_email}</td>
</tr>
<tr>
    <td colspan="3">{$ban->ban_message}</td>
</tr>
{/foreach}

</tbody>


</table>