{ifuserconnected}
<div id="breadcrumbtop" class="headbox box_title">
    <h3>{@havefnubb~member.identity@}</h3>    
</div>
<div id="loginbox">
    <div class="form_row">
    <p>{jlocale 'havefnubb~member.login.welcome', array($login)}
    (<a href="{jurl 'jcommunity~account:show', array('user'=>$login)}">{@havefnubb~member.login.your.account@}</a>,
    <a href="{jurl 'jcommunity~login:out'}">{@havefnubb~member.login.logout@}</a>)
    </p>
    </div>
</div>
{else}
<div id="breadcrumbtop" class="headbox  box_title">
    <h3>{@havefnubb~main.login.connection@}</h3>
</div>
<div id="loginbox">
    {form $form, 'jcommunity~login:in'}
        <div class="form_row">            
            <div class="form_property">{ctrl_label 'auth_login'}</div>
            <div class="form_value">{ctrl_control 'auth_login'}</div>
            
            <div class="form_property">{ctrl_label 'auth_password'}</div>
            <div class="form_value">{ctrl_control 'auth_password'}</div>        
        {if $persistance_ok}
            <div class="form_property">{ctrl_label 'auth_remember_me'}</div> <div class="form_value">{ctrl_control 'auth_remember_me'}</div>
        {/if}
            <div class="clearer">&nbsp;</div>
        </div>        

        <div class="form_row form_row_submit">
            <div class="form_value">{formsubmit}</div>
            <div class="clearer">&nbsp;</div>
        </div>        
        {if $url_return}
            <input type="hidden" name="auth_url_return" value="{$url_return|eschtml}" />
        {/if}
    {/form}
    <div class="form_row">
        <div id="loginbox-links">
            (<a href="{jurl 'jcommunity~registration:index'}">{@havefnubb~member.login.register@}</a>, 
            <a href="{jurl 'jcommunity~password:index'}">{@havefnubb~member.login.forgotten.password@}</a>)
        </div>
    </div>
</div>
{/ifuserconnected}