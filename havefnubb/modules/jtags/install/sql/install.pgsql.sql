DROP TABLE IF EXISTS %%PREFIX%%sc_tags;
CREATE TABLE %%PREFIX%%sc_tags (
    tag_id SERIAL,
    tag_name TEXT NOT NULL,
    nbuse INTEGER DEFAULT 0,
    CONSTRAINT %%PREFIX%%sc_tags_tag_id_pk PRIMARY KEY (tag_id),
    CONSTRAINT %%PREFIX%%sc_tags_tag_name_pk UNIQUE (tag_name)
);

DROP TABLE IF EXISTS %%PREFIX%%sc_tags_tagged;
CREATE TABLE %%PREFIX%%sc_tags_tagged (
    tt_id SERIAL,
    tag_id bigint NOT NULL,
    tt_scope_id TEXT NOT NULL,
    tt_subject_id INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%sc_tags_tagged_tt_id_pk PRIMARY KEY (tt_id)
);

DROP INDEX IF EXISTS %%PREFIX%%sc_tags_tagged_tt_scope_id_tt_subject_id_idx;
CREATE INDEX %%PREFIX%%sc_tags_tagged_tt_scope_id_tt_subject_id_idx ON %%PREFIX%%sc_tags_tagged USING btree (tt_scope_id, tt_subject_id);
DROP INDEX IF EXISTS %%PREFIX%%sc_tags_tagged_tag_id_idx;
CREATE INDEX %%PREFIX%%sc_tags_tagged_tag_id_idx ON %%PREFIX%%sc_tags_tagged USING btree (tag_id);
