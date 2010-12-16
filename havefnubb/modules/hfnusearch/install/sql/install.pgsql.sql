DROP TABLE IF EXISTS %%PREFIX%%hfnu_search_words;
CREATE TABLE %%PREFIX%%hfnu_search_words (
    id SERIAL,
    datasource TEXT NOT NULL,
    words TEXT NOT NULL,
    weight INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_search_words_id_datasource_words_pk PRIMARY KEY (id,datasource,words)
);

DROP INDEX IF EXISTS %%PREFIX%%hfnu_search_words_idx;
CREATE INDEX %%PREFIX%%hfnu_search_words_idx ON %%PREFIX%%hfnu_search_words USING btree (words);
