CREATE TABLE  IF NOT EXISTS %%PREFIX%%sc_tags (
  tag_id int(10) NOT NULL PRIMARY KEY  ,
  tag_name varchar(50) NOT NULL,
  nbuse int(11) DEFAULT '0'
) ;

CREATE UNIQUE INDEX %%PREFIX%%sc_tags_uk_tag ON %%PREFIX%%sc_tags  (  tag_name );

CREATE TABLE  IF NOT EXISTS %%PREFIX%%sc_tags_tagged (
  tt_id int(10)  NOT NULL PRIMARY KEY,
  tag_id int(10)  NOT NULL,
  tt_scope_id varchar(50) NOT NULL,
  tt_subject_id int(10) NOT NULL
) ;
CREATE INDEX %%PREFIX%%sc_tags_tagged_idx1_tt ON %%PREFIX%%sc_tags_tagged  (tt_scope_id,tt_subject_id);
CREATE INDEX %%PREFIX%%sc_tags_tagged_idx2_tt ON %%PREFIX%%sc_tags_tagged  (tag_id);