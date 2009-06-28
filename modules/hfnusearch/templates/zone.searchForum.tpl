{form $form , 'hfnusearch~default:query'}
  <fieldset><legend>Forums</legend>
  <p>{@hfnusearch~forum.search.in.a.particular.forum@}</p>
  <p>{ctrl_label 'hfnu_q'} {ctrl_control 'hfnu_q'}</p>  
  <p>{ctrl_label 'param'} {ctrl_control 'param'}</p>
  {formsubmit 'validate'}
  </fieldset>
{/form}