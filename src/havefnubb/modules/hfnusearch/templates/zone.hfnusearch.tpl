{meta_html assets 'hfnusearch'}

<div class="breadcrumb">
<ol>
    <li>{@havefnubb~main.common.you.are.here@}</li>
    <li><a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> >></li>
    <li>{@hfnusearch~search.search.perform@}</li>
</ol>
</div>

{hook 'hfbBeforeSearch'}
<div class="box">
    <h3>{@hfnusearch~search.search.perform@}</h3>
    <div class="box-content">
        <div id="post-message">{jmessage}</div>
        <form action="{formurl 'hfnusearch~default:query'}" method="post">
        <fieldset>
        <legend>{@hfnusearch~search.in.all.forums@}</legend>
        <div class="form_row">
            {formurlparam 'hfnusearch~default:query'}
            <input type="hidden" name="perform_search_in" value="words"/>
            <div class="form_property"><label class="jforms-label" for="hfnu_q">{@hfnusearch~search.hfnu_q.search@}</label></div>
            <div class="form_value">
                <input type="text" id="hfnu_q" name="hfnu_q" size="31" data-autocomplete-url="{jurl 'hfnusearch~default:queryajax'}"/>
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
    {hook 'hfbSearch'}
    </div>
</div>
{hook 'hfbAfterSearch'}
