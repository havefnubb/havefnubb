<h1>{@havefnubb~member.destroy.account.header@}</h1>

<form action="{formurl 'jcommunity~account:dodestroy', array('user'=>$username)}" method="get">
<fieldset><legend>{@jcommunity~account.form.delete.account.confirm.title@}</legend>
{formurlparam}

<p>{@havefnubb~member.destroy.account.confirm.delete@}</p>


 <p>
  <label for="conf_password">{@jcommunity~account.form.password@}</label>
  <input type="password" name="conf_password" id="conf_password" />
 </p>

<div><input type="submit" value="{@jcommunity~account.form.delete.account.submit@}" />
 <a href="{jurl 'jcommunity~account:show', array('user'=>$username)}">{@havefnubb~member.destroy.account.cancel@}</a>
</div>
</fieldset>
</form>
