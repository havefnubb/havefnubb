<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_40db0cab7ab84b67d4906471e03bfb14($t){

}
function template_40db0cab7ab84b67d4906471e03bfb14($t){
?><table class="data_table" width="100%">
<?php foreach($t->_vars['forums'] as $t->_vars['forum']):?>
<?php if($t->_vars['action'] =='view'):?>
<?php  if(jAcl2::check('hfnu.forum.view','forum'.$t->_vars['forum']->id_forum)):?>
<?php if($t->_vars['forum']->forum_type == 0):?>
    <tr>
        <td class="colleft <?php echo jZone::get('havefnubb~newestposts',array('source'=>'forum','id_forum'=>$t->_vars['forum']->id_forum));?>"></td>
        <td class="colmain linkincell"><h4><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~posts:lists',array('id_forum'=>$t->_vars['forum']->id_forum,'ftitle'=>$t->_vars['forum']->forum_name));?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a></h4><?php echo htmlspecialchars($t->_vars['forum']->forum_desc); ?>
        <?php echo jZone::get('havefnubb~forumchild',array('id_forum'=>$t->_vars['forum']->id_forum,'lvl'=>1,'calledFrom'=>'home'));?></td>
        <td class="colstats"><?php echo jZone::get('havefnubb~postandmsg',array('id_forum'=>$t->_vars['forum']->id_forum));?></td>
        <td class="colright linkincell"><span class="smalltext"><strong><?php echo jLocale::get('havefnubb~main.last.message'); ?></strong>
        <?php echo jZone::get('havefnubb~postlc',array('id_forum'=>$t->_vars['forum']->id_forum));?></span></td>
    </tr>
<?php elseif($t->_vars['forum']->forum_type == 1):?>
    <tr>
        <td class="colleft colredirect"> </td>
        <td class="colmain linkincell"><h4 class="forumtitle"><a href="<?php echo $t->_vars['forum']->forum_url; ?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a></h4><span class="forumdesc"><?php echo htmlspecialchars($t->_vars['forum']->forum_desc); ?></span></td>
        <td class="colstats linkincell"> </td>
    </tr>    
<?php elseif($t->_vars['forum']->forum_type == 2):?>
    <tr>
        <td class="colleft colrss"> &nbsp;</td>
        <td class="colmain linkincell"><h4><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~forum:read_rss',array('id_forum'=>$t->_vars['forum']->id_forum,'ftitle'=>$t->_vars['forum']->forum_name));?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a></h4><span class="forumdesc"><?php echo htmlspecialchars($t->_vars['forum']->forum_desc); ?></span></td>
        <td class="colstats linkincell">&nbsp;</td>
    </tr>
<?php endif;?>
<?php  endif; ?>
<?php elseif($t->_vars['action'] =='index'):?>
<?php  if(jAcl2::check('hfnu.forum.list','forum'.$t->_vars['forum']->id_forum)):?>
<?php if($t->_vars['forum']->forum_type == 0):?>
    <tr>
        <td class="colleft <?php echo jZone::get('havefnubb~newestposts',array('source'=>'forum','id_forum'=>$t->_vars['forum']->id_forum));?>"></td>
        <td class="colmain linkincell"><h4 class="forumtitle"><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~posts:lists',array('id_forum'=>$t->_vars['forum']->id_forum,'ftitle'=>$t->_vars['forum']->forum_name));?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a></h4><span class="forumdesc"><?php echo htmlspecialchars($t->_vars['forum']->forum_desc); ?></span>
        <?php echo jZone::get('havefnubb~forumchild',array('id_forum'=>$t->_vars['forum']->id_forum,'lvl'=>1,'calledFrom'=>'home'));?></td>
        <td class="colstats"><?php echo jZone::get('havefnubb~postandmsg',array('id_forum'=>$t->_vars['forum']->id_forum));?></td>
        <td class="colright linkincell"><span class="smalltext"><strong><?php echo jLocale::get('havefnubb~main.last.message'); ?></strong>
        <?php echo jZone::get('havefnubb~postlc',array('id_forum'=>$t->_vars['forum']->id_forum));?></span></td>
    </tr>
<?php elseif($t->_vars['forum']->forum_type == 1):?>
    <tr>
        <td class="colleft_index colredirect"> </td>
        <td class="colmain_index linkincell"><h4 class="forumtitle"><a href="<?php echo $t->_vars['forum']->forum_url; ?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a></h4><span class="forumdesc"><?php echo htmlspecialchars($t->_vars['forum']->forum_desc); ?></span></td>
        <td class="colstats_index linkincell"> </td>
    </tr>    
<?php elseif($t->_vars['forum']->forum_type == 2):?>
    <tr>
        <td class="colleft_index colrss"> &nbsp;</td>
        <td class="colmain_index linkincell"><h4><a href="<?php jtpl_function_html_jurl( $t,'havefnubb~forum:read_rss',array('id_forum'=>$t->_vars['forum']->id_forum,'ftitle'=>$t->_vars['forum']->forum_name));?>" title="<?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?>"><?php echo htmlspecialchars($t->_vars['forum']->forum_name); ?></a></h4><span class="forumdesc"><?php echo htmlspecialchars($t->_vars['forum']->forum_desc); ?></span></td>
        <td class="colstats_index linkincell">&nbsp;</td> 
        <td class="colright_index"></td>
    </tr>
<?php endif;?>
<?php  endif; ?>
<?php endif;?>
<?php endforeach;?>
</table><?php 
}
?>