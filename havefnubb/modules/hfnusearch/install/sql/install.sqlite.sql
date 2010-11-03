CREATE TABLE %%PREFIX%%hfnu_search_words (
  id varchar(30) NOT NULL,
  datasource varchar(40),
  words varchar(255) NOT NULL,
  weight int(4) NOT NULL
);
CREATE UNIQUE INDEX %%PREFIX%%hfnu_search_words_source_id ON %%PREFIX%%hfnu_search_words (id,datasource,words);
CREATE INDEX %%PREFIX%%hfnu_search_words_words ON %%PREFIX%%hfnu_search_words (words);