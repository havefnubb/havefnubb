<span class="snippet-tags">
    <em>{assign $i=0}
        {foreach $tags as $t}
            {if $i>0},&nbsp;{/if}<a href="{jurl 'admin~snippets:index', array('tag'=>$t)}">{$t}</a>{assign $i = $i+1}
        {/foreach}</em>
</span>