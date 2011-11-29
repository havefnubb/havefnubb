<div id="breadcrumbtop" class="headbox">
    <h2>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a></h2>
</div>
<div id="post-split-to">
{if $step == 1}
    {form $form, 'havefnubb~postsmgr:splitedTo',array('id_forum'=>$id_forum,'id_post'=>$id_post,'thread_id'=>$thread_id)}
        <fieldset>
            <div class="legend">{@havefnubb~main.split.this.thread.from.this.message@} : "{$title|eschtml}"</div>
            <div class="form_row">
                <div class="form_property">{ctrl_label 'choice'} </div>
                <div class="form_value">{ctrl_control 'choice'}</div>
                <div class="clearer">&nbsp;</div>
            </div>

            <div class="form_row form_row_submit">
                <div class="form_value">
                    {formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
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
