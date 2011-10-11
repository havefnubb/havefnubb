{hook 'hfbBeforePostsEdit',array('id_post'=>$id_post)}
<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li><a href="{jurl 'havefnubb~category:view',array('id_cat'=>$forum->id_cat,'ctitle'=>$forum->cat_name)}" title="{$forum->cat_name}">{$forum->cat_name|eschtml}</a> >></li>
    <li><a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a> </li>
</ul>
<div>
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
        <div class="clearfix">
            {ctrl_label 'subject'}
            <div class="input">
                {ctrl_control 'subject'}
            </div>
        </div>
        <div class="clearfix">
            {ctrl_label 'message'}
            <div class="input">
                {ctrl_control 'message'}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <div class="clearfix">
            {ctrl_label 'tags'}
            <div class="input">
                {ctrl_control 'tags'}
                <span class="help-block">{@havefnubb~post.form.tags.description@}</span>
            </div>
        </div>
    </fieldset>
    {formcontrols}
        {ifctrl 'tags'}
            {if $reply == 0}{*no display of the tags field for reply *}
        <div class="clearfix">
            {ctrl_label}
            <div class="input">{ctrl_control} </div>
        </div>
            {/if}
        {/ifctrl}
    {/formcontrols}
    {ifuserconnected}
    <fieldset>
        <div class="clearfix">
            {ctrl_label 'subscribe'}
            <div class="input">
                {ctrl_control 'subscribe'}
                <span class="help-block">{@havefnubb~post.subscribe.to.this.post.help@}</span>
            </div>
        </div>
    </fieldset>
    {else}
    <fieldset>
        <div class="clearfix">
            {ctrl_label 'nospam'}
            <div class="input">
                {ctrl_control 'nospam'}
            </div>
        </div>
    </fieldset>
    {/ifuserconnected}
    <div class="actions">
        {formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
    </div>
    {/form}
</div>
{hook 'hfbAfterPostsEdit',array('id_post'=>$id_post)}
{include 'havefnubb~zone.syntax_wiki'}
