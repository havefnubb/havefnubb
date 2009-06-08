{meta_html js $j_basepath .'hfnutoolbar.js'}
<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>

<div id="postadd">
    {if $previewsubject !== null}
    <h1>{@havefnubb~post.form.title.preview.page@}</h1>
    {$previewsubject|eschtml}    
    {$previewtext|wiki:'wr3_to_xhtml'}    
    <div class="signature-content">
        {if $signature != ''}<hr/>
        {$signature|wiki:'wr3_to_xhtml'|stripslashes}
        {/if}
    </div>
    {/if}    
    <h1>{$heading}</h1>
    {form $form, $submitAction, array('id_post'=>$id_post)}
    <fieldset>
    <p>{ctrl_label 'subject'} </p>
    <p>{ctrl_control 'subject'} </p>
    <p>{ctrl_label 'tags'} </p>
    <p>{ctrl_control 'tags'} </p>    
    <p>{ctrl_label 'message'} </p>
    <p>{ctrl_control 'message'} </p>
    {hfnutoolbar 'jforms_havefnubb_posts_message',$j_themepath.'images/wiki/'}    
    </fieldset>
    <div>{formsubmit 'validate'} {formreset 'cancel'}</div>
    {/form}
</div>
{zone 'havefnubb~syntax_wiki'}