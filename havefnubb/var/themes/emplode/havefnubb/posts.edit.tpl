<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>

<div id="postadd">
    {if $previewsubject !== null}
    <div class="legend"><h3>{@havefnubb~post.form.title.preview.page@}</h3></div>
    <div class="form_row">
        <div class="signature-content form_value">
        {$previewsubject|eschtml}    
        {$previewtext|wiki:'wr3_to_xhtml'}      
        {if $signature != ''}<hr/>
        {$signature|wiki:'wr3_to_xhtml'|stripslashes}
        {/if}
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
    {/if}
    
    {form $form, $submitAction, array('id_post'=>$id_post)}
    <fieldset>
    <div class="legend"><h3>{$heading}</h3></div>        
    <div class="form_row">    
        <div class="form_property">{ctrl_label 'subject'} </div>
        <div class="form_value">{ctrl_control 'subject'} </div>
        <div class="clearer">&nbsp;</div>
    </div>
    
    <div class="form_row">        
        <div class="form_property">{ctrl_label 'tags'} </div>
        <div class="form_value">{ctrl_control 'tags'} </div>
        <div class="clearer">&nbsp;</div>
    </div>
    
    <div class="form_row">        
        <div class="form_property">{ctrl_label 'message'} </div>
        <div class="form_value">
            {ctrl_control 'message'}
            {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function()	{
                $('#jforms_havefnubb_posts_message').markItUp(mySettings);
            });
            //]]>
            </script>
            {/literal}               
        </div>
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