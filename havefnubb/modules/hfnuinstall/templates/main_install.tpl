{meta_html csstheme 'css/text.css'}
{meta_html csstheme 'css/grid.css'}
{meta_html csstheme 'css/layout.css'}
{meta_html csstheme 'css/nav.css'}
{meta_html cssthemeie 'css/ie.css'}

<div class="container_16">

    <div class="grid_16">
        <h1 id="branding"><a href="{jurl 'havefnubb~default:index'}" >{$TITLE}</a></h1>
    </div>   
    <div class="clear"></div>    
    
    <div class="grid_16">
        <h2 id="page-heading">{$DESC}</h2>
    </div>
    <div class="clear"></div>    
    
    <div class="grid_16">
		<div class="box">
			<h2>{@hfnuinstall~install.home.welcome@}</h2>	
		</div>
		<div class="grid_4 alpha">
			<ol id="id">
		{if $step == 'update'}
			<li class="actif">{@hfnuinstall~install.home.update@} ></li>
		{else}
            <li {if $step == 'home'} class="actif"{/if}>{@hfnuinstall~install.home@}</li>
            <li {if $step == 'check'} class="actif"{/if}>{@hfnuinstall~install.checking@}</li>
            <li {if $step == 'config'} class="actif"{/if}>{@hfnuinstall~install.config@}</li>
            <li {if $step == 'dbconfig'} class="actif"{/if}>{@hfnuinstall~install.db@}</li>
            <li {if $step == 'installdb'} class="actif"{/if}>{@hfnuinstall~install.create.table@}</li>
            <li {if $step == 'adminaccount'} class="actif"{/if}>{@hfnuinstall~install.create.account.admin@}</li>
            <li {if $step == 'end'} class="actif"{/if}>{@hfnuinstall~install.end@}</li>    
		{/if}
			</ol>
        </div>
	    <div class="grid_12 omega">
	    {$MAIN}
	    </div>		
	</div> 
    <div class="clear"></div>
       
    <div class="grid_16" id="site_info">
        <div class="box">
            <p>{@havefnubb~main.poweredby@} <a href="http://www.havefnubb.org" title="HaveFnuBB!">HaveFnuBB!</a> - &copy; Copyright 2008 <a href="http://www.foxmask.info" title="FoxMaSk'Z H0m3">FoxMaSk</a>.</p>
        </div>
    </div>
    <div class="clear"></div>
    
</div>