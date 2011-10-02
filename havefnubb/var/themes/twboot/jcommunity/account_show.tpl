{meta_html jquery}
{meta_html jquery_ui 'components', array('widget','tabs')}
{meta_html csstheme 'css/tabnav.css'}
{literal}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $(".tabs").tabs();
});
//]]>
</script>
{/literal}

<ul class="breadcrumb">
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{if $himself}<a id="user" href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)}</a>{else}{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)}{/if} </li>
</ul>

{hook 'hfbAccountShowBefore',array($user->login)}
{jmessage}

<div id="user-profile-avatar">
{if $user->member_gravatar == 1}
    {gravatar $user->email,array('username'=>$user->login)}
{else}
    {if file_exists('hfnu/images/avatars/'. $user->id.'.png') }
    {image 'hfnu/images/avatars/'. $user->id.'.png', array('alt'=>$user->login)}
    {elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpg')}
    {image 'hfnu/images/avatars/'. $user->id.'.jpg', array('alt'=>$user->login)}
    {elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpeg')}
    {image 'hfnu/images/avatars/'. $user->id.'.jpeg', array('alt'=>$user->login)}
    {elseif file_exists('hfnu/images/avatars/'. $user->id.'.gif')}
    {image 'hfnu/images/avatars/'. $user->id.'.gif', array('alt'=>$user->login)}
    {/if}
{/if}
</div>

<ul class="tabs" data-tabs="tabs">
    <li class="active"><a href="#user-profile-general">{@havefnubb~member.general@}</a></li>
    <li><a href="#user-profile-pref">{@havefnubb~member.pref@}</a></li>
    {hook 'hfbAccountShowTab',array($user->login)}
</ul>
<div id="user-profile" class="form-stacked tab-content">
    <div id="user-profile-general" class="active" >
    <fieldset>
        <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
{if $user->member_show_email == 'Y'}
{else}
{/if}
        <div class="row">
            <div class="span8">
                <label class="label user-name user-image"><strong>{@havefnubb~member.nickname@}</strong></label>{$user->nickname|eschtml}
            </div>
            <div class="span4">
{if $user->member_show_email == 'Y'}
                <label class="user-email user-image"><strong>{@havefnubb~member.email@}</strong></label>
                {mailto array('address'=>$user->email,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}</div>
{/if}
{if !$himself}
                <label class="user-messenger user-image"> &nbsp;</label>
                <a href="{jurl 'jmessenger~jmessenger:create'}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->login)}">{@havefnubb~member.common.contact.the.member.by.email@}</a>
{/if}
            </div>
            <div class="span4">
                <label class="user-birthday user-image"><strong>{@havefnubb~member.common.age@}</strong></label>
                {age $user->member_birth}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
        <div class="row">
            <div class="span5">
                <label class="user-town user-image"><strong>{@havefnubb~member.common.town@}</strong></label>
                {$user->member_town|eschtml}
            </div>
            <div class="span5">
                <label><strong>{@havefnubb~member.common.country@}</strong></label>
                {if $user->member_country != ''}
                {image 'hfnu/images/flags/'.strtolower($user->member_country).'.gif', array('alt'=>$user->member_country)} {country $user->member_country}
                {/if}
            </div>
            <div class="span5">
                <label class="user-website user-image"><strong>{@havefnubb~member.common.website@}</strong>&nbsp;</label>
                {if $user->member_website != ''}
                <a href="{$user->member_website|eschtml}" title="{@havefnubb~member.common.website@}">{$user->member_website|eschtml}</a>
                {/if}
            </div>
        </div>
    </fieldset>
    <fieldset>
        <legend><span class="user-stats user-image">{@havefnubb~member.common.stats@}</span></legend>
        <div class="row">
            <div class="span4">
                <label><strong>{@havefnubb~member.common.rank@}</strong></label>{zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}
            </div>
            <div class="span4">
                <label><strong>{@havefnubb~member.memberlist.nb.posted.msg@}</strong></label>{$user->nb_msg}
            </div>
            <div class="span4">
                <label><strong>{@havefnubb~member.common.registered.since@}</strong></label>{$user->member_created|jdatetime}
            </div>
            <div class="span3">
                <label><strong>{@havefnubb~member.common.last.connection@}</strong></label>
            </div>
        </div>
    </fieldset>
    </div>
    <div id="user-profile-pref">
        <fieldset>
            <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
            <div class="row">
                <div class="span15">
                    <label><strong>{@havefnubb~member.common.language@}</strong></label>{$user->member_language}
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>{@havefnubb~member.common.account.signature@}</legend>
            <div class="row">
                <div class="span15">
                    {$user->member_comment|wiki:'hfb_rule'|stripslashes}
                </div>
            </div>
        </fieldset>
    </div>
    {hook 'hfbAccountShowDiv',array('user'=>$user->login)}
</div> <!-- #container -->

    <div class="buttons-bar">
        {if $himself}
        <a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@havefnubb~member.account.show.edit.your.profile@}</a>
        {/if}
    </div>
    {hook 'hfbAccountShowBefore',array($user->login)}
