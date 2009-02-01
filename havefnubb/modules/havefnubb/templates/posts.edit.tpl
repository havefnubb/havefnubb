<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>
{if $previewsubject !== null}
<h1>{@havefnubb~post.form.title.preview.page@}</h1>
<div class="post">
    <div class="posthead">
        <h3>{$j_datenow} {$j_timenow} {@havefnubb~main.by@} {zone 'havefnubb~poster', array('id_user'=>$id_user)}</h3>
    </div>
    <div class="postbody">
        {zone 'havefnubb~memberprofile',array('id'=>$id_user)}
        <div class="post-entry">
            <h4 class="message-title">{$previewsubject|eschtml}</h4>
            <div class="message-content">
            {$previewtext|wiki:$wr_engine}
            {zone 'havefnubb~membersignature',array('id'=>$id_user)}
            </div>
        </div>        
    </div>
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
