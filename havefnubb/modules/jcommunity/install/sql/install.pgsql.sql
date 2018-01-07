CREATE TABLE IF NOT EXISTS %%PREFIX%%community_users (
    id serial NOT NULL,
    login character varying(50) NOT NULL,
    password character varying(120) NOT NULL,
    email character varying(255) NOT NULL,
    nickname character varying(50),
    status smallint DEFAULT 0::smallint NOT NULL,
    keyactivate character varying(10),
    request_date timestamp without time zone,
    create_date timestamp without time zone NOT NULL,
    CONSTRAINT community_users_login_pk PRIMARY KEY (login)
);
