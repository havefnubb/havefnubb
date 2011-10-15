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
    <li>{@havefnubb~member.edit.account.header@}</li>
</ul>

{hook 'hfbAccountEditBefore',array('user'=>$username)}
{jmessage}

<ul class="tabs" data-tabs="tabs">
    <li><a href="#user-profile-general">{@havefnubb~member.general@}</a></li>
    <li><a href="#user-profile-pref">{@havefnubb~member.pref@}</a></li>
    {hook 'hfbAccountEditTab',array('user'=>$username)}
</ul>
{form $form, 'jcommunity~account:save', array('user'=>$username)}
<div id="user-profile" class="tab-content">
    <div id="user-profile-general" class="active" >
        <fieldset>
            <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
            <div class="clearfix">
                {ctrl_label 'nickname'}<div class="input">{ctrl_control 'nickname'}</div>
                {ctrl_label 'email'}<div class="input">{ctrl_control 'email'}</div>
            </div>
            <div class="clearfix">
                <label class="jforms-label" for="jforms_jcommunity_member_birth">{ctrl_label 'member_birth'}</label><div class="input">{ctrl_control 'member_birth'}</div>
            </div>
            <div class="clearfix">
                {ifacl2 'auth.users.change.password'}
                <div class="input"><a class="btn danger user-edit-password user-image"  href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a></div>
                {/ifacl2}
                <div class="input"><a class="btn success" href="{jurl 'havefnubb~members:mail'}">{@havefnubb~member.internal.messenger@}</a></div>
            </div>
        </fieldset>
        <fieldset>
            <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
            <div class="clearfix">
                {ctrl_label 'member_town'}<div class="input">{ctrl_control 'member_town'}</div>
                {ctrl_label 'member_country'}<div class="input">{ctrl_control 'member_country'}</div>
            </div>
            <div class="clearfix">
                {ctrl_label 'member_website'}<div class="input">{ctrl_control 'member_website'}</div>
            </div>
        </fieldset>
    </div>

    <div id="user-profile-pref">
        <fieldset>
            <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
            <div class="clearfix">
                {ctrl_label 'member_language'}<div class="input">{ctrl_control 'member_language'}</div>
            </div>
            <div class="clearfix">
                {ctrl_label 'member_show_email'}<div class="input">{ctrl_control 'member_show_email'}</div>
            </div>
            <div class="clearfix">
                <div class="input">{@havefnubb~member.account.edit.show.your.email.description@}</div>
            </div>
            <div class="clearfix">
                {ctrl_label 'member_avatar'}<div class="input">{ctrl_control 'member_avatar'}</div>
            </div>
            <div class="clearfix">
                {ctrl_label 'member_gravatar'}<div class="input">{ctrl_control 'member_gravatar'}</div>
            </div>
            <div class="clearfix">
                {ctrl_label 'member_comment'}<div class="input">{ctrl_control 'member_comment'}</div>
            </div>
        </fieldset>
    </div>
    {hookinclude 'hfbAccountEditInclude',array('user'=>$username)}
    {hook 'hfbAccountEditDiv',array('user'=>$username)}
</div> <!-- .tabs -->
<div class="actions">{formsubmit}</div>
{/form}
{hook 'hfbAccountEditAfter',array('user'=>$username)}
