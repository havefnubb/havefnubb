<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@havefnubb~member.password.confirmation.activation.of.your.password@}</li>
</ul>
<div>
<p>{@havefnubb~member.password.confirmation.activation.description@}</p>

{form $form,'jcommunity~password:confirm', array()}
    <fieldset>
        <legend>{@havefnubb~member.registration.confirmation.activation@}</legend>
    {formcontrols}
        <div class="clearfix"
        {ctrl_label} : <div class="input">{ctrl_control}</div>
        </div>
    {/formcontrols}
    </fieldset>
    <div class="actions">
    {formsubmit}
    </div>
{/form}
</div>
