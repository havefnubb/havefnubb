<h1>{@hfnupoll~poll.the.poll@}</h1>
{@hfnupoll~poll.poll.process.description@}
<h2>{@hfnupoll~poll.creation.of.answers@}</h2>

<form action="{formurl 'hfnupoll~admin:answersave'}" method="post">
    <fieldset>
        <legend>{@hfnupoll~poll.creation.of.answers@}</legend>        
    {for $i = 0 ; $i < $nb_answers ; $i++}
    {@hfnupoll~poll.answer@}{$i + 1} <input type="text" name="answers[{$i}]" id="answer_{$i}" size="40" maxlength="255"/><br />
    {/for}
    <div class="jforms-submit-buttons">
    {formurlparam 'hfnupoll~admin:answersave'}
    <input type="hidden" name="id_poll" value="{$id_poll}"/>
    <input type="hidden" name="nb_answers" value="{$nb_answers}"/>
    <input type="submit" name="validate" id="jforms_hfnupoll_poll_wizard2_validate" class="jforms-submit" value="{@hfnupoll~poll.saveBt@}"/>
    <input type="hidden" name="hfnutoken" value="{$hfnutoken}"/>
    </div>
    </fieldset>
</form>
