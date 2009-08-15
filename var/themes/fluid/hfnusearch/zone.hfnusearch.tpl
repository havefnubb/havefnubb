{meta_html css $j_themepath .'css/hfnusearch.css'}
{meta_html js  $j_jelixwww . 'jquery/jquery.js'}
{meta_html js  $j_basepath . 'js/jquery.autocomplete.pack.js'}
{meta_html css $j_basepath . 'js/jquery.autocomplete.css'}
{$javascript}
<div class="box">
    <h3><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > {@hfnusearch~search.search.perform@}</h3>
</div>
<div class="box">
    <h2>{@hfnusearch~search.search.perform@}</h2>
    <div class="block">        
        <div id="post-message">{jmessage}</div>
        <form action="{formurl 'hfnusearch~default:query'}" method="post">  
        <fieldset>
        <legend>{@hfnusearch~search.in.all.forums@}</legend>
        <div class="form_row">
            {formurlparam 'hfnusearch~default:query'}
            <input type="hidden" name="perform_search_in" value="words"/>
            <div class="form_property">{@hfnusearch~search.hfnu_q.search@}</div>
            
            <div class="form_value">
                <input type="text" id="hfnu_q" name="hfnu_q" size="31" />    
            </div>
            <div class="clearer">&nbsp;</div>
    
        </div>
        <div class="form_row form_row_submit">    
            <div class="form_value">
                <input class="submit" type="submit" name="validate" value="{@hfnusearch~forum.search.okBt@}" />
            </div>
            <div class="clearer">&nbsp;</div>
        </div>
      </fieldset> 
    </form>
    {zone 'hfnusearch~searchForum'}
    {zone 'hfnusearch~searchAuthor'}
    </div>     
</div>    