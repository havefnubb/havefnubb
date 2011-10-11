<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@havefnubb~member.registration.confirmation.activation.of.your.account@}</li>
</ul>
<div>
{form $form,'jcommunity~registration:confirm', array()}
    <fieldset>
        <p>{@havefnubb~member.registration.confirmation.activation.description@}</p>

        <p>{@havefnubb~member.registration.confirmation.activation.description.line2@}</p>

        <legend>{@havefnubb~member.registration.confirmation.activation@}</legend>
        {formcontrols}
        <div class="clearfix">
            {ctrl_label} :<div class="input">{ctrl_control}</div>
        </div>
        {/formcontrols}
    </fieldset>
    <div class="actions">{formsubmit}</div>
{/form}
</div>
