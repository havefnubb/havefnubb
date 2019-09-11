<div class="box">
    <h3>{@jcommunity~register.form.create.title@}</h3>
    <div class="box-content">
        <div id="post-message">{jmessage}</div>
        {form $form,'jcommunity~registration:save', array()}
        <fieldset>
            <p>{@jcommunity~register.form.create.text.html@}</p>
            <legend>{@havefnubb~main.informations@}</legend>
            {formcontrols}
            <div>{ctrl_label} :</div><div>{ctrl_control}</div>
            {/formcontrols}
            <div >{formsubmit} <a href="{jurl 'jcommunity~login:index'}">{@jcommunity~login.cancel.and.back.to.login@}</a></div>
        </fieldset>
        {/form}
    </div>
</div>
