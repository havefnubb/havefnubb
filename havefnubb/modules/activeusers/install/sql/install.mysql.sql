
CREATE TABLE IF NOT EXISTS %%PREFIX%%connectedusers (
    sessionid  varchar(40) NOT NULL,
    login varchar(50) NULL,
    name varchar(50) NULL,
    member_ip VARCHAR(25) NOT NULL DEFAULT '',
    connection_date INT(10) UNSIGNED NOT NULL DEFAULT 0,
    last_request_date INT(10) UNSIGNED NOT NULL DEFAULT 0,
    disconnection_date INT(10) UNSIGNED NULL,
    PRIMARY KEY (sessionid)
) DEFAULT CHARSET=utf8;