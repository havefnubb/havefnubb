<div id="breadcrumbtop" class="headbox">
	<h3>{@havefnubb~main.common.you.are.here@} <a href="{jurl 'havefnubb~default:index'}" title="{@havefnubb~main.home@}">{@havefnubb~main.home@}</a> > <a href="{jurl 'havefnubb~category:view',array('id_cat'=>$category->id_cat,'ctitle'=>$category->cat_name)}" title="{$category->cat_name}">{$category->cat_name|eschtml}</a> > <a href="{jurl 'havefnubb~posts:lists',array('id_forum'=>$forum->id_forum,'ftitle'=>$forum->forum_name)}" title="{$forum->forum_name|eschtml}">{$forum->forum_name|eschtml}</a></h3>
</div>
<div id="post-notify">
{form $form, $submitAction, array('id_post'=>$id_post)}
	<fieldset>
		<div class="legend">{$heading}</div>
			<div class="form_row">
				<div class="form_property">{ctrl_label 'reason'}</div>
				<div class="form_value">{ctrl_control 'reason'}</div>
				<div class="clearer">&nbsp;</div>
			</div>
			<div class="form_row">
				<div class="form_property">{ctrl_label 'message'} </div>
				<div class="form_value">{ctrl_control 'message'}</div>
				{literal}
				<script type="text/javascript">
				//<![CDATA[
				$(document).ready(function()	{
					$('#jforms_havefnubb_posts_message').markItUp(mySettings);
				});
				//]]>
				</script>
				{/literal}
				<div class="clearer">&nbsp;</div>
			</div>

			<div class="form_row form_row_submit">
				<div class="form_value">
					{formsubmit 'validate'} {formreset 'reset'} {gobackto 'havefnubb~main.go.back.to'}
				</div>
				<div class="clearer">&nbsp;</div>
			</div>
	</fieldset>
{/form}
</div>
