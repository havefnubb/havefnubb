<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jmessage.php');
function template_meta_1fe6c5068c6dd52e179f5d2ad9c859aa($t){

}
function template_1fe6c5068c6dd52e179f5d2ad9c859aa($t){
?>    <div id="breadcrumbtop" class="headbox">
        <h3><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~default:index');?>" title="<?php echo jLocale::get('havefnubb~main.home'); ?>"><?php echo jLocale::get('havefnubb~main.home'); ?></a><?php if($t->_vars['action'] == 'view'):?> > <?php echo htmlspecialchars($t->_vars['category']->cat_name);  endif;?></h3>
    </div>
    <div id="post-message"><?php jtpl_function_html_jmessage( $t);?></div>	
<?php if($t->_vars['action'] == 'index'):?>
<?php foreach($t->_vars['categories'] as $t->_vars['category']):?>
    <div class="category box_title">
        <h3><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~category:view',array('id_cat'=>$t->_vars['category']->id_cat,'ctitle'=>$t->_vars['category']->cat_name));?>" title="<?php echo htmlspecialchars($t->_vars['category']->cat_name); ?>"><?php echo htmlspecialchars($t->_vars['category']->cat_name); ?></a></h3>
    </div>
    <?php  if(jAcl2::check('hfnu.forum.list','forum'.$t->_vars['category']->id_forum)):?>
    <?php echo jZone::get('havefnubb~forum',array('action'=>'index','id_cat'=>$t->_vars['category']->id_cat));?>
    <?php  endif; ?>
<?php endforeach;?>
<?php elseif($t->_vars['action'] == 'view'):?>
    <?php echo jZone::get('havefnubb~forum',array('action'=>'view','id_cat'=>$t->_vars['category']->id_cat,'ctitle'=>$t->_vars['category']->cat_name));?>
<?php endif;?>
<?php 
}
?>