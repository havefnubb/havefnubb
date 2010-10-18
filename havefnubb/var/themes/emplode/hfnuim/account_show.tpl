        <div id="user-profile-messenger">
            <fieldset>
                <div class="legend"><span class="user-messenger user-image">{@hfnuim~im.instant.messenger@}</span></div>
                <div class="form_row">
                    <div class="form_property">
                        <span class="user-xfire user-image"><strong>{@hfnuim~im.xfire@}</strong></span>
                    </div>
                    <div class="fom_value">
                        {$xfire|eschtml}
                    </div>
                    <div class="form_property">
                        <span class="user-icq user-image"><strong>{@hfnuim~im.icq@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$icq|eschtml}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>
                 <div class="form_row">
                    <div class="form_property">
                        <span class="user-yim user-image"><strong>{@hfnuim~im.yim@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$yim|eschtml}
                    </div>
                    <div class="form_property">
                        <span class="user-msn user-image"><strong>{@hfnuim~im.hotmail@}</strong></span>
                    </div>
                    <div class="form_value">
                        {if $hotmail != ''}
                        {mailto array('address'=>$hotmail,'encode'=>'hex','text'=>@havefnubb~member.common.email@)}
                        {/if}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>
                 <div class="form_row">
                    <div class="form_property">
                        <span class="user-aim user-image"><strong>{@hfnuim~im.aol@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$aol|eschtml}
                    </div>
                    <div class="form_property">
                        <span class="user-gtalk user-image"><strong>{@hfnuim~im.gtalk@}</strong></span>
                    </div>
                    <div class="form_value">
                        {$gtalk|eschtml}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>
                 <div class="form_row">
                    <div class="form_property">
                        <span class="user-jabber user-image"><strong>{@hfnuim~im.jabber@}</strong></span>
                    </div>
                    <div class="forum_value">
                        {$jabber|eschtml}
                    </div>
                    <div class="clearer">&nbsp;</div>
                </div>

            </fieldset>
        </div>
