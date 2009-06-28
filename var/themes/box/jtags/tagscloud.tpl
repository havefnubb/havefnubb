<div id="cloud-headings">
    <h2>{@havefnubb~main.cloud@}</h2>
</div>    
<p id="tagscloud">
    {foreach $tags as $t}
        {if $size[$t->tag_id] == 0 }
        {assign $theSize = 0.5}
        {else}
        {assign $theSize = $size[$t->tag_id]}
        {/if}
        <a href="{jurl $destination, array('tag'=>$t->tag_name)}" style="font-size:{$theSize}em" title="{$t->tag_name}">{$t->tag_name}</a>,
    {/foreach}

</p>
