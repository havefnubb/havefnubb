<div id="body">
<div id="msg-install">{jmessage}</div>
{if $step == 'home'}    
<h2>{@hfnuinstall~install.home.welcome@}</h2>
<p>{@hfnuinstall~install.home.process.description@}</p>
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
{form $form, 'hfnuinstall~default:index'}
<div id="config">
<fieldset>
    <legend>{@hfnuinstall~install.config.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}        
</fieldset>
</div>
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>
{/form}
{/if}
{if $step == 'dbconfig'}
{form $form, 'hfnuinstall~default:index'}
<div id="dbconfig">
<fieldset>
    <legend>{@hfnuinstall~install.dbconfig.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}    
</fieldset>
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>
</div>
{/form}
{/if}
{if $step =='installdb'}
{form $form, 'hfnuinstall~default:index'}
<div id="installdb">
<fieldset>
    <legend>{@hfnuinstall~install.installdb.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>    
</fieldset>
</div>
{/form}
{/if}
{if $step =='adminaccount'}
{form $form, 'hfnuinstall~default:index'}
<div id="installdb">
<fieldset>
    <legend>{@hfnuinstall~install.adminaccount.general@}</legend>
    {formcontrols}
    <p>{ctrl_label}<br/> {ctrl_control} </p>
    {/formcontrols}
<div>{formsubmit 'validate'} {formreset 'cancel'}</div>    
</fieldset>
</div>
{/form}
{/if}
{if $step =='end'}
<div id="end">
<fieldset>
    <legend>{@hfnuinstall~install.end.general@}</legend>
    <p>{@hfnuinstall~install.end.general.description@}</p>
    <p><a href="{jurl 'havefnubb~default:index'}">{@hfnuinstall~install.goto.forum@}</a></p>
</fieldset>
</div>
{/if}
</div>