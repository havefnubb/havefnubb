{meta_html assets 'hfnuaccount'}
{hook 'hfbAccountEditBefore',array('user'=>$username)}
<div class="box">
    <h3>{@jcommunity~account.form.edit.profile.title@}</h3>
    <div class="box-content">
        {form $form, 'jcommunity~account:save', array('user'=>$username)}
        <div id="container">
            <ul class="nav">
                <li><a href="#user-profile-general">{@havefnubb~member.general@}</a></li>
                <li><a href="#user-profile-pref">{@havefnubb~member.pref@}</a></li>
                {hook 'hfbAccountEditTab',array('user'=>$username)}
            </ul>
            {jmessage}
            <div id="user-profile-general">
            <fieldset>
                <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-name user-image"><strong>{ctrl_label 'nickname'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'nickname'}</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-email user-image"><strong>{ctrl_label 'email'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'email'}</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-email user-image">&nbsp;</span>
                    </div>
                    <div class="form_value"><a href="{jurl 'havefnubb~members:mail'}">{@havefnubb~member.internal.messenger@}</a></div>
                </div>
            </fieldset>
            <fieldset>
                <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-town user-image"><strong>{ctrl_label 'member_town'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_town'}</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-country user-image"><strong>{ctrl_label 'member_country'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_country'}</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-website user-image"><strong>{ctrl_label 'member_website'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_website'}</div>
                </div>
            </fieldset>
            </div>
            <div id="user-profile-pref">
            <fieldset>
                <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-language user-image"><strong>{ctrl_label 'member_language'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_language'}</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-show-email user-image"><strong>{ctrl_label 'member_show_email'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_show_email'}</div>
                </div>
                <div class="form_row">
                    <div class="form_value">{@havefnubb~member.account.edit.show.your.email.description@}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-avatar user-image"><strong>{ctrl_label 'member_avatar'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_avatar'}</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-gravatar user-image"><strong>{ctrl_label 'member_gravatar'}</strong></span>
                        <br/>
                    </div>
                    <div class="form_value">{ctrl_control 'member_gravatar'}</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-signature user-image"><strong>{ctrl_label 'member_comment'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_comment'}</div>
                </div>
            </fieldset>
            </div>
      {hookinclude 'hfbAccountEditInclude',array('user'=>$username)}
            {hook 'hfbAccountEditDiv',array('user'=>$username)}
        </div> <!-- #container -->
        <div class="form_row form_row_submit">
            <div class="form_value">{formsubmit}</div>
            <div class="clearer">&nbsp;</div>
        </div>
    {/form}
    </div> <!-- #box-content -->
</div> <!-- #box -->
{hook 'hfbAccountEditAfter',array('user'=>$username)}
