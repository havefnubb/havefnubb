{meta_html js $j_basepath .'hfnutoolbar.js'}
<div class="toggler-quickreply" title="{@havefnubb~post.quickreply.quickreply@}">
<div class="headings">
    <h3><span>{@havefnubb~post.quickreply.quickreply@}</span></h3>
</div>
<div id="quickreply">
    {form $form, 'havefnubb~posts:savereply', array('id_post'=>$id_post)}
    
    <p>{ctrl_label 'subject'} </p>
    <p>{ctrl_control 'subject'} </p>
    <p>{ctrl_label 'message'} </p>
    <p>{ctrl_control 'message'} </p>
    {hfnutoolbar 'jforms_havefnubb_posts_message',$j_themepath.'images/wiki/'}
    <div>{formsubmit 'validate'} {formreset 'cancel'}</div>
    {/form}
</div>
{zone 'havefnubb~syntax_wiki'}
</div>