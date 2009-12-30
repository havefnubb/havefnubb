<h3>Rights Access</h3>
<p>
	Don't forget to change your rights access on all your folder to 755, and your files in 644,<strong> except for the 4 files located in var/config which have to be in 664 : defaultconfig.ini.php, dbprofils.ini.php, havefnu.ini.php, flood.ini.php,</strong><br/>
	<h4>change rights on directories</h4>
	<pre>{literal}find . -type d -exec chmod 755 {} \;{/literal}</pre>
	<h4>change rights on files</h4>
	<pre>{literal}find . -type f -exec chmod 644 {} \;{/literal}</pre>
</p>
