{hook 'hfbBeforePostsEdit',array('id_post'=>$id_post)}
<div class="breadcrumb">
<ol>
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$forum->id_cat,'ctitle'=>$forum->cat_name)}" title="{$forum->cat_name}">{$forum->cat_name|eschtml}</a> >></li>
    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> </li>
</ol>
</div>
<div class="box">
    <div class="block">
    {if $previewsubject !== null}
    <fieldset>
        <legend>{@havefnubb~post.form.title.preview.page@}</legend>
        <div class="signature-content form_value">
        {$previewsubject|eschtml}
        {$previewtext|wiki:'hfb_rule'}
        {if $signature != ''}<hr/>
        {$signature|wiki:'hfb_rule'|stripslashes}
        {/if}
        </div>
    </fieldset>
    {/if}
    {hook 'hfbPostsEdit',array('id_post'=>$id_post)}
    {form $form, $submitAction, array('id_forum'=>$id_forum)}
    <fieldset>
        <legend>{$heading}</legend>
        {ctrl_label 'subject'}<br/>
        {ctrl_control 'subject'}<br/>
        {ctrl_label 'message'}<br/>
        {ctrl_control 'message'}<br/>
    </fieldset>
    {formcontrols}
        {ifctrl 'tags'}
            {if !isset($reply) || $reply == 0}{*no display of the tags field for reply *}
    <fieldset>
        <legend>{ctrl_label 'tags'}</legend>
        {@havefnubb~post.form.tags.description@}<br/>
        {ctrl_control 'tags'}
    </fieldset>
		{/if}
        {/ifctrl}
    {/formcontrols}
    {ifuserconnected}
    <fieldset>
        <legend>{ctrl_label 'subscribe'}</legend>
        {@havefnubb~post.subscribe.to.this.post.help@} {ctrl_control 'subscribe'}
    </fieldset>
    {else}
    <fieldset>
        <legend>{ctrl_label 'nospam'}</legend>
        {ctrl_control 'nospam'}
    </fieldset>
    {/ifuserconnected}
    {formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
    </fieldset>
    {/form}
    </div>
</div>
{hook 'hfbAfterPostsEdit',array('id_post'=>$id_post)}
{include 'havefnubb~zone.syntax_wiki'}
