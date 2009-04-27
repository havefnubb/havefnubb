<div id="breadcrumbtop" class="headbox">
    <h3>{@hfnusearch~search.search.perform@}</h3>    
</div>
<div id="hfnusearch">
<div id="post-message">{jmessage}</div>
<form action="{formurl 'hfnusearch~default:query'}" method="post">  
  <fieldset><legend>{@hfnusearch~search.in.all.forums@}</legend>
  <div>
    {formurlparam 'hfnusearch~default:query'}
    {@hfnusearch~search.hfnu_q.search@}
    <input type="hidden" name="perform_search_in" value="words"/>
    <input type="text" name="hfnu_q" size="31" />
    <input class="jforms-submit" type="submit" name="validate" value="{@hfnusearch~forum.search.okBt@}" />
  </div>
  </fieldset> 
</form>
{zone 'hfnusearch~searchForum'}
{zone 'hfnusearch~searchAuthor'}
</div>