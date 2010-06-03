ALTER TABLE hf_posts ADD ispined INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE hf_posts ADD INDEX ( ispined ) ;

ALTER TABLE hf_posts ADD iscensored INT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE hf_posts ADD INDEX ( iscensored ) ;

update hf_posts set status = 3,ispined=1,iscensored=0 where status = "pined";
update hf_posts set status = 4,ispined=1,iscensored=0 where status = "pinedclosed";
update hf_posts set status = 3,ispined=0,iscensored=0 where status = "opened";
update hf_posts set status = 4,ispined=0,iscensored=0 where status = "closed";
update hf_posts set status = 4,ispined=0,iscensored=1 where status = "censored";
update hf_posts set status = 3,ispined=0,iscensored=0 where status = "uncensored";
update hf_posts set status = 7,ispined=0,iscensored=0 where status = "hidden";


ALTER TABLE hf_posts CHANGE status status INT( 2 ) NOT NULL DEFAULT '3';

CREATE TABLE IF NOT EXISTS hf_threads (
  id_thread int(11) NOT NULL AUTO_INCREMENT,
  id_forum int(11) NOT NULL,
  id_user INT NOT NULL,
  status int(11) NOT NULL,
  id_first_msg int(11) NOT NULL,
  id_last_msg int(11) NOT NULL,
  date_created int(11) NOT NULL,
  date_last_post int(11),
  nb_viewed int(11) DEFAULT '0',
  nb_replies int(11) DEFAULT '0',
  ispined INT( 1 ) NOT NULL DEFAULT '0',
  iscensored INT( 1 ) NOT NULL DEFAULT '0',
  PRIMARY KEY (id_thread),
  KEY id_forum (id_forum),
  KEY id_user (id_user),
  KEY status (status),
  KEY id_first_msg (id_first_msg),
  KEY id_last_msg (id_last_msg),
  KEY ispined (ispined),
  KEY iscensored (iscensored)
) DEFAULT CHARSET=utf8;

INSERT INTO hf_threads (id_thread,id_forum,id_user,status,nb_viewed,date_created,ispined,iscensored) SELECT parent_id,id_forum,id_user,status,viewed,date_created,ispined,iscensored FROM hf_posts WHERE parent_id=id_post;

UPDATE hf_threads SET id_first_msg = id_thread ;
UPDATE hf_threads SET id_last_msg = ( SELECT id_post FROM hf_posts WHERE hf_threads.id_thread = parent_id ORDER BY date_created DESC LIMIT 1), date_last_post =( SELECT date_created FROM hf_posts WHERE hf_threads.id_thread = parent_id ORDER BY date_created DESC LIMIT 1);
UPDATE hf_threads SET nb_replies = (SELECT count(id_post) -1 FROM hf_posts WHERE hf_threads.id_thread = parent_id);


