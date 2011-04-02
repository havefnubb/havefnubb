<fieldset>
<legend>{@havefnubb~member.private.messaging@}</legend>
    <div class="block grid_16">    
        <div class="grid_5">    
         {zone 'jmessenger~links'}
        </div>
        <div class="grid_11">
            <fieldset>
                <legend><span class="{if isset($send)}user-email-outbox{else}user-email-inbox{/if} user-image">{$title}</span></legend>
                <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>{@jmessenger~message.list.discussion@}</th>
                        <th>{@jmessenger~message.list.from@}</th>
                        <th>{@jmessenger~message.list.date@}</th>
                        <th>{@jmessenger~message.list.actions@}</th>
                    </tr>
                </thead>
                {if $msg->rowCount() > 0}
                <tbody>
            {foreach $msg as $m}
                <tr id="mail{$m->id}" {if $m->isSeen == 0 && !isset($send)}class="new"{/if}>
                    <td><input class='msg_select' type='checkbox' value="{$m->id}" name='msg_select[]'/></td>
                    <td><a href="{jurl 'jmessenger~jmessenger:view', array('id'=>$m->id)}">{$m->title}</a> {if $m->isSeen == 0 && !isset($send)}({@jmessenger~message.new@} !){/if}</td>
                    <td>{if isset($send)}
                        <a href="{jurl 'jcommunity~account:show', array('user'=>$m->loginFor)}">{$m->loginFor}</a>
                    {else}
                        <a href="{jurl 'jcommunity~account:show', array('user'=>$m->loginFor)}">{$m->loginFor}</a>
                    {/if}
                    </td>
                    <td>
                        {$m->date|jdatetime:'db_datetime':'lang_date'}, {$m->date|jdatetime:'db_datetime':'db_time'}
                    </td>
                    <td>
                        {if !isset($send)}
                        <a class="messenger-email-answer user-image"  href="{jurl 'jmessenger~jmessenger:answer', array('id'=>$m->id)}">{@message.answer@}</a>
                        {/if}
                        <a class="messenger-email-delete user-image" href="{jurl 'jmessenger~jmessenger:delete', array('id'=>$m->id)}">{@message.delete@}</a>
                    </td>
                </tr>
            {/foreach}
                </tbody>
                {/if}
                </table>
            </fieldset>
        </div>
    </div>
</fieldset>