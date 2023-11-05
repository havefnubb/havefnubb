<div class="box">
    <h3>{@jcommunity~password.form.change.title@}</h3>
{if $error_status}
    <p>{@jcommunity~password.form.change.error.$error_status@}</p>
{else}

    {@jcommunity~password.form.change.text.html@}

    {formfull $form,'jcommunity~password_reset:save', array(), 'html', array(
        'plugins' => array(
            'pchg_password' => $passwordWidget
    ))}

{/if}

    <p><a href="{jurl 'jcommunity~login:index'}">{@jcommunity~login.cancel.and.back.to.login@}</a></p>
</div>