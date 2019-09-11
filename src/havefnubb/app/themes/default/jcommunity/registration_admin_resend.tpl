
    <h3>{@jcommunity~register.admin.resend.email.title@}</h3>
    {jlocale 'jcommunity~register.admin.resend.email.text.html', array($login)}
    <form method="post" action="{jurl 'jcommunity~registration_admin_resend:send'}">
        <p>
            <input type="hidden" name="pass_login" value="{$login}">
            <button>{@jcommunity~password.admin.form.email.button@} {$login}</button></p>
    </form>

    <p><a href="{jurl 'jauthdb_admin~default:view', array('j_user_login'=>$login)}">{@jcommunity~password.admin.form.back.to.account@} {$login}</a></p>