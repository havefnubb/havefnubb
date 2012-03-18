; path related to the ini file. By default, the ini file is expected to be into the myapp/install/ directory.
pagesPath = "../../lib/installwizard/pages/"
customPath = "wizard_upd/custom/"
start = welcome
tempPath = "../../temp/havefnubb/"
supportedLang = en,fr

appname = HaveFnuBB

[welcome.step]
next=checkjelix

[checkjelix.step]
next=installapp
pathcheck[]="www:cache/"
pathcheck[]="www:files/"
pathcheck[]="var:config/havefnubb/config.ini.php"
pathcheck[]="var:config/havefnubb/flood.coord.ini.php"
pathcheck[]="var:config/havefnubb/activeusers.coord.ini.php"

[installapp.step]
next=end
level=notice

[end.step]
noprevious = on
messageFooter = "message.footer.end"
