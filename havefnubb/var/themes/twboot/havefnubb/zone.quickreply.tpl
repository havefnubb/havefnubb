{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_basepath.'hfnu/js/bootstrap-tabs.js'}
{meta_html js $j_basepath.'hfnu/js/bootstrap-dropdown.js'}
{meta_html js $j_basepath.'hfnu/js/bootstrap-alerts.js'}
<div>
    {form $form, 'havefnubb~posts:savereply', array('id_post'=>$id_post,'thread_id'=>$thread_id)}
    <fieldset>
        <legend>{@havefnubb~post.quickreply.quickreply@}</legend>
        <div class="clearfix">
            {ctrl_label 'subject'}
            <div class="input">
                {ctrl_control 'subject'}
            </div>
        </div>
        <div class="clearfix">
            {ctrl_label 'message'}
            <div class="input">
                {ctrl_control 'message'}
            </div>
        </div>
        {ifusernotconnected}
        <fieldset>
            <div class="clearfix">
                {ctrl_label 'nospam'}
                <div class="input">
                    {ctrl_control 'nospam'}
                </div>
            </div>
        </fieldset>
        {/ifusernotconnected}
        <div class="actions">
        {formsubmit 'validate'} {formreset 'reset'}
        </div>
    </fieldset>
    {/form}
</div>
{include 'havefnubb~zone.syntax_wiki'}
