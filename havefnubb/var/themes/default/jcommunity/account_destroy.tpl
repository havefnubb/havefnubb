<h3>{@havefnubb~member.destroy.account.header@}</h3>

<form action="{formurl 'jcommunity~account:dodestroy', array('user'=>$username)}" method="get">
<fieldset><legend>Confirmation</legend>
{formurlparam 'jcommunity~account:dodestroy', array('user'=>$username)}

<p>{@havefnubb~member.destroy.account.confirm.delete@}</p>
<div><input type="submit" value="{@havefnubb~main.common.yes@}" />
 <a href="{jurl 'jcommunity~account:show', array('user'=>$username)}">{@havefnubb~member.destroy.account.cancel@}</a>
</div>
</fieldset>
</form>
