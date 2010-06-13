<div class="box">
    <div class="block">
    <div id="msg-install">{jmessage}</div>
{if $step == 'home'}
    <h3>{@hfnuinstall~install.home.introduction@}</h3>
    <p>{@hfnuinstall~install.home.process.description@}</p>
    {if $chmod_msg == '*NIX'}
    {zone 'rights_msg'}
    {/if}
    <h3>{@hfnuinstall~install.home.lets.go@}</h3>
    <p><a href="{jurl 'hfnuinstall~default:index', array('step'=>'check')}">{@hfnuinstall~install.home.process.start@}</a></p>
{/if}
{if $step == 'check'}
    {if $continue}
        <p><a class="submit-next"  href="{jurl 'hfnuinstall~default:index', array('step'=>'config')}">{@hfnuinstall~install.next@}</a></p>
    {else}
        <p>{@hfnuinstall~install.check.the.prerequisites.are.not.satisfying@}</p>
    {/if}
{/if}
{if $step == 'config'}
    {if $err === false}
{form $form, 'hfnuinstall~default:index'}
<fieldset>
    <legend>{@hfnuinstall~install.config.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}
</fieldset>

<div>{formsubmit 'validate'} {formreset 'cancel'}</div>
{/form}
    {else}
    {@hfnuinstall~install.reload.the.page.after.having.fixed.those.errors@}
    {/if}
{/if}
{if $step == 'dbconfig'}
{form $form, 'hfnuinstall~default:index'}

<fieldset>
    <legend>{@hfnuinstall~install.dbconfig.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}
</fieldset>
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>

{/form}
{/if}
{if $step =='installdb'}
{form $form, 'hfnuinstall~default:index'}

<fieldset>
    <legend>{@hfnuinstall~install.installdb.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>
</fieldset>

{/form}
{/if}
{if $step =='adminaccount'}
{form $form, 'hfnuinstall~default:index'}

<fieldset>
    <legend>{@hfnuinstall~install.adminaccount.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>
</fieldset>

{/form}
{/if}
{if $step =='end'}

<fieldset>
    <legend>{@hfnuinstall~install.end.general@}</legend>
    <p>{@hfnuinstall~install.end.general.description@}</p>
    <p><a href="{jurl 'havefnubb~default:index'}">{@hfnuinstall~install.goto_forum@}</a></p>
</fieldset>

{/if}
    </div>
</div>
