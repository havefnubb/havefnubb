<div id="breadcrumbtop" class="headbox">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>
<div id="post-notify">
    <h1>{$heading}</h1>
    {form $form, $submitAction, array('id_post'=>$id_post)}    
    <fieldset>
    <p>{ctrl_label 'subject'} </p>
    <p>{ctrl_control 'subject'} </p>
    <p>{ctrl_label 'message'} </p>
    <p>{ctrl_control 'message'} </p>
    {literal}
    <script type="text/javascript">
    //<![CDATA[
    $(document).ready(function()	{
        $('#jforms_havefnubb_notify_message').markItUp(mySettings);
    });
    //]]>
    </script>
    {/literal}     
    </fieldset>     
    <div>{formsubmit 'validate'} {formreset 'cancel'}</div>
    {/form}
</div>

