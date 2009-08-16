<h1>{@hfnupoll~poll.edit.the.poll@}</h1>
{form $form, 'hfnupoll~admin:saveedit'}

<fieldset><legend>{@hfnupoll~poll.edit.the.poll@}</legend>

{formcontrols}
   <p> {ctrl_label} : {ctrl_control} </p>
{/formcontrols}

</fieldset>

<div> {formreset}{formsubmit} </div>

{/form}