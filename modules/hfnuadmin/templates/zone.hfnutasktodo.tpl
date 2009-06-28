<ul id="hfnuadmin-tasktodo">
{if count($tasks) == 0}
<li>{@hfnuadmin~task.none@}</li>
{/if}
{for $i = 0 ; $i < count($tasks) ; $i++}
<li>{$tasks[0]}</li>
{/for}
</ul>
