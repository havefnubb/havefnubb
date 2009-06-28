<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/common/modifier.jdatetime.php');
function template_meta_4935f80518e47f222bc72c537dc101a6($t){

}
function template_4935f80518e47f222bc72c537dc101a6($t){
?><?php if($t->_vars['msg'] == ''):?>
<a href="<?php jtpl_function_html_jurl( $t,'havefnubb~posts:view', array('id_post'=>$t->_vars['post']->id_post, 'parent_id'=>$t->_vars['post']->parent_id, 'id_forum'=>$t->_vars['post']->id_forum, 'ftitle'=>$t->_vars['post']->forum_name, 'ptitle'=>$t->_vars['post']->subject));?>"
   title="<?php echo jLocale::get('havefnubb~main.goto.this.message'); ?>"><?php echo jtpl_modifier_common_jdatetime($t->_vars['post']->date_modified,'timestamp','lang_datetime'); ?></a> <?php echo jLocale::get('havefnubb~main.by'); ?> <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:show',array('user'=>$t->_vars['user']->login));?>" title="<?php echo htmlspecialchars($t->_vars['user']->login); ?>"><?php echo htmlspecialchars($t->_vars['user']->login); ?></a>
<?php else:?>
<?php echo $t->_vars['msg']; ?>
<?php endif;?><?php 
}
?>