CREATE TABLE %%PREFIX%%hfnu_subscript_forum (
    id_user bigint DEFAULT 0::bigint NOT NULL,
    id_forum bigint DEFAULT 0::bigint NOT NULL,
    CONSTRAINT id_user_forum PRIMARY KEY (id_user , id_forum)
);
