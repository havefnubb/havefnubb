{meta_html jquery}
{meta_html jquery_ui 'components', array('widget','tabs')}
{meta_html csstheme 'css/tabnav.css'}
{literal}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $("#container").tabs();
});
//]]>
</script>
{/literal}
<div id="breadcrumbtop" class="headbox box_title">
    <h5>{jlocale 'havefnubb~member.memberlist.profile.of', array($user->login)}
{if $himself}
&gt; <a id="user" class="user-image" href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@havefnubb~member.account.show.edit.your.profile@}</a>
{ifacl2 'auth.users.change.password'}
&gt; <a class="user-edit-password user-image" href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a>
{/ifacl2}
{else}
&gt; <a href="{jurl 'jmessenger~jmessenger:create'}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->login)}">{@havefnubb~member.common.contact.the.member.by.email@}</a>
{/if}
    </h5>
</div>
{hook 'hfbAccountShowBefore',array($user->login)}
<div id="post-message">{jmessage}</div>
<div id="profile">
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
    <div id="container">
        <ul>
            <li><a href="#user-profile-general"><span class="user-general user-image">{@havefnubb~member.general@}</span></a></li>
            <li><a href="#user-profile-pref"><span class="user-pref user-image">{@havefnubb~member.pref@}</span></a></li>
            {hook 'hfbAccountShowTab',array($user->login)}
        </ul>
        <div id="user-profile-general">
            <fieldset>
                <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
    {if $user->member_show_email == 'Y'}
    {assign $class="three-cols"}
    {else}
    {assign $class="two-cols"}
    {/if}
                <div class="{$class} form_row">
                    <div class="form_property">
                        <span class="user-name user-image"><strong>{@havefnubb~member.nickname@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$user->nickname|eschtml}
                    </div>

    {if $user->member_show_email == 'Y'}
                    <div class="form_property">
                        <span class="user-email user-image"><strong>{@havefnubb~member.email@}</strong></span>
                    </div>
                    <div class="form_value">
                        {mailto array('address'=>$user->email,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}
                    </div>
    {/if}
                    <div class="form_property">
                        <span class="user-birthday user-image"><strong>{@havefnubb~member.common.age@}</strong></span>
                    </div>
                    <div class="form_value">
                        {age $user->member_birth}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>
            </fieldset>
            <fieldset>
                <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-town user-image"><strong>{@havefnubb~member.common.town@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$user->member_town|eschtml}
                    </div>
                    <div class="form_property">
                        <span><strong>{@havefnubb~member.common.country@}</strong></span>
                    </div>
                    <div class="form_value">
                        {if $user->member_country != ''}
                        {image 'hfnu/images/flags/'.strtolower($user->member_country).'.gif', array('alt'=>$user->member_country)} {country $user->member_country}
                        {/if}
                    </div>
                    <div class="form_property">
                        <span class="user-website user-image"><strong>{@havefnubb~member.common.website@}</strong></span>
                    </div>
                    <div class="fom_value">
                        {if $user->member_website != ''}
                        <a href="{$user->member_website|eschtml}" title="{@havefnubb~member.common.website@}">{$user->member_website|eschtml}</a>
                        {/if}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>

            </fieldset>
            <fieldset>
                <legend><span class="user-stats user-image">{@havefnubb~member.common.stats@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <span><strong>{@havefnubb~member.common.rank@}</strong></span>
                    </div>
                    <div class="form_value">
                        {zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}
                    </div>
                    <div class="form_property">
                        <span><strong>{@havefnubb~member.memberlist.nb.posted.msg@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$user->nb_msg}
                    </div>
                    <div class="clearer">
                        &nbsp;
                    </div>

                    <div class="form_property">
                        <span><strong>{@havefnubb~member.common.registered.since@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$user->create_date|jdatetime}
                    </div>
                    <div class="form_property">
                        <span><strong>{@havefnubb~member.common.last.connection@}</strong></span>
                    </div>
                    <div class="clearer">
                        &nbsp;
                    </div>
                </div>
            </fieldset>
        </div>

        <div id="user-profile-pref">
            <fieldset>
                <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <strong>{@havefnubb~member.common.language@}</strong>
                    </div>
                    <div class="form_value">
                        {$user->member_language}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span><strong>{@havefnubb~member.common.account.signature@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$user->member_comment|wiki:'hfb_rule'|stripslashes}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>
            </fieldset>
        </div>

        {hook 'hfbAccountShowDiv',array('user'=>$user->login)}
    </div>
</div>
{hook 'hfbAccountShowBefore',array($user->login)}
