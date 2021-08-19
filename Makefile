
VERSION=$(shell cat src/havefnubb/VERSION)
CURRENT_PATH = $(shell pwd)
DISTPATH=$(CURRENT_PATH)/_dist

PACKAGE_NAME=havefnubb-$(VERSION)

BUILDPATH=$(DISTPATH)/$(PACKAGE_NAME)

FILES=src/cache src/files src/havefnubb src/hfnu src/jelix src/themes src/forums.php src/hfnuadmin.php src/index.php src/install.php src/migration.php src/update.php
FORBIDDEN_CONFIG_FILES := installer.ini.php installer.bak.ini.php liveconfig.ini.php localurls.xml localconfig.ini.php profiles.ini.php
EMPTY_DIRS := cache/images/ files/ havefnubb/temp/ havefnubb/var/db havefnubb/var/feeds havefnubb/var/log havefnubb/var/mails havefnubb/var/overloads havefnubb/var/sessions havefnubb/var/themes havefnubb/var/uploads

.PHONY: build package deploy

clean:
	rm -rf $(BUILDPATH)

build:
	composer update --working-dir=src/havefnubb/ --prefer-dist --no-ansi --no-interaction --ignore-platform-reqs --no-dev --no-suggest --no-progress
	mkdir -p  $(BUILDPATH)
	cp -aR $(FILES) $(BUILDPATH)/
	mkdir -p $(BUILDPATH)/havefnubb/temp/havefnubb/
	@for file in $(FORBIDDEN_CONFIG_FILES); do rm -f $(BUILDPATH)/havefnubb/var/config/$$file; done;
	@for dir in $(EMPTY_DIRS); do rm -rf $(BUILDPATH)/$$dir/*;  touch $(BUILDPATH)/$$dir/.empty; done;
	rm -f $(BUILDPATH)/havefnubb/composer.*
	chmod -R o-w $(BUILDPATH)/

package:
	cd $(DISTPATH) && zip -rq $(PACKAGE_NAME).zip  $(PACKAGE_NAME)/
	rm -rf $(BUILDPATH)

deploy_package:
	scp $(DISTPATH)/$(PACKAGE_NAME).zip $(CI_DEPLOY_USER)@$(CI_DEPLOY_SERVER):$(HAVEFNUBB_JELIX_ORG_DEPLOY_DIR)/$(PACKAGE_NAME).zip
