
; path related to the ini file. By default, the ini file is expected to be into the myapp/install/ directory.
pagesPath = "../../lib/installwizard/pages/,wizard/pages/"
customPath = "wizard/custom/"
start = welcome
tempPath = "../../temp/havefnubb/"
supportedLang = en,fr

appname = HaveFnuBB

[welcome.step]
next=checkjelix

[checkjelix.step]
next=confmail
databases=mysql,pgsql,sqlite

[hfnconf.step]
next=confmail

[confmail.step]
next=dbprofile

[dbprofile.step]
next=installapp
availabledDrivers="mysql,sqlite,pgsql"
ignoreProfiles=""
messageHeader="message.header.dbProfile"

[installapp.step]
next=adminaccount
level=notice

[adminaccount.step]
next=end

[end.step]
noprevious = on
messageFooter = "message.footer.end"