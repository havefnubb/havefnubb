<div id="quickreply">
    <h3>{@havefnubb~post.quickreply.quickreply@}</h3>

    {form $form, 'havefnubb~posts:savereply', array('id_post'=>$id_post)}
    
    <fieldset>
    <p>{ctrl_label 'subject'} </p>
    <p>{ctrl_control 'subject'} </p>
    <p>{ctrl_label 'message'} </p>
    <p>{ctrl_control 'message'} </p>
    </fieldset>
     
    <div>{formsubmit 'validate'} {formreset 'cancel'}</div>
    {/form}
</div>
