


CREATE TABLE %%PREFIX%%hfnu_rates (
    id_user integer NOT NULL,
    id_source integer NOT NULL,
    source character varying(40) NOT NULL,
    ip character varying(80) NOT NULL,
    level double precision NOT NULL,
    CONSTRAINT rates_id PRIMARY KEY(id_user,id_source,source)
);


CREATE INDEX hfnu_rates_id_source_ix ON %%PREFIX%%hfnu_rates USING btree (id_source);

CREATE INDEX hfnu_rates_id_user_rates_idx ON %%PREFIX%%hfnu_rates USING btree (id_user);

CREATE INDEX hfnu_rates_source ON %%PREFIX%%hfnu_rates USING btree (source);