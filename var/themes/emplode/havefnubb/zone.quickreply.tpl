{meta_html js $j_basepath .'hfnutoolbar.js'}
<div id="quickreply">
    {form $form, 'havefnubb~posts:savereply', array('id_post'=>$id_post)}
    <fieldset>
        <div class="legend">
            <h3><span>{@havefnubb~post.quickreply.quickreply@}</span></h3>
        </div>        
        {hfnutoolbar 'jforms_havefnubb_posts_message',$j_themepath.'images/wiki/'}
        <div class="form_row">
            <div class="form_property">{ctrl_label 'subject'} </div>
            <div class="form_value">{ctrl_control 'subject'}</div>
            <div class="clearer">&nbsp;</div>
        </div>
        <div class="form_row">
            <div class="form_property">{ctrl_label 'message'} </div>
            <div class="form_value">{ctrl_control 'message'}</div>
            {hfnutoolbar 'jforms_havefnubb_posts_message',$j_themepath.'images/wiki/'}            
            <div class="clearer">&nbsp;</div>
        </div>
        
        <div class="form_row form_row_submit">
            <div class="form_value">
                {formsubmit 'validate'} {formreset 'cancel'}
            </div>
            <div class="clearer">&nbsp;</div>
        </div>        
    </fieldset>             
    {/form}
</div>

{zone 'havefnubb~syntax_wiki'}