<div class="box">
    <h2>{@havefnubb~post.quickreply.quickreply@}</h2>
    <div class="block">
    {form $form, 'havefnubb~posts:savereply', array('id_post'=>$id_post,'parent_id'=>$parent_id)}
    <fieldset>
        <legend>{@havefnubb~post.quickreply.quickreply@}</legend>
        <p>
            {ctrl_label 'subject'}
            {ctrl_control 'subject'}
        </p>
        <p>
            {ctrl_label 'message'}
            {ctrl_control 'message'}
        {literal}
        <script type="text/javascript">
        //<![CDATA[
        $(document).ready(function()    {
            $('#jforms_havefnubb_posts_message').markItUp(mySettings);
        });
        //]]>
        </script>
        {/literal}
        </p>
        {formsubmit 'validate'} {formreset 'reset'}
    </fieldset>
    {/form}
    </div>
</div>
{include 'havefnubb~zone.syntax_wiki'}

