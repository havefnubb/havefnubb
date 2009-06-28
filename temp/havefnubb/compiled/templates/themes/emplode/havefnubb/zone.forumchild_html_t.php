<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_956ff55bb02c39852b90a02250009657($t){

}
function template_956ff55bb02c39852b90a02250009657($t){
?><?php if($t->_vars['childs'] > 0):?>
<?php if($t->_vars['calledFrom'] == 'home'):?>
<ul class="subforum-home">
    <li><?php echo jLocale::get('havefnubb~forum.forumchild.subforum'); ?> :</li>
<?php foreach($t->_vars['forumChilds'] as $t->_vars['forum']):?>
    <li><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~posts:lists',array('id_forum'=>$t->_vars['forum']->id_forum,'ftitle'=>$t->_vars['forum']->forum_name));?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a>,</li>
<?php endforeach;?>
</ul>
<?php else:?>
<div class="subforum box_title">
    <h3><?php echo jLocale::get('havefnubb~forum.forumchild.subforum'); ?></h3>
</div>
<table class="data_table" width="100%">
<?php foreach($t->_vars['forumChilds'] as $t->_vars['forum']):?>
    <tr>
        <td class="line colleft <?php echo jZone::get('havefnubb~newestposts',array('id_forum'=>$t->_vars['forum']->id_forum));?>"></td>
        <td class="line colmain linkincell"><h4 class="forumtitle"><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~posts:lists',array('id_forum'=>$t->_vars['forum']->id_forum,'ftitle'=>$t->_vars['forum']->forum_name));?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a></h4><span class="forumdesc"><?php echo htmlspecialchars($t->_vars['forum']->forum_desc); ?></span></td>
        <td class="line colstats"><?php echo jZone::get('havefnubb~postandmsg',array('id_forum'=>$t->_vars['forum']->id_forum));?></td>
        <td class="line colright linkincell"><span class="smalltext"><strong><?php echo jLocale::get('havefnubb~main.last.message'); ?></strong>
        <?php echo jZone::get('havefnubb~postlc',array('id_forum'=>$t->_vars['forum']->id_forum));?></span></td>
    </tr>
<?php endforeach;?>
</table>
<?php endif;?>
<?php endif;?><?php 
}
?>