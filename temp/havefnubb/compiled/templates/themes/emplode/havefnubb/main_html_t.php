<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.breadcrumb.php');
function template_meta_e8badb2caa6592efee06336e964136e6($t){

}
function template_e8badb2caa6592efee06336e964136e6($t){
?><div id="hfnuhead">
	<div class="center_wrapper">
		
		<div id="toplinks">
			<div id="toplinks_inner">
			<?php echo jZone::get('jcommunity~status');?>
			</div>
		</div>
		<div class="clearer">&nbsp;</div>

		<div id="site_title">
			<h1><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~default:index');?>" ><?php echo $t->_vars['TITLE']; ?></a></h1><p><?php echo $t->_vars['DESC']; ?></p>	
		</div>

	</div>
</div>
<div id="navigation">
	<div class="center_wrapper">
		<?php echo jZone::get('havefnubb~menu',array('selectedMenuItem'=>$t->_vars['selectedMenuItem']));?>
		<div class="clearer">&nbsp;</div>
	</div>
</div>


<div id="main_wrapper_outer">
	<div id="main_wrapper_inner">
		<div class="center_wrapper">
				<div id="main_content">
<?php  if(jAuth::isConnected()): else:?>
				<div id="login-status">
					<ul>
						<li><?php echo jLocale::get('havefnubb~member.status.welcome.and.thanks.to'); ?> <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~login:index');?>"><?php echo jLocale::get('havefnubb~member.status.connect'); ?></a></li>
						<li><?php echo jLocale::get('havefnubb~member.status.or.to'); ?> <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~registration:index');?>"><?php echo jLocale::get('havefnubb~member.status.register'); ?></a></li>
						<li><?php echo jLocale::get('havefnubb~member.status.or.maybe'); ?> <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~password:index');?>"><?php echo jLocale::get('havefnubb~member.status.forgotten.password'); ?> ?</a></li>
					</ul>
				</div>
<?php  endif; ?>
				<?php echo $t->_vars['MAIN']; ?>
				</div>
			<div class="clearer">&nbsp;</div>
		</div>
	</div>
</div>

<div class="breadcrumb">
<?php jtpl_function_html_breadcrumb( $t,8, ' > ');?>
</div>

<div id="dashboard">
	<div id="dashboard_content">
		<div class="center_wrapper">

			<div class="col3 left">
				<div class="col3_content">
					<?php echo jZone::get('havefnubb~lastposts');?>
				</div>
			</div>

			<div class="col3mid left">
				<div class="col3_content">
					<?php echo jZone::get('havefnubb~online');?>
					<?php echo jZone::get('havefnubb~online_today');?>
				</div>
			</div>

			<div class="col3 right">
				<div class="col3_content">
					<?php echo jZone::get('havefnubb~stats');?>
					<?php echo jZone::get("jtags~tagscloud",array('destination'=>'havefnubb~default:cloud'));?>    					
				</div>
			</div>

			<div class="clearer">&nbsp;</div>

		</div>
	</div>
</div>

<div id="footer">
	<div class="center_wrapper">

		<div class="left">
			<p><span><?php echo jLocale::get('havefnubb~main.poweredby'); ?> <a href="http://www.havefnubb.org" title="HaveFnuBB!">HaveFnuBB!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a></span></p>
		</div>
		<div class="right">
			<a href="http://templates.arcsin.se/">Website template</a> by <a href="http://arcsin.se/">Arcsin</a> 
		</div>
		
		<div class="clearer">&nbsp;</div>

	</div>
</div>


<!-- #footer -->
<?php 
}
?>