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
    <h5 id="user" class="user-image">{@havefnubb~member.edit.account.header@} - <a class="user-private-message  user-image" href="{jurl 'havefnubb~members:mail'}" >{@havefnubb~member.internal.messenger@}</a>
{ifacl2 'auth.users.change.password'}
> <a class="user-edit-password user-image" href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a>
{/ifacl2}
    </h5>
</div>
{hook 'hfbAccountEditBefore',array('user'=>$username)}
<div id="profile">
{form $form, 'jcommunity~account:save', array('user'=>$username)}
    <div id="container" class="user-formbg">
        <ul>
            <li><a href="#user-profile-general"><span class="user-general user-image">{@havefnubb~member.general@}</span></a></li>
            <li><a href="#user-profile-pref"><span class="user-pref user-image">{@havefnubb~member.pref@}</span></a></li>
            {hook 'hfbAccountEditTab',array('user'=>$username)}
        </ul>
        {jmessage}
        <div id="user-profile-general">
            <fieldset>
                <legend><span class="user-general user-image">{@havefnubb~member.general@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_nickname" class="user-name user-image"><strong>{ctrl_label 'nickname'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'nickname'}</div>
                    <div class="form_property">
                        <label  for="jforms_jcommunity_account_email" class="user-email user-image"><strong>{ctrl_label 'email'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'email'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                   <div class="form_property">
                        <label for="jforms_jcommunity_account_member_birth_day" class="user-birthday user-image"><strong>{ctrl_label 'member_birth'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_birth'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-email user-image">&nbsp;</span>
                    </div>
                    <div class="form_value"><a href="{jurl 'havefnubb~members:mail'}">{@havefnubb~member.internal.messenger@}</a></div>
                    <div class="clearer">&nbsp;</div>
                </div>

            </fieldset>
            <fieldset>
                <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_member_town" class="user-town user-image"><strong>{ctrl_label 'member_town'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_town'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_member_country" class="user-country user-image"><strong>{ctrl_label 'member_country'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_country'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_member_website" class="user-website user-image"><strong>{ctrl_label 'member_website'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_website'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
            </fieldset>
        </div>

        <div id="user-profile-pref">
            <fieldset>
                <legend><span class="user-pref user-image">{@havefnubb~member.pref@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_member_language" class="user-language user-image"><strong>{ctrl_label 'member_language'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_language'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_member_website" class="user-show-email user-image"><strong>{ctrl_label 'member_show_email'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_show_email'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_value">{@havefnubb~member.account.edit.show.your.email.description@}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_member_comment" class="user-signature user-image"><strong>{ctrl_label 'member_comment'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_comment'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-gravatar user-image"><strong>{ctrl_label 'member_gravatar'}</strong></span>
                    </div>
                    <div class="form_value">{ctrl_control 'member_gravatar'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label for="jforms_jcommunity_account_member_avatar" class="user-avatar user-image"><strong>{ctrl_label 'member_avatar'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_avatar'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
            </fieldset>
        </div>


    {hookinclude 'hfbAccountEditInclude',array('user'=>$username)}
        {hook 'hfbAccountEditDiv',array('user'=>$username)}
    </div>
    <div class="form_row form_row_submit">
        <div class="form_value">{formsubmit}</div>
        <div class="clearer">&nbsp;</div>
    </div>
{/form}
</div>
{hook 'hfbAccountEditAfter',array('user'=>$username)}
