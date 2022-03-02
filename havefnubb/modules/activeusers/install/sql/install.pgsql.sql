
CREATE TABLE %%PREFIX%%connectedusers (
    sessionid  character varying(40) NOT NULL,
    login character varying(50) NULL,
    name character varying(50) NULL,
    member_ip character varying(40) NOT NULL,
    connection_date bigint DEFAULT 0::bigint NOT NULL,
    last_request_date bigint DEFAULT 0::bigint NOT NULL,
    disconnection_date bigint NULL,
    CONSTRAINT sessionid PRIMARY KEY (sessionid)
);
