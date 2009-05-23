<!--div id="statsinfos" class="four-cols">
	<div id="home-links" class="col">
		<div class="headings_footer">
			<h3><span>{@havefnubb~main.home.links@}</span></h3>
        </div>
		<ul>
			<li><a href="{jurl 'havefnubb~default:index'}">{@havefnubb~main.home@}</a></li>
			<li><a href="{jurl 'hfnucontact~default:index'}">{@hfnucontact~contact.contact@}</a></li>
		</ul>
	</div>
	{zone 'havefnubb~lastposts'}
	{zone 'havefnubb~stats'}	
	<p class="col">
	</p>
<p class='clearboth'>&nbsp;</p>	
</div-->
{assign $nbPerCol = 0}
<div id="statsinfos" class="four-cols">    
    {for $i=$nbPerCol; $i<count($widgets);$i++}    
    <div id="home-links" class="col">        
        {$widgets[$i]->content}
    </div>
    {/for}    
    <div class="clearboth"></div>
</div>
