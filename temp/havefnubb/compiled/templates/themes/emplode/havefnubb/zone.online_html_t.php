<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_0924d5a4b59f8d7de6faa0a3ec4f8518($t){

}
function template_0924d5a4b59f8d7de6faa0a3ec4f8518($t){
?><?php if($t->_vars['nbMembers'] > 0 ):?>
<div class="headings">
    <h3><span><?php echo jLocale::get('havefnubb~main.member.currently.online'); ?></span></h3>
</div>
<div id="currently-online">
<ul class="user-currently-online">   
<?php foreach($t->_vars['members'] as $t->_vars['member']):?>
    <li><a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:show',array('user'=>$t->_vars['member']->login));?>" title="<?php echo htmlspecialchars($t->_vars['member']->login); ?>"><?php echo htmlspecialchars($t->_vars['member']->login); ?></a>,</li>
<?php endforeach;?>
</ul>
</div>
<?php endif;?><?php 
}
?>