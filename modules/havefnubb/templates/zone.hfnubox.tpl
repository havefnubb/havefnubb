<div id="statsinfos" class="four-cols">    
    {for $i=0; $i<count($widgets);$i++}    
    <div id="home-links" class="col">        
        {$widgets[$i]->content}
    </div>
    {/for}    
    <div class="clearboth"></div>
</div>
