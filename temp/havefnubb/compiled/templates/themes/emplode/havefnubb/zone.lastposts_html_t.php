<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/common/modifier.jdatetime.php');
function template_meta_12f16e084eeae5a7521e22db3d1f087b($t){

}
function template_12f16e084eeae5a7521e22db3d1f087b($t){
?><div class="headings">
    <h3><span><?php echo jLocale::get('havefnubb~main.last.messages'); ?></span></h3>
</div>
<div id="lastposts" >
<table width="100%">
<?php foreach($t->_vars['lastPost'] as $t->_vars['post']):?>
<tr>
    <td><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~posts:view',array('id_post'=>$t->_vars['post']->id_post,'parent_id'=>$t->_vars['post']->parent_id,'id_forum'=>$t->_vars['post']->id_forum,'ptitle'=>$t->_vars['post']->subject,'ftitle'=>$t->_vars['post']->forum_name));?>" title="<?php echo jLocale::get('havefnubb~forum.forumlist.view.this.subject'); ?>"><?php echo htmlspecialchars($t->_vars['post']->subject); ?></a>    </a></td>
    <td id="lastposts-date"><?php echo jtpl_modifier_common_jdatetime($t->_vars['post']->date_modified,'timestamp','lang_datetime'); ?></td>
</tr>
<?php endforeach;?>
</table>
</div><?php 
}
?>