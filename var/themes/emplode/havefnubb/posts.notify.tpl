{meta_html js $j_basepath .'hfnutoolbar.js'}
<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>
<div id="post-notify">    
    {form $form, $submitAction, array('id_post'=>$id_post)}    
    <fieldset>
        <div class="legend">{$heading}</div>
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

