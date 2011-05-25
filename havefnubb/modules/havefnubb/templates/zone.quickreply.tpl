<div class="box">
    <h2>{@havefnubb~post.quickreply.quickreply@}</h2>
    <div class="block">
    {form $form, 'havefnubb~posts:savereply', array('id_post'=>$id_post,'thread_id'=>$thread_id)}
    <fieldset>
        <legend>{@havefnubb~post.quickreply.quickreply@}</legend>
        <p>
            {ctrl_label 'subject'}
            {ctrl_control 'subject'}
        </p>
        <p>
            {ctrl_label 'message'}
            {ctrl_control 'message'}
        </p>
        {ifusernotconnected}
        <fieldset>
            <legend>{ctrl_label 'nospam'}</legend>
            {ctrl_control 'nospam'}
        </fieldset>
        {/ifusernotconnected}
        {formsubmit 'validate'} {formreset 'reset'}
    </fieldset>
    {/form}
    </div>
</div>
{include 'havefnubb~zone.syntax_wiki'}
