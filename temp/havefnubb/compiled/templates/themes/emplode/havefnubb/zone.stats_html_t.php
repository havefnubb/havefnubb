<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jlocale.php');
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_9e9dece7efc226e13d30d39fc07dbf75($t){

}
function template_9e9dece7efc226e13d30d39fc07dbf75($t){
?><div class="headings">
    <h3><span><?php echo jLocale::get('havefnubb~main.stats'); ?></span></h3>
</div>
<div id="stats">
    <ul>
        <li><?php echo $t->_vars['posts']; ?> <?php echo jLocale::get('havefnubb~main.messages'); ?> <?php jtpl_function_html_jlocale( $t,'havefnubb~main.in.threads', array($t->_vars['threads']));?> <?php jtpl_function_html_jlocale( $t,'havefnubb~main.posted.by.members' , array($t->_vars['members']));?></li>
        <li><?php echo jLocale::get('havefnubb~main.last.post'); ?> : <a href="<?php jtpl_function_html_jurl( $t,'havefnubb~posts:view',array('id_post'=>$t->_vars['lastPost']->parent_id,'parent_id'=>$t->_vars['lastPost']->parent_id,'id_forum'=>$t->_vars['lastPost']->id_forum,'ptitle'=>$t->_vars['lastPost']->subject,'ftitle'=>$t->_vars['forum']->forum_name));?>" title="<?php echo jLocale::get('havefnubb~main.goto.this.message'); ?>"><?php echo htmlspecialchars($t->_vars['lastPost']->subject); ?></a></li>
        <li><?php echo jLocale::get('havefnubb~main.last.member'); ?> : <a href="<?php jtpl_function_html_jurl( $t,'jcommunity~account:show',array('user'=>$t->_vars['lastMember']->login));?>" title="<?php echo htmlspecialchars($t->_vars['lastMember']->login); ?>"><?php echo htmlspecialchars($t->_vars['lastMember']->login); ?></a></li>
    </ul>
</div><?php 
}
?>