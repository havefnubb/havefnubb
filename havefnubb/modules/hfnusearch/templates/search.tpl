<div id="breadcrumbtop" class="headbox">
    <h3>{@hfnusearch~search.search.perform@}</h3>    
</div>
<div id="search">
<form action="{formurl 'hfnusearch~default:query'}" method="post">
  
  <fieldset><legend>{@hfnusearch~search.in.all.forums@}</legend>
  <div>
    {formurlparam 'hfnusearch~default:query'}
    {@hfnusearch~search.hfnu_q.search@}
    <input type="text" name="hfnu_q" size="31" />
    <input type="submit" name="sa" value="Rechercher" />
  </div>
  </fieldset> 
</form>
{zone 'hfnusearch~searchForum'}
{zone 'hfnusearch~searchAuthor'}
</div>