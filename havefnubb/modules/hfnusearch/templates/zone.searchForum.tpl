{form $form , 'hfnusearch~forum:index'}
  <fieldset><legend>Forums</legend>
  <p>{@hfnusearch~forum.search.in.a.particular.forum@}</p>
  <p>{ctrl_label 'hfnu_q'} {ctrl_control 'hfnu_q'}</p>  
  <p>{ctrl_label 'id_forum'} {ctrl_control 'id_forum'}</p>
  {formsubmit 'validate'}
  </fieldset>
{/form}