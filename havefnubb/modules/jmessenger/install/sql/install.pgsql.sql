CREATE TABLE %%PREFIX%%jmessenger (
    id SERIAL,
    id_from INTEGER DEFAULT 0 NOT NULL,
    id_for INTEGER DEFAULT 0 NOT NULL,
    date TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    title TEXT NOT NULL,
    content TEXT NOT NULL,
    isSeen INTEGER NOT NULL,
    isArchived INTEGER NOT NULL,
    isReceived INTEGER NOT NULL,
    isSend INTEGER NOT NULL,
    CONSTRAINT %%PREFIX%%jmessenger_id_pk PRIMARY KEY (id)
);
