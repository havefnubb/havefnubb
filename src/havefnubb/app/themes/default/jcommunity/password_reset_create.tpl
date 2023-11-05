<div class="box">
    <h3>{@jcommunity~password.form.create.title@}</h3>
{if $error_status}
    <p>{@jcommunity~password.form.create.error.$error_status@}</p>
{else}

    {@jcommunity~password.form.create.text.html@}

    {formfull $form,'jcommunity~password_confirm_registration:save', array(), 'html', array(
    'plugins' => array(
        'pchg_password' => $passwordWidget
    ))}

{/if}

    <p><a href="{jurl 'jcommunity~login:index'}">{@jcommunity~login.cancel.and.back.to.login@}</a></p>
</div>