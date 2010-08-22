{zone 'jmessenger~links'}
<div id="messenger-new-mail" class="form_row">
	{form $form, $submitAction}
	<fieldset>
    <div class="legend"><h3>{@jmessenger~message.action.new@}</h3></div>
	<div class="form_row">
		<div class="form_property">{ctrl_label 'id_for'} </div>
		<div class="form_value">{ctrl_control 'id_for'} </div>
		<div class="clearer">&nbsp;</div>
	</div>
	<div class="form_row">
		<div class="form_property">{ctrl_label 'title'} </div>
		<div class="form_value">{ctrl_control 'title'} </div>
		<div class="clearer">&nbsp;</div>
	</div>
	<div class="form_row">
		<div class="form_property">{ctrl_label 'content'} </div>
		<div class="form_value">
            {ctrl_control 'content'}
            {literal}
            <script type="text/javascript">
            //<![CDATA[
            $(document).ready(function() {
                $('#jforms_jmessenger_jmessenger_content').markItUp(mySettings);
            });
            //]]>
            </script>
            {/literal}
        </div>
		<div class="clearer">&nbsp;</div>
	</div>
	<div class="form_row form_row_submit">
		<div class="form_value">
		{formsubmit '_submit'}
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
    </fieldset>
    {/form}
	<p>&nbsp;<a href="{jurl $listAction}" class="crud-link">{@jelix~crud.link.return.to.list@}</a>.</p>
</div>
<hr/>
{include 'havefnubb~zone.syntax_wiki'}
