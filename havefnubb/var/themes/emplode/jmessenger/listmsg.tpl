{zone 'jmessenger~links'}
<div id="messenger-list" class="form_row">
<h3 class="{if isset($send)}user-email-outbox{else}user-email-inbox{/if} user-image">{$title}</h3>

<table width="100%" class="data_table">
	<thead>
		<tr>
			<th class="listcol"></th>
			<th class="listcol">{@jmessenger~message.list.discussion@}</th>
			<th class="listcol">{@jmessenger~message.list.from@}</th>
			<th class="listcol">{@jmessenger~message.list.date@}</th>
			<th class="listcol">{@jmessenger~message.list.actions@}</th>
		</tr>
	</thead>
	{if $msg->rowCount() > 0}
	<tbody>
	{foreach $msg as $m}
	<tr id="mail{$m->id}" {if $m->isSeen == 0 && !isset($send)}class="new"{/if}>
		<td><input class='msg_select' type='checkbox' value="{$m->id}" name='msg_select[]'/></td>
		<td class="coltitle linkincell"><a href="{jurl 'jmessenger~jmessenger:view', array('id'=>$m->id)}">{$m->title}</a> {if $m->isSeen == 0 && !isset($send)}({@jmessenger~message.new@} !){/if}</td>
		<td class="colposter linkincell">
		{if isset($send)}
			<a href="{jurl 'jcommunity~account:show', array('user'=>$m->loginFor)}">{$m->loginFor}</a>
		{else}
			<a href="{jurl 'jcommunity~account:show', array('user'=>$m->loginFor)}">{$m->loginFrom}</a>
		{/if}
		</td>
		<td  class="colright linkincell">
			{$m->date|jdatetime:'db_datetime':'lang_date'}, {$m->date|jdatetime:'db_datetime':'db_time'}
		</td>
		<td class="colright linkincell">
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
</div>
