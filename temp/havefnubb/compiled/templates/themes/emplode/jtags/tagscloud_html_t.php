<?php 
 require_once('D:\wamp\www\havefnubb\lib\jelix/plugins/tpl/html/function.jurl.php');
function template_meta_a4e4ba9b373fdaee2ae3704a29112167($t){

}
function template_a4e4ba9b373fdaee2ae3704a29112167($t){
?><div class="headings">
    <h3><span><?php echo jLocale::get('havefnubb~main.cloud'); ?></span></h3>
</div>    
<p id="tagscloud">
    <?php foreach($t->_vars['tags'] as $t->_vars['t']):?>        
        <a href="<?php jtpl_function_html_jurl( $t,$t->_vars['destination'], array('tag'=>$t->_vars['t']->tag_name));?>" style="font-size:<?php echo $t->_vars['size'][$t->_vars['t']->tag_id]; ?>em" title="<?php echo $t->_vars['t']->tag_name; ?>"><?php echo $t->_vars['t']->tag_name; ?></a>,
    <?php endforeach;?>
</p>
<?php 
}
?>