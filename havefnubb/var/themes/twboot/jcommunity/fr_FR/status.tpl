<script type="text/javascript">
//<![CDATA[
    $('#topbar').dropdown();
//]]>
</script>
<ul class="nav secondary-nav">
    <li class="dropdown" data-dropdown="dropdown" >
    {ifuserconnected}
        <a href="#" class="dropdown-toggle">{$login}</a>
        <ul class="dropdown-menu">
            <li><a href="{jurl 'jcommunity~account:prepareedit', array('user'=>$login)}">{@havefnubb~member.status.your.account@}</a></li>
            <li class="divider"></li>
            <li><a href="{jurl 'jcommunity~login:out'}">{@havefnubb~main.logout@}</a></li>
        </ul>
    {else}
        <a href="#" class="dropdown-toggle">{@havefnubb~member.status.welcome@}</a>
        <ul class="dropdown-menu">
            <li><a href="{jurl 'jcommunity~login:index'}">Connexion</a></li>
            {if $canRegister}<li><a href="{jurl 'jcommunity~registration:index'}">Inscription</a></li>{/if}
            {if $canResetPassword}<li><a href="{jurl 'jcommunity~password:index'}">Mot de pass oublié</a></li>{/if}
        </ul>
    {/ifuserconnected}
    </li>
</ul>
