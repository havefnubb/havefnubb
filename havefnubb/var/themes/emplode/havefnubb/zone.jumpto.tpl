<div class="headings box_title">
	<h3><span>{@havefnubb~forum.jumpto.jumpto@}</span></h3>
</div>
<div id="jumpto">
	{form $form, 'havefnubb~posts:goesto'}
	<div class="form_row">
		<div class="form_property">{ctrl_label 'id_forum'} </div>
			<div class="form_value">
				{ctrl_control 'id_forum'}
			</div>
			<div class="form_value">
				{formsubmit 'validate'} {formreset 'reset'}
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
	{/form}
</div>
