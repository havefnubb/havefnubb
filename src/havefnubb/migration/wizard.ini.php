
; path related to the ini file. By default, the ini file is expected to be into the myapp/install/ directory.
pagesPath = "../lib/installwizard/pages/,wizard/pages/"
customPath = "wizard/custom/"
start = welcome
tempPath = "../temp/havefnubb/"
supportedLang = en,fr

appname = HaveFnuBB

[welcome.step]
next=migrate

[migrate.step]
next=installapp

[installapp.step]
next=end
level=notice

[end.step]
noprevious = on
messageFooter = "message.footer.end"
