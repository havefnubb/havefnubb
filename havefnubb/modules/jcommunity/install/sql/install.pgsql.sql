DROP TABLE IF EXISTS %%PREFIX%%community_users;
CREATE TABLE %%PREFIX%%community_users (
    id SERIAL,
    login TEXT NOT NULL,
    password TEXT NOT NULL,
    email TEXT NOT NULL,
    nickname TEXT,
    status INTEGER DEFAULT 0 NOT NULL,
    keyactivate TEXT,
    request_date TIMESTAMP WITHOUT TIME ZONE,
    create_date TIMESTAMP WITHOUT TIME ZONE NOT NULL,
    CONSTRAINT %%PREFIX%%community_users_login_pk PRIMARY KEY (login)
);
