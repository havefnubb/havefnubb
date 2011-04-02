<div class="box">
    <h2>{@havefnubb~member.edit.account.header@}</h2>
    <div class="block">
        <fieldset>
        <legend>{@havefnubb~member.private.messaging@}</legend>
            <div class="block grid_16">    
                <div class="grid_5">    
                {zone 'jmessenger~links'}
                </div>
                <div class="grid_11">
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
        </fieldset>
    </div>
    {include 'havefnubb~zone.syntax_wiki'}      
</div>