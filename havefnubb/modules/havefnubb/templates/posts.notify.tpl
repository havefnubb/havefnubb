<div class="box">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$forum->id_cat,'ctitle'=>$forum->cat_name)}" title="{$forum->cat_name}">{$forum->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>
<div class="box">
    <div class="block">
    {form $form, $submitAction, array('id_post'=>$id_post)}
    <fieldset>
		{jlocale 'havefnubb~post.form.notify.description',$subject}

        <legend>{$heading}</legend>
        <p>
            {ctrl_label 'reason'}<br/>
            {ctrl_control 'reason'}
        </p>
        <p>
            {ctrl_label 'message'}<br />
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
        </p>
        {formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
    </fieldset>
    {/form}
    </div>
</div>
