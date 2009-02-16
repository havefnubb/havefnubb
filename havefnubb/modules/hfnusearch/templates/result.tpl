{if $count == 0}
No Result
{else}
{foreach $datas as $data}
Subjet : {$data->subject|eschtml}<br/>Message : {$data->message|eschtml}<hr/>
{/foreach}
{/if}