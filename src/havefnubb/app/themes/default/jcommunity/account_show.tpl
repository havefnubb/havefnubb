{meta_html assets 'hfnuaccount'}
{hook 'hfbAccountShowBefore',array($user->login)}
<div id="post-message">{jmessage}</div>
<div class="box">
    <h3>{if $himself}
            <a id="user" href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{jlocale 'jcommunity~account.profile.of', array($user->login)}</a>
        {else}
            {jlocale 'jcommunity~account.profile.of', array($user->login)}
        {/if}</h3>
    <div class="box-content">
        <div id="user-profile-avatar">
        {if $user->member_gravatar == 1}
            {gravatar $user->email,array('username'=>$user->login)}
        {else}
            {if file_exists('hfnu/images/avatars/'. $user->id.'.png') }
            <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.png" alt="{$user->login}" />
            {elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpg')}
            <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.jpg" alt="{$user->login}" />
            {elseif file_exists('hfnu/images/avatars/'. $user->id.'.jpeg')}
            <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.jpeg" alt="{$user->login}" />
            {elseif file_exists('hfnu/images/avatars/'. $user->id.'.gif')}
            <img src="{$j_basepath}hfnu/images/avatars/{$user->id}.gif" alt="{$user->login}" />
            {/if}
        {/if}
        </div>
        <div id="container">
        <ul class="nav">
            <li><a href="#user-profile-general">{@havefnubb~member.general@}</a></li>
            <li><a href="#user-profile-pref">{@havefnubb~member.pref@}</a></li>
            {hook 'hfbAccountShowTab',array($user->login)}
        </ul>
            <div id="user-profile-general">
            <fieldset>
                <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-name user-image"><strong>{@havefnubb~member.nickname@}</strong></label>
                    </div>
                    <div class="form_value">
                        {$user->nickname|eschtml}
                    </div>
                </div>
    {if $user->member_show_email == 'Y'}
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-email user-image"><strong>{@havefnubb~member.email@}</strong></label>
                    </div>
                    <div class="form_value">
                        {mailto array('address'=>$user->email,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}
                    </div>
                </div>
    {/if}
    {if $himself}
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-email user-image">&nbsp;</span>
                    </div>
                    <div class="form_value"><a href="{jurl 'jmessenger~jmessenger:index'}">{@havefnubb~member.internal.messenger@}</a></div>
                </div>
        {else}
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-messenger user-image"> &nbsp;</label>
                    </div>
                    <div class="form_value"><a href="{jurl 'jmessenger~jmessenger:create'}" title="{jlocale 'havefnubb~member.common.send.an.email.to',array($user->login)}">{@havefnubb~member.common.contact.the.member.by.email@}</a></div>
                </div>
    {/if}
            </fieldset>
            <fieldset>
                <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-town user-image"><strong>{@havefnubb~member.common.town@}</strong></label>
                    </div>
                    <div class="form_value">
                        {$user->member_town|eschtml}
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label><strong>{@havefnubb~member.common.country@}</strong></label>
                    </div>
                    <div class="form_value">
                        {if $user->member_country != ''}
                            <img src="{$j_basepath}hfnu/flags/avatars/{$user->member_country|lower}.gif" alt="{$user->member_country}" />
                            {country $user->member_country}
                        {/if}
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-website user-image"><strong>{@havefnubb~member.common.website@}</strong>&nbsp;</label>
                    </div>
                    <div class="fom_value">
                        {if $user->member_website != ''}
                        <a href="{$user->member_website|eschtml}" title="{@havefnubb~member.common.website@}">{$user->member_website|eschtml}</a>
                        {/if}
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend><span class="user-stats user-image">{@havefnubb~member.common.stats@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label><strong>{@havefnubb~member.common.rank@}</strong></label>
                    </div>
                    <div class="form_value">
                        {zone 'havefnubb~what_is_my_rank',array('nbMsg'=>$user->nb_msg)}
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label><strong>{@havefnubb~member.memberlist.nb.posted.msg@}</strong></label>
                    </div>
                    <div class="form_value">
                        {$user->nb_msg}
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label><strong>{@havefnubb~member.common.registered.since@}</strong></label>
                    </div>
                    <div class="form_value">
                        {$user->create_date|jdatetime}
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label><strong>{@havefnubb~member.common.last.connection@}</strong></label>
                    </div>
                </div>
            </fieldset>
            </div>
            <div id="user-profile-pref">
            <fieldset>
                <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label><strong>{@havefnubb~member.common.language@}</strong></label>
                    </div>
                    <div class="form_value">
                        {$user->member_language}
                    </div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label><strong>{@havefnubb~member.common.account.signature@}</strong></label>
                    </div>
                    <div class="form_value">
                        {$user->member_comment|wiki|stripslashes}
                    </div>
                </div>
            </fieldset>
            </div>
            {hook 'hfbAccountShowDiv',array('user'=>$user->login)}
        </div> <!-- #container -->
    </div>
    <div class="buttons-bar">
        {if $changeAllowed}<a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$user->login)}">{@jcommunity~account.link.profile.edit@}</a>{/if}
        {if $passwordChangeAllowed}<a href="{jurl 'jcommunity~password:index', array('user'=>$user->login)}">{@jcommunity~account.link.account.change.password@}</a>{/if}
        {if $destroyAllowed}<a href="{jurl 'jcommunity~account:destroy', array('user'=>$user->login)}">{@jcommunity~account.link.account.delete@}</a>{/if}
        {foreach $otherPrivateActions as $link=>$label}
            <a href="{$link}">{$label|eschtml}</a>
        {/foreach}
    </div>
    </div>
    {hook 'hfbAccountShowBefore',array($user->login)}
