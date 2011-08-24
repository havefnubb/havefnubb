{hook 'jMessengerBeforeView',array('id'=>$id)}
<div class="box">
    <h2>{@havefnubb~member.private.messaging@}</h2>
    <div class="box-content grid_16">    
        <div class="grid_5">    
         {zone 'jmessenger~links'}
        </div>
        <div class="grid_11">
            {formdatafull $form}
            <ul class="crud-links-list">
                <li><a href="{jurl $editAction, array('id'=>$id)}" class="crud-link">{@jelix~crud.link.edit.record@}</a></li>
                <li><a href="{jurl $deleteAction, array('id'=>$id)}" class="crud-link" onclick="return confirm('{@jelix~crud.confirm.deletion@}')">{@jelix~crud.link.delete.record@}</a></li>
            </ul>
        </div>
    </div>
</div>
{hook 'jMessengerAfterView',array('id'=>$id)}