<h1>{@hfnupoll~poll.the.poll@}</h1>
{@hfnupoll~poll.poll.process.description@}
{ifacl2 'hfnu.admin.poll.add'}
<h2>{@hfnupoll~poll.create.a.poll@}</h2>
{formfull $form, 'hfnupoll~admin:savecreate'}
<br/>
{/ifacl2}
{ifacl2 'hfnu.admin.poll.edit'}
<form action="{formurl 'hfnupoll~admin:saveedit'}" method="post">
{/ifacl2}
<table width="100%" class="records-list">
<thead>  
    <tr>
        <th  class="records-list">{@hfnupoll~poll.question@}</th>        
        <th  class="records-list">{@hfnupoll~poll.status@}</th>
        <th  class="records-list">{@hfnupoll~poll.date_created@}</th>        
{ifacl2 'hfnu.admin.poll.delete'}               
        <th  class="records-list">{@hfnupoll~poll.actions@}</th>
{/ifacl2}         
    </tr>
</thead>
<tbody>
{foreach $polls as $poll}
    <tr class="{cycle array('odd','even')}">
{ifacl2 'hfnu.admin.poll.edit'}
        <th><input type="text" size="40" name="question[{$poll->id_poll}]" value="{$poll->question}" /></th>
        <td><input type="text" size="1" name="status[{$poll->id_poll}]" value="{$poll->status}" /></td>
{else}
        <th><{$poll->question|eschtml}</th>
        <td>{$poll->status}</td>                
{/ifacl2}
        <td>{$poll->date_created|jdatetime:'timestamp':'lang_datetime'}</td>        
        <td><input type="hidden" name="id_poll[{$poll->id_poll}]" value="{$poll->id_poll}" />{ifacl2 'hfnu.admin.poll.delete'}<a href="{jurl 'hfnupoll~admin:delete',array('id_poll'=>$poll->id_poll)}" title="{$poll->question|eschtml}" onclick="return confirm('{jlocale 'hfnupoll~poll.confirm.deletion',array($poll->question)}')">{@hfnupoll~poll.deletion@}</a>{/ifacl2}</td>
        {zone 'hfnupoll~answers',array('id_poll'=>$poll->id_poll)}
{/foreach}
</tbody>
</table>
{ifacl2 'hfnu.admin.poll.edit'}
<div class="jforms-submit-buttons">
    {formurlparam 'hfnupoll~admin:saveedit'}    
    <input type="submit" name="validate" id="jforms_hfnupoll_poll_edit_validate" class="jforms-submit" value="{@hfnupoll~poll.saveBt@}"/>
    <input type="hidden" name="hfnutoken" value="{$hfnutoken}"/>
</div>
</form>
{/ifacl2}