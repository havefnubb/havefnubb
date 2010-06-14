CREATE TABLE %%PREFIX%%search_words (
  id varchar(30) NOT NULL,
  datasource varchar(40),
  words varchar(255) NOT NULL,
  weight int(4) NOT NULL
);
CREATE UNIQUE INDEX %%PREFIX%%search_words_source_id ON %%PREFIX%%search_words (id,datasource,words);
CREATE INDEX %%PREFIX%%search_words_words ON %%PREFIX%%search_words (words);