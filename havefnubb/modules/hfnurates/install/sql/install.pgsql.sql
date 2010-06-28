


CREATE TABLE %%PREFIX%%rates (
    id_user integer NOT NULL,
    id_source integer NOT NULL,
    source character varying(40) NOT NULL,
    ip character varying(80) NOT NULL,
    level double precision NOT NULL,
    CONSTRAINT rates_id PRIMARY KEY(id_user,id_source,source)
);


CREATE INDEX id_source_ix ON %%PREFIX%%rates USING btree (id_source);

CREATE INDEX id_user_rates_idx ON %%PREFIX%%rates USING btree (id_user);

CREATE INDEX source ON %%PREFIX%%rates USING btree (source);