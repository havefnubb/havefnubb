<div class="box">
    <h3>{@jcommunity~register.registration.confirm.title@}</h3>
    <div class="box-content">

        <p class="jcommunity-{if $status == 'ok'}notice{else}error{/if}"
        >{@jcommunity~register.registration.confirm.$status@}</p>
        <p><a href="{jurl 'jcommunity~login:index'}">{@jcommunity~login.back.to.login@}</a></p>

    </div>
</div>
