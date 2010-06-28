CREATE TABLE %%PREFIX%%jmessenger (
    id serial NOT NULL,
    id_from integer DEFAULT 0 NOT NULL,
    id_for integer DEFAULT 0 NOT NULL,
    date timestamp without time zone NOT NULL,
    title character varying(255) NOT NULL,
    content text NOT NULL,
    "isSeen" smallint NOT NULL,
    "isArchived" smallint NOT NULL,
    "isReceived" smallint NOT NULL,
    "isSend" smallint NOT NULL
);
SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('%%PREFIX%%jmessenger', 'id'), 1, false);



ALTER TABLE ONLY %%PREFIX%%jmessenger
    ADD CONSTRAINT %%PREFIX%%jmessenger_pkey PRIMARY KEY (id);