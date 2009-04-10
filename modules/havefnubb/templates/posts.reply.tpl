<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>
<div id="postreply">
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
    <p>{ctrl_label 'message'} </p>
    <p>{ctrl_control 'message'} </p>
    </fieldset>     
    <div>{formsubmit 'validate'} {formreset 'cancel'}</div>
    {/form}
</div>

{zone 'havefnubb~posts_replies',array('id_post'=>$parent_id,'id_forum'=>$id_forum)}
