{meta_html js $j_jelixwww.'jquery/jquery.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.core.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.widget.min.js'}
{meta_html js $j_jelixwww.'jquery/ui/jquery.ui.tabs.min.js'}
{literal}
<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $("#container").tabs();
});
//]]>
</script>
{/literal}
{hook 'hfbAccountEditBefore',array('user'=>$username)}
<div class="box">
    <h2>{@havefnubb~member.edit.account.header@}</h2>
    <div class="block">
        {form $form, 'jcommunity~account:save', array('user'=>$username)}
        <div id="container">
            <ul class="nav main">
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
                        <label class="user-name user-image"><strong>{ctrl_label 'nickname'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'nickname'}</div>
                    <div class="form_property">
                        <label class="user-email user-image"><strong>{ctrl_label 'email'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'email'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                   <div class="form_property">
                        <label class="user-birthday user-image"><strong>{ctrl_label 'member_birth'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_birth'}</div>
                    {ifacl2 'auth.users.change.password'}
                    <div class="form_value"><a class="user-edit-password user-image"  href="{jurl 'havefnubb~members:changepwd', array('user'=>$username)}">{@havefnubb~member.pwd.change.of.password@}</a></div>
                    {/ifacl2}
                    <div class="clearer">&nbsp;</div>
                </div>
          <div class="form_row">
                    <div class="form_property">
                        <label class="user-email user-image">&nbsp;</label>
                    </div>
                    <div class="form_value"><a href="{jurl 'havefnubb~members:mail'}">{@havefnubb~member.internal.messenger@}</a></div>
                    <div class="clearer">&nbsp;</div>
                </div>

            </fieldset>
            <fieldset>
                <legend><span class="user-location user-image">{@havefnubb~member.common.location@}</span></legend>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-town user-image"><strong>{ctrl_label 'member_town'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_town'}</div>
                    <div class="form_property">
                        <label class="user-country user-image"><strong>{ctrl_label 'member_country'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_country'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-website user-image"><strong>{ctrl_label 'member_website'}</strong></label>
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
                        <label class="user-language user-image"><strong>{ctrl_label 'member_language'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_language'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-show-email user-image"><strong>{ctrl_label 'member_show_email'}</strong></label>
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
                        <label class="user-avatar user-image"><strong>{ctrl_label 'member_avatar'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_avatar'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-gravatar user-image"><strong>{ctrl_label 'member_gravatar'}</strong></label>
                        <br/>
                    </div>
                    <div class="form_value">{ctrl_control 'member_gravatar'}</div>
                    <div class="clearer">&nbsp;</div>
                </div>
                <div class="form_row">
                    <div class="form_property">
                        <label class="user-signature user-image"><strong>{ctrl_label 'member_comment'}</strong></label>
                    </div>
                    <div class="form_value">{ctrl_control 'member_comment'}</div>
                    <div class="clearer">&nbsp;</div>
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
    </div> <!-- #block -->
</div> <!-- #box -->
{hook 'hfbAccountEditAfter',array('user'=>$username)}
