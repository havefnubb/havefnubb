<?php 
function template_meta_d5fb6ee1638047ea7eab931da563c30e($t){

}
function template_d5fb6ee1638047ea7eab931da563c30e($t){
?><div id="navbar" class="up-and-down">
<?php foreach($t->_vars['menuitems'] as $t->_vars['bloc']):?>
    <?php if(count($t->_vars['bloc']->childItems)):?>
    <ul>
<?php foreach($t->_vars['bloc']->childItems as $t->_vars['item']):?>
        <li<?php if($t->_vars['item']->id == $t->_vars['selectedMenuItem']):?> class="selected"<?php endif; if($t->_vars['item']->icon):?> style="background-image:url(<?php echo $t->_vars['item']->icon; ?>);"<?php endif;?>>
<?php if($t->_vars['item']->type == 'url'):?><a href="<?php echo htmlspecialchars($t->_vars['item']->content); ?>"><?php echo htmlspecialchars($t->_vars['item']->label); ?></a>
<?php else: echo $t->_vars['item']->content;  endif;?>
        </li>
<?php endforeach;?>
    </ul>      
    <?php endif;?>
<?php endforeach;?>
</div><?php 
}
?>