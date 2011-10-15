<div id="user-profile-messenger">
    <fieldset>
        <legend><span class="user-messenger user-image">{@hfnuim~im.instant.messenger@}</span></legend>
        <div class="clearfix">
            <label><strong>{@hfnuim~im.xfire@}</strong></label><div class="input">{$xfire|eschtml}</div>
            <label><strong>{@hfnuim~im.icq@}</strong></label><div class="input">{$icq|eschtml}</div>
        </div>
         <div class="clearfix">
            <label><strong>{@hfnuim~im.yim@}</strong></label><div class="input">{$yim|eschtml}</div>
            <label><strong>{@hfnuim~im.hotmail@}</strong></label><div class="input">{if $hotmail != ''}{mailto array('address'=>$hotmail,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}{/if}</div>
        </div>
        <div class="clearfix">
            <label><strong>{@hfnuim~im.aol@}</strong></label><div class="input">{$aol|eschtml}</div>
            <label><strong>{@hfnuim~im.gtalk@}</strong></label><div class="input">{$gtalk|eschtml}</div>
        </div>
         <div class="clearfix">
            <label><strong>{@hfnuim~im.jabber@}</strong></label><div class="input">{$jabber|eschtml}</div>
        </div>
    </fieldset>
</div>
