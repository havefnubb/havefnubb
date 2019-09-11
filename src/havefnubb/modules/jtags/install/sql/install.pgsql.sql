CREATE TABLE  IF NOT EXISTS %%PREFIX%%sc_tags (
    tag_id bigint NOT NULL,
    tag_name character varying(50) NOT NULL,
    nbuse integer DEFAULT 0,
    CONSTRAINT tag_id PRIMARY KEY (tag_id),
    CONSTRAINT tag_name UNIQUE (tag_name)
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('%%PREFIX%%sc_tags', 'tag_id'), 1, false);


CREATE TABLE  IF NOT EXISTS %%PREFIX%%sc_tags_tagged (
    tt_id bigint NOT NULL,
    tag_id bigint NOT NULL,
    tt_scope_id character varying(50) NOT NULL,
    tt_subject_id bigint NOT NULL,
    CONSTRAINT tt_id PRIMARY KEY (tt_id)
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('%%PREFIX%%sc_tags_tagged', 'tt_id'), 1, false);



CREATE INDEX idx1_tt ON %%PREFIX%%sc_tags_tagged USING btree (tt_scope_id, tt_subject_id);

CREATE INDEX idx2_tt ON %%PREFIX%%sc_tags_tagged USING btree (tag_id);