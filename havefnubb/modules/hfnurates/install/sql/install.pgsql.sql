

DROP TABLE IF EXISTS %%PREFIX%%hfnu_rates;
CREATE TABLE %%PREFIX%%hfnu_rates (
    id_user INTEGER NOT NULL,
    id_source INTEGER NOT NULL,
    source TEXT NOT NULL,
    ip TEXT NOT NULL,
    level FLOAT NOT NULL,
    CONSTRAINT %%PREFIX%%hfnu_rates_id_user_id_source_source_pk PRIMARY KEY(id_user,id_source,source)
);

DROP INDEX IF EXISTS %%PREFIX%%hfnu_rates_id_source_idx;
CREATE INDEX %%PREFIX%%hfnu_rates_id_source_idx ON %%PREFIX%%hfnu_rates USING btree (id_source);

DROP INDEX IF EXISTS %%PREFIX%%hfnu_rates_id_user_idx;
CREATE INDEX %%PREFIX%%hfnu_rates_id_user_idx ON %%PREFIX%%hfnu_rates USING btree (id_user);

DROP INDEX IF EXISTS %%PREFIX%%hfnu_rates_source_idx;
CREATE INDEX %%PREFIX%%hfnu_rates_source_idx ON %%PREFIX%%hfnu_rates USING btree (source);
