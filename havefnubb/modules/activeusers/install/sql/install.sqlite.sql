
CREATE TABLE %%PREFIX%%connectedusers (
    sessionid  VARCHAR(40) PRIMARY KEY,
    login varchar(50) NULL,
    name varchar(50) NULL,
    member_ip VARCHAR(40) NOT NULL DEFAULT '',
    connection_date INT(10) NOT NULL DEFAULT 0,
    last_request_date INT(10) NOT NULL DEFAULT 0,
    disconnection_date INT(10) NULL,
);
