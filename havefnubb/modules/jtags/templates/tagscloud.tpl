<p id="tagscloud">
    {foreach $tags as $t}

        <a href="{jurl $destination, array('tag'=>$t->tag_name)}" class="tag{$size[$t->tag_id]}" title="Tag {$t->tag_name}">{$t->tag_name}</a>,
    {/foreach}
</p>
