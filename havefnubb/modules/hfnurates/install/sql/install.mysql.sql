CREATE TABLE IF NOT EXISTS %%PREFIX%%rates (
    id_user   INT NOT NULL ,
    id_source INT NOT NULL ,
    source    VARCHAR(40) NOT NULL,
    ip        VARCHAR(80) NOT NULL,
    level FLOAT NOT NULL ,
    INDEX ( id_user ),
    INDEX ( id_source ),
    INDEX ( source ),
    PRIMARY KEY rates_id (id_user,id_source,source)
)DEFAULT CHARSET=utf8;
