<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_6abea90c14a8e97843c1042283c6b503($t){

}
function template_6abea90c14a8e97843c1042283c6b503($t){
?><?php if($t->_vars['nbMembers'] > 0 ):?>
<div class="headings">
    <h3><span><?php echo jLocale::get('havefnubb~main.member.online.today'); ?></span></h3>
</div>
<div id="online-today">
<ul class="user-online-today">
<?php foreach($t->_vars['members'] as $t->_vars['member']):?>
    <li><a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:show',array('user'=>$t->_vars['member']->login));?>" title="<?php echo htmlspecialchars($t->_vars['member']->login); ?>"><?php echo htmlspecialchars($t->_vars['member']->login); ?></a>,</li>
<?php endforeach;?>
</ul>
</div>
<?php endif;?><?php 
}
?>