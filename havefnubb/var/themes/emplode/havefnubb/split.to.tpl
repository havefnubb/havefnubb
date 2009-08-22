<div id="breadcrumbtop" class="headbox">
    <h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a></h3>
</div>
<div id="post-split-to">
{if $step == 1}
    {form $form, 'havefnubb~posts:splitTo'}    
        <fieldset>
            <div class="legend">{@havefnubb~main.split.this.thread.from.this.message@} : "{$title|eschtml}"</div>            
            <div class="form_row">
                <div class="form_property">{ctrl_label 'choice'} </div>
                <div class="form_value">{ctrl_control 'choice'}</div>
                <div class="clearer">&nbsp;</div>
            </div>
            
            <div class="form_row form_row_submit">
                <div class="form_value">
                    {formsubmit 'validate'} {formreset 'cancel'}
                </div>
                <div class="clearer">&nbsp;</div>
            </div>
        </fieldset>
    </div>
    {/form}
{elseif $step == 2}
ok
{/if}
</div>