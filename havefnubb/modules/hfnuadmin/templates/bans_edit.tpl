<h1>{@hfnuadmin~ban.the.bans@}</h1>
{formfull $form, 'hfnuadmin~ban:saveban'}

{foreach $bans as $ban}
{$ban->ban_username}<br/>
{/foreach}
