<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_652eac0907fbcbd0df74883103d23b0a($t){

}
function template_652eac0907fbcbd0df74883103d23b0a($t){
?><?php  if(jAuth::isConnected()):?>
    <ul>
        <li><?php echo jLocale::get('havefnubb~member.status.welcome'); ?> <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:prepareedit', array('user'=>$t->_vars['login']));?>"><?php echo $t->_vars['login']; ?>.</a></li>
    </ul>
    <ul>
        <li><a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:prepareedit', array('user'=>$t->_vars['login']));?>"><?php echo jLocale::get('havefnubb~member.status.your.account'); ?></a></li>
        - <li><a href="<?php jtpl_function_html_jurl( $t,'jcommunity~login:out');?>"><?php echo jLocale::get('havefnubb~main.logout'); ?></a></li>
    </ul>   
<?php  endif; ?>
<?php 
}
?>