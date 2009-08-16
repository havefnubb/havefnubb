<h1>{@hfnupoll~poll.the.questions@}</h1>
{ifacl2 'hfnu.admin.poll.add'}
<h2>{@hfnupoll~poll.create.a.poll@}</h2>
{formfull $form, 'hfnupoll~admin:savecreate'}
<br/>
{/ifacl2}
<div id="admin-message">{jmessage}</div>
<table width="100%" class="records-list">
<thead>  
    <tr>
        <th  class="records-list">{@hfnupoll~poll.question@}</th>
        <th  class="records-list">{@hfnupoll~poll.date_created@}</th>
        <th  class="records-list">{@hfnupoll~poll.status@}</th>
{ifacl2 'hfnu.admin.poll.edit'}               
        <th  colspan="2" class="records-list">{@hfnupoll~poll.actions@}</th>
{/ifacl2}         
    </tr>
</thead>
<tbody>
{foreach $polls as $poll}
    <tr class="{cycle array('odd','even')}">
        <th>{$poll->question|eschtml}</th>
        <td>{$poll->date_created|jdatetime:'timestamp':'lang_datetime'}</td>
        <td>{$poll->status}</td>
        {ifacl2 'hfnu.admin.poll.delete'}
        <td><a href="{jurl 'hfnupoll~admin:delete',array('id_poll'=>$poll->id_poll)}" title="{$poll->question|eschtml}" onclick="return confirm('{jlocale 'hfnupoll~poll.confirm.deletion',array($poll->question)}')">{@hfnupoll~poll.deletion@}</a></td>
        {/ifacl2}
        {ifacl2 'hfnu.admin.poll.edit'}
        <td><a href="{jurl 'hfnupoll~admin:edit',array('id_poll'=>$poll->id_poll)}" title="{$poll->question|eschtml}">{@hfnupoll~poll.edit@}</a></td>
        {/ifacl2}
    </tr>
{/foreach}
</tbody>
</table>