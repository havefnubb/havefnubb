{if $count == 0}
<tr>
    <td>{@hfnupoll~poll.no.answer.defined.begin@}<br/>
<a href="{jurl 'hfnupoll~admin:ans_wizard1',array('id_poll'=>$id_poll)}">{@hfnupoll~poll.no.answer.defined.end@}</a></td>
</tr>
{else}
{foreach $answers as $i => $answer}
<tr>    
    <td><strong>{@hfnupoll~poll.answer@}{$i +1}:</strong> {$answer->answer}</td>
    <td colspan="3"></td>
</tr>
{/foreach}
{/if}