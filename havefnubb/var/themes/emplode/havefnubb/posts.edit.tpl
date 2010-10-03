{hook 'hfbBeforePostsEdit',array('id_post'=>$id_post)}
<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$forum->id_cat,'ctitle'=>$forum->cat_name)}" title="{$forum->cat_name}">{$forum->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>

<div id="postadd">
    {if $previewsubject !== null}
    <div class="legend"><h3>{@havefnubb~post.form.title.preview.page@}</h3></div>
    <div class="form_row">
        <div class="signature-content form_value">
        {$previewsubject|eschtml}
        {$previewtext|wiki:'hfb_rule'}
        {if $signature != ''}<hr/>
        {$signature|wiki:'hfb_rule'|stripslashes}
        {/if}
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
    {/if}
    {hook 'hfbPostsEdit',array('id_post'=>$id_post)}
    {form $form, $submitAction, array('id_forum'=>$id_forum)}
    <fieldset>
    <div class="legend"><h3>{$heading}</h3></div>
    <div class="form_row">
        <div class="form_property">{ctrl_label 'subject'} </div>
        <div class="form_value">{ctrl_control 'subject'} </div>
        <div class="clearer">&nbsp;</div>
    </div>

    <div class="form_row">
        <div class="form_property">{ctrl_label 'message'} </div>
        <div class="form_value">{ctrl_control 'message'}</div>
        <div class="clearer">&nbsp;</div>
    </div>

    <div class="form_row">
        <div class="form_property">{ctrl_label 'tags'} </div>
        <div class="form_value">{ctrl_control 'tags'} </div>
        <div class="clearer">&nbsp;</div>
    </div>
    {ifuserconnected}
    <div class="form_row">
        <div class="form_property">{ctrl_label 'subscribe'}</div>
        <div class="form_value">{@havefnubb~post.subscribe.to.this.post.help@} {ctrl_control 'subscribe'} </div>
        <div class="clearer">&nbsp;</div>
    </div>
    {/ifuserconnected}
    {ifusernotconnected}
    <div class="form_row">
        <div class="form_property">{ctrl_label 'nospam'}</div>
        <div class="form_value">{ctrl_control 'nospam'}</div>
        <div class="clearer">&nbsp;</div>
    </div>
    {/ifusernotconnected}
    <div class="form_row form_row_submit">
        <div class="form_value">
        {formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
        </div>
        <div class="clearer">&nbsp;</div>
    </div>
    </fieldset>
    {/form}
</div>
{hook 'hfbAfterPostsEdit',array('id_post'=>$id_post)}
{include 'havefnubb~zone.syntax_wiki'}
