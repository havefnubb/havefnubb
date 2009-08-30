ALTER TABLE hf_member CHANGE member_last_post member_last_post INT( 12 ) NOT NULL DEFAULT '0' ;
ALTER TABLE hf_member ADD `member_gravatar` INT( 1 ) NOT NULL DEFAULT '0' ;
