<div id="quickreply">
{form $form, 'havefnubb~posts:savereply', array('id_post'=>$id_post)}
    <fieldset>
        <div class="legend">
            <h3><span>{@havefnubb~post.quickreply.quickreply@}</span></h3>
        </div>
        <div class="form_row">
            <div class="form_property">{ctrl_label 'subject'} </div>
            <div class="form_value">{ctrl_control 'subject'}</div>
            <div class="clearer">&nbsp;</div>
        </div>
        <div class="form_row">
            <div class="form_property">{ctrl_label 'message'} </div>
            <div class="form_value">{ctrl_control 'message'}</div>
            {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function() {
                $('#jforms_havefnubb_posts_message').markItUp(mySettings);
            });
            //]]>
            </script>
            {/literal}
            <div class="clearer">&nbsp;</div>
        </div>

        <div class="form_row form_row_submit">
            <div class="form_value">
                {formsubmit 'validate'} {formreset 'reset'}
            </div>
            <div class="clearer">&nbsp;</div>
        </div>
    </fieldset>
{/form}
</div>
{include 'havefnubb~zone.syntax_wiki'}
