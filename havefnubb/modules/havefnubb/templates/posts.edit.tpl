{hook 'hfbBeforePostsEdit',array('id_post'=>$id_post)}
<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>

<div class="box">
    <div class="block">
        
    {if $previewsubject !== null}
    <fieldset>
    <legend>{@havefnubb~post.form.title.preview.page@}</legend>
        <div class="signature-content form_value">
        {$previewsubject|eschtml}
        {$previewtext|wiki:'wr3_to_xhtml'}
        {if $signature != ''}<hr/>
        {$signature|wiki:'wr3_to_xhtml'|stripslashes}
        {/if}
        </div>
    </fieldset>
    {/if}
    {hook 'hfbPostsEdit',array('id_post'=>$id_post)}
    {form $form, $submitAction, array('id_post'=>$id_post)}
    <fieldset>
    <legend>{$heading}</legend>
    <p>
        {ctrl_label 'subject'}<br/>
        {ctrl_control 'subject'}
    </p>
    <p>
        {ctrl_label 'tags'}<br/>
        {ctrl_control 'tags'} 
    </p>
    
    <p>
        {ctrl_label 'message'}<br/>
        {ctrl_control 'message'}
        {literal}
        <script type="text/javascript">
        //<![CDATA[
        $(document).ready(function() {
            $('#jforms_havefnubb_posts_message').markItUp(mySettings);
        });
        //]]>
        </script>
        {/literal}
    </p>
    {formsubmit 'validate'} {formreset 'reset'} <span class="jforms-submit" id="fake-cancel">{@havefnubb~main.go.back.to@} {gobackto}</span
    </fieldset>
    {/form}

    </div>
</div>
{hook 'hfbAfterPostsEdit',array('id_post'=>$id_post)}
{zone 'havefnubb~syntax_wiki'}