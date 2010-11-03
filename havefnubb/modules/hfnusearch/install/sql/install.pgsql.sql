
CREATE TABLE %%PREFIX%%hfnu_search_words (
    id character varying(30) NOT NULL,
    datasource character varying(40) NOT NULL,
    words character varying(255) NOT NULL,
    weight integer NOT NULL,
    CONSTRAINT source_id PRIMARY KEY (id,datasource,words)
);




CREATE INDEX %%PREFIX%%hfnu_search_words ON %%PREFIX%%hfnu_search_words USING btree (words);
