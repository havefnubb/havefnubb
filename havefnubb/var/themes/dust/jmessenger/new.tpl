{zone 'jmessenger~links'}
<div class="box">
    <h2 class="user-email-add user-image">{@jmessenger~message.action.new@}</h2>
    <div class="block">
    {form $form, $submitAction}
    <fieldset>
    <legend>{@jmessenger~message.action.new@}</legend>
    {ctrl_label 'id_for'}<br/>
    {ctrl_control 'id_for'}<br/>

    {ctrl_label 'title'}<br/>
    {ctrl_control 'title'}<br/>

    {ctrl_label 'content'}<br/>
    {ctrl_control 'content'}<br/>
    {formsubmit '_submit'}
    </fieldset>
    {/form}
    </div>
</div>
{include 'havefnubb~syntax_wiki'}
